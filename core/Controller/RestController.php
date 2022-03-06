<?php

abstract class RestController {
    public $template = null; 
    function __construct() {
    }

    public function output($json = null) {
        header('Content-Type: text/html; charset=UTF-8');
        echo json_encode($json);
    }
}