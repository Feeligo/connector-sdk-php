<?php
/**
 * Feeligo
 *
 * @category   Feeligo Common
 * @package    Feeligo_Common
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    FeeligoApiController
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../helpers/url.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'response.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../models/resource/factory.php');

/**
 * Exception used to break the controller's execution and set an error message into the response
 */
class FeeligoControllerException extends Exception {
  
  public function __construct($status, $type, $msg) {
    parent::__construct("$type: $msg");
    $this->_status = $status;
    $this->_type = $type;
    $this->_message = $msg;
  }
  
  public function status() { return $this->_status; }
  public function type() { return $this->_type; }
  public function message() { return $this->_message; }
}
 
 
/**
 * Single controller: determines the requested action, executes it and returns a response
 */ 
class FeeligoController {

  public function __construct(FeeligoApi $api) {
    $this->_api = $api;
    
    // URL helper
    $this->_url_helper = new FeeligoHelperUrl();
    
    // response
    $this->_response = new FeeligoControllerResponse($this->request());
    
    $this->_does_paginate = false;
  }
  
  public function run () {
    try {
      $this->_run();
    } catch (FeeligoControllerException $e) {
      $this->response()->error($e->type(), $e->message());
      return $this->response()->fail($e->status());
    }
    return $this->response()->success();
  }
  
  /**
   * Accessor for API object
   */
  public function api() {
    return $this->_api;
  }
  
  /**
   * Accessor for the authentication object
   */
  public function auth() {
    return $this->api()->auth();
  }
  
  /**
   * Accessor for the URL helper
   */
  public function url_helper() {
    return $this->_url_helper;
  }
  
  /**
   * Accessor for the Request
   */
  public function request() {
    return $this->url_helper()->request();
  }
  
  /**
   * Accessor for the URL
   */
  public function url($i = null) {
    return $this->request()->url($i);
  }
  
  /**
   * Accessor for the params
   */
  public function param($name, $default_val = null) {
    return $this->request()->param($name, $default_val);
  }
  
  /**
   * Accessor for the Response
   */
  public function response() {
    return $this->_response;
  }
  
  /**
   * Accessor for the Community
   */
  public function community() {
    return $this->_api->community();
  }
  
  /**
   * url_for helper
   */
  public function url_for($new_flg_url = null, $new_params = null) {
    return $this->_url_helper->url_for($new_flg_url, $new_params);
  }

  /**
   * Actually performs the action
   */
  private function _run() {
    
    $data = null;
    $errors = array();
    
    // authentication
    $token = $this->auth()->decode_community_api_user_token($this->param('token'));
    if ($token === null) {
      $this->_fail_unauthorized('token', 'invalid');
    }
    
    // pagination
    $this->pagination_limit = (int) $this->param('lim', $this->param('limit', 100));
    $this->pagination_offset = (int) $this->param('off', $this->param('offset', 0));
      
    // which type
    $controller = $this->url(0);
    
    if ($controller == 'search') {
      // path: search/  :  search
      if (!($type = $this->param('t'))) { $this->_fail_bad_request('type', "missing"); }  
        
      if ($type == 'user') {
        // search among users
        $data = $this->_data_search($this->community()->users());
      } else {
        // only allows searching users
        $this->_fail_bad_request('type', "$type is not a valid type for $controller");
      }
    }elseif ($controller == 'users') {
      // path: users/  :  select community users
      $data = $this->community()->users();
      
      if ($this->url(1) == 'search') {
        // path: users/search  :  search among users
        $data = $this->_data_search($data);
        
      }else if ($this->url(1)) {
        
        // path: users/:id  :  select user by id  
        try {
          $data = $data->find($this->url(1));
          
          if ($this->url(2) == 'friends') {
            // users/:id/friends  :  select user's friends
            
            // access to friends is restricted : check token
            if ($token->user_id().'' !== $this->url(1)) {
              $this->_fail_unauthorized('privacy', "you are not allowed to access this user's friends");
            }
            
            // select user's friends
            $data = $data->selector_friends();
            
            if ($this->url(3) == 'search') {
              // path: users/:id/friends/search  :  search among friends
              $data = $this->_data_search($data);
               
            }else if ($this->url(3) !== null) {
              // path: users/:id/friends/:friend_id  :  select specific friend by id
              $data = $data->find($this->url(3));
              
              if ($this->url(4) !== null) {
                // path: users/:id/friend/:friend_id/:something  :  invalid path
                $this->_fail_bad_request('path', $this->url()." is not a valid path");  
              }
            }else{
              // path: users/:id/friends  :  list all friends of user :id
              $data = $this->_data_all($data);
            }
          }else if ($this->url(2) !== null) {
            // users/:id/something  :  invalid
            $this->_fail_bad_request('path', $this->url()." is not a valid path");
          }
        } catch (FeeligoEntityNotFoundException $e) {
          // if either user :id or his friend :friend_id was not found
          $this->_fail_not_found($e->type(), $e->message());
        }
      }else{
        // path: users/  :  list all users of the community
        $data = $this->_data_all($this->community()->users());
      }
    }elseif ($controller == 'actions') {
      // only allow POST
      if ($this->request()->is_post()) {
        $this->_fail_bad_request('actions', 'is not a valid path'); // scheduled for next release
      }else{
        $this->_fail_method_not_allowed('method', 'not allowed');
      }
    }else{
      $this->_fail_bad_request('path', $this->url()." is not a valid path");
    }
    
    if ($data !== null) {
      // decorate resources by adding URLs
      $data = FeeligoResourceFactory::decorate($data);
      // data in json format
      $data = $data->as_json();
    }
    
    // add pagination information if needed
    $data = $this->_add_pagination_info(array(
      'time' => time(),
      'data' => $data
    ));
      
    $this->response()->set_data($data);
  }
  
