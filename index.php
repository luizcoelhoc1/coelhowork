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

//if not find by route find by path
//todo...
/**
 * 
    $path = $_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"];
    $path = str_replace("?" . $_SERVER["QUERY_STRING"], "", $path);
    $path = str_replace(__ROOT, "", $path);

    if ($path != "/") {
        $find = true;
        $path = __PATHFILESCLASS . $path;

        if (substr($path, -1) == "/") { //path in dir
            $class = __DEFAULTCLASSDIR;
            if (!is_file($path = $path . __DEFAULTCLASSDIR . __EXTENSIONFILESCLASS)) {
                $find = false;
            }
        } else {  //path in file                
            $path = explode("/", $path);
            $class = $path[count($path) - 1] = ucfirst($path[count($path) - 1]);
            if (!is_file($path = implode("/", $path) . __EXTENSIONFILESCLASS)) {
                $find = false;
            }
        }

        if ($find == false) {
            $path = $_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"];
            $path = str_replace("?" . $_SERVER["QUERY_STRING"], "", $path);
            $path = str_replace(__ROOT, "", $path);
            $path = __PATHFILESCLASS . $path;
            if (substr($path, -1) == "/") {
                $path = substr($path, 0, -1);
                $path = explode("/", $path);
                $class = $path[count($path) - 1] = ucfirst($path[count($path) - 1]);
                if (!is_file($path = implode("/", $path) . __EXTENSIONFILESCLASS)) {
                    throw new Exception("This page doesn't exists <br /> $path", 404);
                }
            } else {
                $path .= "/";
                $class = __DEFAULTCLASSDIR;
                if (!is_file($path = $path . __DEFAULTCLASSDIR . __EXTENSIONFILESCLASS)) {
                    throw new Exception("This page doesn't exists <br /> $path", 404);
                }
            }
        }
    } else {
        $path = (substr(__FIRSTPAGE, strlen(__EXTENSIONFILESCLASS) * -1) == __EXTENSIONFILESCLASS) ?
                __PATHFILESCLASS . __FIRSTPAGE :
                __PATHFILESCLASS . __FIRSTPAGE . __EXTENSIONFILESCLASS;
        $class = explode("/", $path);
        $class = str_replace(__EXTENSIONFILESCLASS, "", $class[count($class) - 1]);
    }
 */
$pagina = new $class();
try {
    if ($pagina->open_transaction) {
        $connection = Transaction::open();
        if ($pagina->throw_error_on_connection_fail) {
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

function console($item) {
    global $console;
    $console[] = $item;
}

function checkRequests(&$pagina) {
    if (isset($pagina->required)) {
        foreach ($pagina->required as $key => $value) {
            if ($key == "session") {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                continue;
            }
            if ($key === "login") {
                if ($value) {
                    checkLogin($pagina);
                }
                continue;
            }
            if ($key === "permission") {
                if (checkLogin($pagina)) {
                    if (is_array($value)) {
                        foreach ($value as $permission) {
                            if (!checkPermission($permission)) {
                                throw new Exception("No Permission", 403);
                            }
                        }
                    } else {
                        if (!checkPermission($value)) {
                            throw new Exception("No Permission", 403);
                        }
                    }
                }
            }
            if ($key === "once") {
                if (is_array($value)) {
                    foreach ($value as $required_once) {
                        require_once $required_once;
                    }
                } else {
                    require_once $value;
                }
            }
        }
    }
}

function checkPermission($permission) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["idUser"])) {
        return false;
    }

    $context = selectBySQL("SELECT * FROM `context` where `context` = ?", array($permission))->fetchObject();
    $allowedContexts = array_merge(array($context->id), explode("/", $context->path));

    $allowed = false;
    $permissions = selectBySQL("SELECT * FROM `permission` inner join context on context.id = permission.idContext where permission.idLogin = ?", array($_SESSION["idUser"]));
    while ($permission = $permissions->fetchObject()) {
        if (in_array($permission->idContext, $allowedContexts)) {
            $allowed = true;
            return $allowed; //break more fast i think ehuehehue
        }
    }

    return $allowed;
}

function checkLogin(&$pagina) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION["idUser"])) {
        if (!isset($_POST["user"])) {
            showLoginScreen(false, $pagina);
            return false;
        } else {
            return setAndCheckLogin($pagina);
        }
    } else {
        return true;
    }
}


function setAndCheckLogin(&$pagina) {
    $user = selectBySQL("SELECT id FROM login where user = ? and password = ?", array($_POST["user"], hash(__HASH, $_POST["password"])));
    if ($user->rowCount() != 0) {
        $_SESSION["idUser"] = $user->fetchObject()->id;
        checkRequests($pagina);
        return true;
    } else {
        showLoginScreen(true, $pagina);
        return false;
    }
}

function showLoginScreen($incorrectPass, &$pagina) {
    $path = (substr(__LOGINPATHFILECLASS, strlen(__EXTENSIONFILESCLASS) * -1) == __EXTENSIONFILESCLASS) ?
            __PATHFILESCLASS . __LOGINPATHFILECLASS :
            __PATHFILESCLASS . __LOGINPATHFILECLASS . __EXTENSIONFILESCLASS;
    $class = explode("/", $path);
    $class = str_replace(__EXTENSIONFILESCLASS, "", $class[count($class) - 1]);
    require_once $path;
    $pagina = new $class;
    $pagina->incorrectPass = $incorrectPass;
    $pagina->data = new stdClass();
}
?>