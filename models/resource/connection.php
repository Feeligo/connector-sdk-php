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
 * @package    FeeligoResourceConnection
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * A Connection is a Resource that belongs to a Resource and points to another Resource
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity.php');
 
class FeeligoResourceConnection extends FeeligoEntity {
  
  public function __construct($url) {
    parent::__construct('connection');
    $this->_url = $url;
  }
  
  public function as_json () {
    return $this->_url;
  }
  
}