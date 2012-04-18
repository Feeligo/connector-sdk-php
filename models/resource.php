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
 * @package    FeeligoResourceElement
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * a Resource is an Entity that can have an URL
 *
 */
 
interface FeeligoResource {
  
  public function url();
  
  public function as_json();

}