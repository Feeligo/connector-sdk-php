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
 * @package    FeeligoControllerAuthToken
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
class FeeligoControllerAuthToken {
  
  const COMMUNITY_API_USER_TOKEN_PREFIX = "community-api-user-token";
  
  const FIELD_DATA = 'data';
  const FIELD_AUTH = 'auth';
  const FIELD_AUTH_SIGNATURE = 's';
  const FIELD_AUTH_TIME = 't';
  
  protected function __construct ($fields, $signature, $time) {
    $this->_fields = $fields;
    $this->_signature = $signature;
    $this->_time = $time;
  }
  
  public function fields() {
    return $this->_fields;
  }
  
  public function field($name, $default = null) {
    $fields = $this->fields();
    return isset($fields[$name]) ? $fields[$name] : $default;
  }
  
  public function signature() {
    return $this->_signature;
  }
  
  public function api_key() {
    return $this->field('api_key');
  }
  
  public function user_id() {
    return $this->field('user_id');
  }
  
  public function payload() {
    return $this->field('payload');
  }
  
  public function time() {
    return $this->_time;
  }
  
  public function permissions() {
    return $this->_field('permissions', array());
  }
  
  public function has_permission($permission) {
    return in_array($permission, $this->permissions());
  }
  
  public function as_json() {
    return $this->fields();
  }
  
  public function encode() {
    if ($this->signature() === null) return null;
    return base64_encode(
      json_encode(array(
        self::FIELD_DATA => $this->as_json(),
        self::FIELD_AUTH => array(
          self::FIELD_AUTH_SIGNATURE => $this->signature(),
          self::FIELD_AUTH_TIME => $this->time()
        )
      ))
    );
  }
  
  
  public static function decode($token_str, $secret) {
    if ($token_str === null) return null;
    if (($token_json_string = base64_decode($token_str)) !== false) {
      if (($data = json_decode($token_json_string, true)) !== false) {
        
        // check that the token is signed
        if (!isset($data[self::FIELD_AUTH])) return null;
        $time = isset($data[self::FIELD_AUTH][self::FIELD_AUTH_TIME]) ? $data[self::FIELD_AUTH][self::FIELD_AUTH_TIME] : null;
        $signature = isset($data[self::FIELD_AUTH][self::FIELD_AUTH_SIGNATURE]) ? $data[self::FIELD_AUTH][self::FIELD_AUTH_SIGNATURE] : null;
        if ($time === null || $signature === null) return null;

        $token = self::make($data[self::FIELD_DATA], $secret, $time);

        if ($token->signature() == $signature) {
          return $token;
        }
      }
    }
    return null;
  }
  
  public static function make($fields, $secret, $time = null) {
    $time = ($time !== null) ? $time : time();
    $signature = sha1(self::COMMUNITY_API_USER_TOKEN_PREFIX.":".$secret.":".json_encode($fields).":".$time);
    return new self($fields, $signature, $time);
  }
  
  
}