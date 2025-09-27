<?php

namespace Coelho;

abstract class Controller {

    public $openTransaction;
    public $rollbackOnException;

    function __construct(
        $openTransaction = true,
        $rollbackOnException = true
    ) {
        $this->openTransaction = $openTransaction;
        $this->rollbackOnException = $rollbackOnException;
    }

    abstract public function output($content);
    
    abstract public function output_error($exception);

    public function controller_exists() {
        return get_class($this) !== "Controller";
    }
}