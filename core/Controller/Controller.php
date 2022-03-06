<?php

abstract class Controller {

    public $open_transaction = true;
    public $rollback_on_finish = false;
    public $rollback_on_exception = false;
    public $throw_error_on_connection_fail = true;

    function __construct() {

    }

    public function output($params) {

    }
    
    public function loading() {

    }

    public function checks() {

    }

    public function output_error($exception) {
        
    }
}