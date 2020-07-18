<?php

/**
 * Create an applicaiton registry for handling global values
 *
 * This is the solution for week 8. This uses the singleton pattern to return global style values.
 * Make sure CONFIGLOCATION is specified in setEnv.php
 *
 * @author John Rooksby
 * @version 8.1
 *  
 */ 
Class ApplicationRegistry extends Registry {
   
    private $values = array();
    private static $instance;

    private function __construct() {
        $this->openSystemConfigFile();
    }

    private static function instance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get($key) {
        return isset($this->values[$key]) ? $this->values[$key] : null;
    }

    protected function set($key, $value) {
        $this->values[$key] = $value;
    }

    private function openSystemConfigFile() {
        $filename = CONFIGLOCATION;  //CONFIGLOCATION a constant defined elsewhere
        if (file_exists($filename)) {
            $temp = simplexml_load_file($filename);
            foreach ($temp as $key => $value) {
                $this->set($key, trim($value));
            }
        }
    }
    
    public static function getDBName() {
        return self::instance()->get('dbname');
    }

    public static function DB() {
        return pdoDB::getConnection();
    }

}
?>
