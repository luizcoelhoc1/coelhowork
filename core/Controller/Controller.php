<?php

abstract class Controller {

    public $openTransaction;
    public $rollbackOnFinish;
    public $rollbackOnException;
    public $throwErrorOnConnectionFail;

    function __construct(
        $openTransaction = true,
        $rollbackOnFinish = false,
        $rollbackOnException = false,
        $throwErrorOnConnectionFail = true,
    ) {
        $this->openTransaction = $openTransaction;
        $this->rollbackOnFinish = $rollbackOnFinish;
        $this->rollbackOnException = $rollbackOnException;
        $this->throwErrorOnConnectionFail = $throwErrorOnConnectionFail;

    }

    abstract public function output($params);
    
    abstract public function loads();

    abstract public function guards();

    abstract public function output_error($exception);

    public function controller_exists() {
        return get_class($this) !== "Controller";
    }
}