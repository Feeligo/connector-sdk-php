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
 * @package    EntityElement
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * An EntityElement is an Entity which represents a single element. It has an ID.
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity.php');

class FeeligoEntityElement extends FeeligoEntity {
  
  public function __construct ($type, $id) {
    parent::__construct($type); 
    $this->_id = $id;
  }
  
  public function id() {
    return $this->_id;
  }
  
  public function as_json() {
    return array(
      'id' => $this->id() . '', // important! we want all our ID's to be strings
      'type' => $this->type()
    );
  }
  
  protected function _get_id($el_or_id) {
    return self::id_of($el_or_id);
  }
  
  public static function id_of($el_or_id) {
    if (method_exists($el_or_id, 'id')) {
      return $el_or_id->id();
    }
    return $el_or_id;
  }
}