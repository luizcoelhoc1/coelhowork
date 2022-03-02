<?php

class Controller {

    public $template = null; 
    public $open_transaction = true;
    public $rollback_on_finish = false;
    public $rollback_on_exception = false;

    function __construct() {

    }

    public function output($string) {
        header('Content-Type: text/html; charset=UTF-8');
        if (!empty($this->template)) {
            loadView($this->template, [
                "content" => $string
            ]);
        } else {
            echo $string;
        }
    }
    
    public function loading() {

    }

    public function checks() {

    }
}