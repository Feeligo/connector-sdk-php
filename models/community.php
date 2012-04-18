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
 * @package    FeeligoAdapterCommunityInterface
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'entity/element.php'); 
 
abstract class FeeligoCommunity extends FeeligoEntityElement {
  
  public function __construct() {
    parent::__construct('community', '/');
  }
  
  public abstract function users();
  
  public abstract function actions();

  
  public function as_json () {
    return array(
      'url' => $this->url,
      'message' => 'Feeligo API'
    );
  }
    
}