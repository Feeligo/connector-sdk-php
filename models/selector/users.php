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
 * @package    FeeligoSelectorUsers
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity/selector.php'); 
 
interface FeeligoSelectorUsers extends FeeligoEntitySelector {
    
  public function search($query, $limit = null, $offset = 0);
  
  /** To be implemented
  */
    
}