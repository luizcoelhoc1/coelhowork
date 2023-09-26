<?php

//load cores
require_once "core/aux_functions.php";
require_once_all_on_dir("core/");
require_once_all_on_dir("core/Controller/");
require_once_all_on_dir("core/database/");

//load config
require_once "config/config.php";
$configPath = "config/" . getenv('ENVELOPMENT') . "/";
require_once_all_on_dir($configPath);
const __TEMPLATEDEFAULT = __DIR__ . "/template/default_template.php";

//route
require_once "routes.php";
$path = $_SERVER["REQUEST_URI"];
$path = str_replace("?" . $_SERVER["QUERY_STRING"], "", $path);
$path = trim($path, "/");
foreach ($routes as $route => $controller) {
    preg_match($route, $path, $matches);
    if (!empty($matches)) {
        require_once $controller;
        $class = explode("/", $controller);
        $class = end($class);
        $class = str_replace(".php", "", $class);
        break;
    }
}

if (class_exists($class ?? "")) {
    $pagina = new $class();
} else {
    $pagina = new Controller();
}

try {
    if (!$pagina->controller_exists()) {
        throw new Exception("Not found", 404);
    }
    if ($pagina->open_transaction) {
        $connection = Transaction::open();
        if ($pagina->throw_error_on_connection_fail && !$connection) {
            throw new Exception("Error on try connect in database", 500);
        }
    }
    
    ob_start();
    $pagina->loading();
    $pagina->checks();
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    if (method_exists($pagina, $method)) {
        $params = explode("/", $path);
        $pagina->$method(...$params);
    }
    $var = ob_get_contents();
    ob_end_clean();
    $pagina->output($var);
    if (Transaction::get()) {
        $pagina->rollback_on_finish ? Transaction::rollback() : Transaction::close();
    }
} catch (Exception $e) {
    if (Transaction::get()) {
        $pagina->rollback_on_exception ? Transaction::rollback() : Transaction::close();
    }
    $pagina->output_error($e);
}

?>