  /**
   * calls the search() method on $data, passing query, type and pagination parameters
   */
  private function _data_search($data, $query = null) {
    // ensure there is a query, either passed or in the params
    if ($query === null && ($query = $this->param('q')) === null) { $this->_fail_bad_request('query', "missing"); }
    // enable pagination
    $this->_does_paginate = true;
    // apply search()
    return $data->search($query, $this->pagination_limit, $this->pagination_offset);
  }
  
  /**
   * calls the all() method on $data, passing pagination parameters
   */
  private function _data_all($data) {
    // enable pagination
    $this->_does_paginate = true;
    // apply all()
    return $data->all($this->pagination_limit, $this->pagination_offset);
  }
  
  
  /**
   * add pagination information to data (if paginated)
   */
  private function _add_pagination_info($data) {
    if ($this->_does_paginate && $this->pagination_limit !== null) {
      // show 'previous' link if offset > 0
      if ($this->pagination_offset > 0) {
        $previous_offset = max(array($this->pagination_offset - $this->pagination_limit,0));
        $params = $this->request()->params();
        if ($previous_offset == 0) {
          unset($params['off']);
        }else{
          $params['off'] = $previous_offset;
        }
        if (!isset($data['paging'])) $data['paging'] = array();
        $data['paging']['previous'] = $this->url_for($params);
      }
      // show 'next' link if limit reached
      if (isset($data['data']) && $this->pagination_limit == sizeof($data['data'])) {
        $params = $this->request()->params();
        $params['off'] = $this->pagination_offset + $this->pagination_limit;
        if (!isset($data['paging'])) $data['paging'] = array();
        $data['paging']['next'] = $this->url_for($params);
      } 
    }
    return $data;
  }
  
  /**
   * convenience methods to throw controller exceptions
   */
  private function _fail_bad_request($type, $msg) { // 400
    throw new FeeligoControllerException(FeeligoControllerResponse::HTTP_BAD_REQUEST, $type, $msg);
  }
  private function _fail_unauthorized($type, $msg) { // 401
    throw new FeeligoControllerException(FeeligoControllerResponse::HTTP_UNAUTHORIZED, $type, $msg);
  }
  private function _fail_not_found($type, $msg) { // 404
    throw new FeeligoControllerException(FeeligoControllerResponse::HTTP_NOT_FOUND, $type, $msg);
  }
  private function _fail_method_not_allowed($type, $msg) { // 405
    throw new FeeligoControllerException(FeeligoControllerResponse::HTTP_METHOD_NOT_ALLOWED, $type, $msg);
  }
  
}