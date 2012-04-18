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
 * A FeeligoResourceFactory takes FeeligoEntityElements and FeeligoEntityCollections and returns
 * them decorated as Resources
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity/collection.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity/element.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../resource/element.php');

class FeeligoResourceFactory {
  
  public static function decorate(FeeligoEntity $entity, $url_helper = null) {
    
    // instantiate a UrlHelper at most once
    if ($url_helper === null) $url_helper = new FeeligoHelperUrl();
    
    if ($entity instanceof FeeligoEntityElement) {
      // decorate the object
      return new FeeligoResourceElement($entity, $url_helper);
      
    }elseif ($entity instanceof FeeligoEntityCollection) {
      // make a new collection and fill it with decorated elements
      $collection = new FeeligoEntityCollection($entity->type());
      foreach($entity->all() as $el) {
        $collection->add(self::decorate($el, $url_helper));
      }
      return $collection; 
    }
    return null;
  }

}