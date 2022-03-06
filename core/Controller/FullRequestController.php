<?php

abstract class FullRequestController extends Controller {
    public $template = null; 
    function __construct() {
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
            /**
        //set lang
        $langDefaultBrowser = locale_accept_from_http($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        $langPath = is_file($langPath = "lang/" . $langDefaultBrowser . ".php") ?
                $langPath :
                "lang/" . __LANGDEFAULT . ".php";
        require_once $langPath;
        $fullPage = new Template($layout->output());
        Template::$startKey = "{@";
        Template::$endKey = "}";
        foreach (__LANGVARS as $nameVarLang => $valueVarLang) {
            $fullPage->set($nameVarLang, $valueVarLang);
        }
     */
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
}