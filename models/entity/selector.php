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
 * @package    FeeligoResourceCollection
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * A FeeligoEntitySelector selects and returns FeeligoEntityElement's or FeeligoEntityCollection's
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'not_found_exception.php'); 
 
interface FeeligoEntitySelector {
 
  public function all($limit, $offset);
 
  public function find($id);
 
  public function find_all($ids);
  
}