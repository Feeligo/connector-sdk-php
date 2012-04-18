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
 * @package    FeeligoEntity
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * an Entity is any meaningful object of a given Type
 * - not necessarily persistent
 * - does not have a URL nor an ID
 */
 
abstract class FeeligoEntity {
  
  public function __construct($type) {
    $this->_type = $type;
  }
  
  public function type () {
    return $this->_type;
  }
    
  public abstract function as_json();
  
}