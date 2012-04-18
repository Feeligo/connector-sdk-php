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
 * @package    FeeligoAdapterInterface
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

interface FeeligoApi {
  
  public function community_api_key();
  
  public function community_secret();
  
  public function community();
  
  public function remote_api_endpoint_url();
    
}