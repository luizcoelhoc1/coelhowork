<?php

abstract class Controller {

    public $open_transaction = true;
    public $rollback_on_finish = false;
    public $rollback_on_exception = false;
    public $throw_error_on_connection_fail = true;

    function __construct() {

    }

    abstract public function output($params);
    
    abstract public function loads();

    abstract public function guards();

    abstract public function output_error($exception);

    public function controller_exists() {
        return get_class($this) !== "Controller";
    }
}