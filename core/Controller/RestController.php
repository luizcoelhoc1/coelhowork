<?php

abstract class RestController {
    public $template = null; 
    function __construct() {
    }

    public function output($json = null) {
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($json);
    }
    
    public function loads() {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        header('Content-Type: application/json; charset=utf-8');
    }
    
    public function output_error($exception) {
        $code = $exception->getCode();
        if (is_http_code($code)) {
            http_response_code($code);
        }
    }
}