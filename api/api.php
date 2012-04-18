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

abstract class FeeligoApi {
  
  const __community_api_key = FLG__community_api_key;
  const __community_secret = FLG__community_secret;
  const __remote_server_url = FLG__server_url;
  
  /**
   * Accessors for Feeligo params
   */
  public function community_api_key() { 
    return self::__community_api_key;
  }
  public function community_secret() {
    return self::__community_secret;
  }

  public function remote_server_url() {
    return self::__remote_server_url;
  }
  public function remote_api_endpoint_url() {
    return self::remote_server_url().'c/'.self::community_api_key().'/api/';
  }
  
  /**
   * The singleton Api object
   *
   * @var FeeligoApi
   */
  protected static $_instance;
  
  /**
   * Get or create the current api instance
   * 
   * @return FeeligoApi
   */
  public static function getInstance() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Shorthand for getInstance
   *
   * @return FeeligoApi
   */
  public static function _() {
    return self::getInstance();
  }
  
  /**
   * protected constructor prevents instantiation from outside
   */
  protected function __construct() {}
  
  /**
   * accessor for the Community adapter
   */
  public function community();
    
}