<?php

namespace Coelho\controllers;

use Coelho\Controller;
use Coelho\helpers\Util;

abstract class RestController extends Controller {
    function __construct() {
    }

    public function output($json = null) {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(200);
        echo json_encode($json);
    }
    
    public function output_error($exception) {
        $code = $exception->getCode();
        if (Util::is_http_code($code)) {
            http_response_code($code);
        }
    }
}