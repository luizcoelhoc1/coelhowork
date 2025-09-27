<?php

namespace Coelho\controllers;
use \Coelho\View;
use \Coelho\Controller;
use \Coelho\helpers\Util;

abstract class ServerRenderController extends Controller {
    public $template = null; 
    function __construct(
        $openTransaction = true,
        $rollbackOnException = false,
    ) {
        parent::__construct(
            $openTransaction,
            $rollbackOnException
        );
    }

    public function output($content = null) {
        header('Content-Type: text/html; charset=UTF-8');
        if ($content === null) {
            return;
        }
        if (!empty($this->template)) {
            echo View::load($this->template, [
                "content" => $content
            ]);
        } else if (is_string($content)) {
            echo $content;
        } else {
            echo json_encode($content);
        }
    }

    public function output_error($exception) {
        $code = $exception->getCode();
        $view = "errors/$code";

        if (View::exists($view)) {
            header('Content-Type: text/html; charset=UTF-8');
            echo View::load($view, ["exception" => $exception]);
            return;
        }

        if (error_reporting() & E_ERROR) {
            header('Content-Type: text/html; charset=UTF-8');
            echo View::load("errors/default", ["exception" => $exception]);
            return;
        }

        $path = $_SERVER["REQUEST_URI"];
        $path = str_replace("?" . $_SERVER["QUERY_STRING"], "", $path);
        $path = trim($path, "/");
        if ($path) {
            Util::redirect("/", true);
        }
        
    }

}