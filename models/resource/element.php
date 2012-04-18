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
 * A ResourceElement is an EntityElement which has a URL
 * 
 * this class uses the Decorator pattern to add a url field to an Entity
 * - the URL is obtained from a URL helper (which makes it from the type and ID of the resource)
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../resource.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'connection.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity/collection/hash.php');
 
class FeeligoResourceElement extends FeeligoEntityElement implements FeeligoResource {
  
  public function __construct(FeeligoEntityElement $entity, FeeligoHelperUrl $url_helper = null) {
    parent::__construct($entity->type(), $entity->id());
    $this->_entity = $entity;
    $this->_url_helper = $url_helper !== null ? $url_helper : new FeeligoHelperUrl();
    //$this->_connections = new FeeligoEntityCollectionHash('connection');
  }
  
  public function url() {
    return $this->_url_helper->url_for_entity_element($this->_entity());
  }
  
  public function id() {
    return $this->_entity()->id();
  }
  
  public function as_json() {
    return array_merge(
      $this->_entity()->as_json(),
      array(
        'url' => $this->url()
      )
    );
  }

  protected function _entity() {
    return $this->_entity;
  }  
  
  /*
   * Connections : TODO: Need to change this
   */
  /*
  public function connections() {
    return $this->_connections;
  }
  
  protected function _add_connection($name, $url) {
    $this->_connections->add($name, new FeeligoResourceConnection($url, $this));
  }
  */
  
}