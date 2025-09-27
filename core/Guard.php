<?php

namespace Coelho;
use Coelho\helpers\Http;

abstract class Guard {

    abstract function can() : bool;

    function customFail ($route) {  }

}