<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    API Connector SDK for PHP
 * @copyright  Copyright 2012 Feeligo
 * @license
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    FeeligoUserPresenter
 * @copyright  Copyright 2012 Feeligo
 * @license
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'model.php');

/**
 * presenter class used for a single User
 */

class FeeligoUserPresenter extends FeeligoModelPresenter {

  public function __construct($item, $token=null) {
    parent::__construct($item);
    $this->_token = $token;
  }

  protected function path() {
    return 'users/'.$this->item()->id();
  }

  public function as_json() {
    // default attributes for all users
    $json = array_merge(parent::as_json(), array(
      'name' => $this->item()->name(),
      'link' => $this->item()->link(),
      'picture_url' => $this->item()->picture_url(),
      'birth_date' => $this->item()->birth_date()
    ));
    // private attributes just for the user who owns the token
    if ($this->token_user_id() == $this->item()->id()) {
      $json['email'] = $this->item()->email();
    }
    return $json;
  }
}