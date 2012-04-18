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
 * @package    EntityCollection
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * An EntityCollection is an Entity which holds other Entities
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity.php');

class FeeligoEntityCollection extends FeeligoEntity {
  
  public function __construct ($type) {
    parent::__construct($type); 
    $this->_elements = array();
  }
  
  public function all($limit = null, $offset = 0) {
    if ($limit !== null) return array_slice($this->_elements($offset, $limit));
    return $this->_elements;
  }
  
  public function add($el) {
    if ($el->type() != $this->type()) {
      return false;
    }
    $this->_elements[] = $el;
  }
  
  public function count() {
    return sizeof($this->_elements);
  }
  
  public function as_json () {
    $json = array();
    $els = $this->all();
    if (sizeof($els) > 0) {
      foreach($els as $el) {
        $json[] = $el->as_json();
      }
    }
    return $json;
  }
  
}