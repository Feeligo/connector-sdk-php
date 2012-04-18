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
 * @package    FeeligoEntityElementCollection
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * FeeligoEntityElementCollection stores FeeligoEntityElement instances in an Array
 * - can have duplicates if an element is added more than once
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../element.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../collection.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../not_found_exception.php');

class FeeligoEntityElementCollection extends FeeligoEntityCollection {
    
  public function find($el_or_id, $throw = true) {
    $id = $this->_get_id($el_or_id);
    if (($r = $this->_find($id)) !== null) return $r;
    if ($throw) throw new FeeligoEntityNotFoundException($this->type(), 'could not find '.$this->type().' with id='.$id);
    return null;
  }
  
  protected function _find($id) {
    return ($i = $this->_index_of($id)) !== null ? $this->_elements[$i] : null;
  }
  
  public function index_of($el_or_id) {
    return $this->_index_of($this->_get_id($el_or_id));
  }
  
  protected function _index_of($id) {
    for ($i=0; $i<sizeof($this->_elements); $i++) {
      if ($this->_elements[$i]->id() == $id) { return $i; }
    }
    return null;
  }
  
  public function delete($el_or_id) {
    $i = $this->index_of($el_or_id);
    if ($i !== null) {
      unset($this->_elements[$i]);
      return true;
    }
    return false;
  }
  
  public function contains($el_or_id) {
    return $this->index_of($el_or_id) !== null;
  }
    
  protected function _get_id($el_or_id) {
    return FeeligoEntityElement::id_of($el_or_id);
  }
    
}