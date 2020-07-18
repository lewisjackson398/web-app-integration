<?php
/**
 * Create an abstract Registry
 *
 * @author John Rooksby 
 * @version 8.1
 *  
 */ 
Abstract Class Registry {
    private function __construct() {}
    abstract protected function get($key);
    abstract protected function set($key, $value);
}
?>
