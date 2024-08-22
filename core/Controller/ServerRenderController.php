<?php

abstract class ServerRenderController extends Controller {
    public $template = null; 
    function __construct(
        $openTransaction = true,
        $rollbackOnFinish = false,
        $rollbackOnException = false,
        $throwErrorOnConnectionFail = true
    ) {
        parent::__construct(
            $openTransaction,
            $rollbackOnFinish,
            $rollbackOnException,
            $throwErrorOnConnectionFail
        );
    }

    public function output($string = null) {
        header('Content-Type: text/html; charset=UTF-8');
        if (!empty($this->template)) {
            loadView($this->template, [
                "content" => $string
            ]);
        } else {
            echo $string;
        }

    }

    public function output_error($exception) {
        $code = $exception->getCode();
        $view = "errors/$code";

        if (viewExists($view)) {
            header('Content-Type: text/html; charset=UTF-8');
            loadView($view, ["exception" => $exception]);
            return;
        }

        if (error_reporting() & E_ERROR) { 
            header('Content-Type: text/html; charset=UTF-8');
            loadView("errors/default", ["exception" => $exception]);
            return;
        }

        $path = $_SERVER["REQUEST_URI"];
        $path = str_replace("?" . $_SERVER["QUERY_STRING"], "", $path);
        $path = trim($path, "/");
        if ($path) {
            redirect("/", true);
        }
    }

    public function loads() {

    }

    public function guards() {

    }

}