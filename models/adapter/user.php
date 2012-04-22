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
 * @package    FeeligoUser
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity/element.php');
 
abstract class FeeligoAdapterUser extends FeeligoEntityElement {
  
  public function __construct($adaptee, $id) {
    parent::__construct('user', $id);
    $this->_user = $adaptee;
  }
  
  public function user() {
    return $this->_user;
  }
  
  public abstract function name();
  public abstract function username();
  public abstract function link();

  public abstract function picture_url();  
  
  public abstract function selector_friends();

  public function as_json() {
    return array_merge(parent::as_json(), array(
      'name' => $this->name(),
      'username' => $this->username(),
      'link' => $this->link(),
      'picture' => $this->picture_url()
    ));
  }
}