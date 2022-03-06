<?php

abstract class RestController {
    public $template = null; 
    function __construct() {
    }

    public function output($json = null) {
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($json);
    }
    
    public function output_error($exception) {
        $code = $exception->getCode();
        if (is_http_code($code)) {
            http_response_code($code);
        }
    }
}