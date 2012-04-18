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
 * @package    FeeligoControllerResponseEncoder
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * Encodes a $data variable (string|null|array) in a specific format and outputs a string
 */
 
interface FeeligoControllerResponseEncoder {

  public function encode($data);
  
  public function content_type();
  
}