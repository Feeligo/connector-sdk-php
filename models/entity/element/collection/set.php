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
 * @package    FeeligoEntityElementCollectionSet
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * A FeeligoEntityElementCollectionSet holds Entities as an id-indexed hash
 * - guarantees no duplicates (Entities identified by id)
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../collection.php');

class FeeligoEntityElementCollectionSet extends FeeligoEntityElementCollection {

  public function add($el) {
    $this->_elements[$el->id()] = $el;
  }
  
  protected function _index_of($id) {
    return $id;
  }
   
}