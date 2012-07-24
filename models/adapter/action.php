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
 * @package    FeeligoAdapterAction
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../entity/element.php');
 
abstract class FeeligoAdapterAction extends FeeligoEntityElement {
  
  public function __construct($id) {
    parent::__construct('action', $id);
  }

  public function as_json() {
    return array_merge(parent::as_json(), array(

    ));
  }
}