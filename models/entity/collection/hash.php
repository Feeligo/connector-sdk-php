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
 * @package    ResourceCollection
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * A FeeligoEntityCollectionHash is an Entity which holds Entities as a Hash
 * - keys must be specified when adding a value
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../collection.php');

class FeeligoEntityCollectionHash extends FeeligoEntityCollection {
  
  public function add($key, $el) {
    if ($el->type() != $this->type()) {
      return false;
    }
    $this->_elements[$key] = $el;
  }
  
  public function as_json () {
    $json = array();
    $els = $this->all();
    if (sizeof($els) > 0) {
      foreach($els as $k => $el) {
        $json[$k] = $el->as_json();
      }
    }
    return $json;
  }
  
}