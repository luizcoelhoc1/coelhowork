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
}