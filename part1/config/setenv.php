<?php
define('CONFIGLOCATION', 'config/config.xml');
// turn on all possible errors
error_reporting(-1);
// display errors, should be value of 0, in a production system of course
ini_set("display_errors", 1);


function errorHandler($errno, $errstr, $errfile, $errline) {
	http_response_code(500);
	echo json_encode(array("message" => "Error Detected: [$errno] $errstr"));
}

set_error_handler('errorHandler');


function exceptionHandler ($e) {
    http_response_code(500);
	echo json_encode(array("message" => "Exception Detected - $e "));
}
set_exception_handler('exceptionHandler');


	/**
	*
	* Autoload classes. This uses include, but you might prefer require. 
	*
	*/
	function autoloadClasses($className) {
	    $filename = "classes/" . strtolower($className) . ".php";
	    if (is_readable($filename)) {
	        include $filename;
	    }
	}

spl_autoload_register("autoloadClasses");

?>