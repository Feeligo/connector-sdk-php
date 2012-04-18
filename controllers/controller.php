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
require_once(str_replace('//','/',dirname(__FILE__).'/').'auth.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../models/resource/factory.php');
 
class FeeligoController {

  public function __construct(FeeligoApi $api) {
    $this->_api = $api;
    
    // authentication
    $this->_auth = new FeeligoControllerAuth($api);
    
    // URL helper
    $this->_url_helper = new FeeligoHelperUrl();
    
    // response
    $this->_response = new FeeligoControllerResponse($this->request());
    
    $this->_can_paginate = false;
  }
  
  /**
   * Accessor for the authentication object
   */
  public function auth() {
    return $this->_auth;
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
   * determine and execute action
   */
  public function run() {
    
    $data = null;
    $errors = array();
    
    // authentication
    $token = $this->auth()->decode_community_api_user_token($this->param('token'));
    if ($token === null) {
      return $this->response()->error('token', 'invalid')->error('try', $this->auth()->get_community_api_user_token(1)->encode())->fail_unauthorized();
    }
    
    // pagination
    $this->pagination_limit = (int) $this->param('lim', $this->param('limit', 50));
    $this->pagination_offset = (int) $this->param('off', $this->param('offset', 0));
      
    // which type
    $controller = $this->url(0);
    
    if ($controller == 'search') {
      
      if (!($query = $this->param('q'))) { $this->_error('query', 'missing'); return $this->_fail(); }
      if (!($type = $this->param('t'))) { $this->_error('type', 'missing'); return $this->_fail(); }  
        
      if ($type == 'user') {
        $data = $this->community()->users()->search($query, $this->pagination_limit, $this->pagination_offset);
        $this->_can_paginate = true;
      } else {
        return $this->response()->error('type', "$type is not a valid type for $controller")->fail_bad_request();
      }
    }elseif ($controller == 'users') {
      if ($this->url(1)) {
        try {
          $data = $this->community()->users()->find($this->url(1));
          if ($this->url(2) == 'friends') {
            $data = $data->friends();
            if ($this->url(3) !== null) {
              $data = $data->find($this->url(3));
              if ($this->url(4) !== null) {
                return $this->response()->error('path', $this->url()." is not a valid path")->fail_bad_request();
              }
            }else{
              $this->_can_paginate = true;
            }
          }
        } catch (FeeligoEntityNotFoundException $e) {
          return $this->response()->error($e->type(), $e->message())->fail_not_found();
        }
      }else{
        $data = $this->community()->users()->all($this->pagination_limit, $this->pagination_offset);
        $this->_can_paginate = true;
      }
    }elseif ($controller == 'actions') {
      // only allow POST
      if ($this->request()->is_post()) {
        
      }else{
        return $this->response()->error('method', 'not allowed')->fail_method_not_allowed();
      }
    }else{
      return $this->response()->error('path', $this->url()." is not a valid path")->fail_bad_request();
    }
    
    if ($data !== null) {
      // decorate resources by adding URLs
      $data = FeeligoResourceFactory::decorate($data);
      // data in json format
      $data = $data->as_json();
    }
    
    $data = $this->_paginate_data(array(
      'time' => time(),
      'data' => $data
    ));
      
    $this->response()->set_data($data);
    return $this->response()->success();
  }
  
  private function _paginate_data($data) {
    if ($this->_can_paginate && $this->pagination_limit !== null) {
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
  
}