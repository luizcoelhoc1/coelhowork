<?php

require_once 'config.php';
include_once 'functions.php';
include_once 'autoload.php';
include_once 'class/model/database/DB.functions.php';
include_once 'plugins/simple_html_dom/simple_html_dom.php';
global $console;
$console = array();


try {
    Transacao::open();

    /*
     * Get user required class 
     */
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

    /*
     * Prepare a page settings
     */
    require_once $path;
    $pagina = new $class;
    $pagina->data = new stdClass();
    checkRequests($pagina);
    if (!isset($pagina->layout))
        $pagina->layout = __LAYOUTDEFAULT;


    /*
     * get page or final result
     */
    $method = __METHODNAME;
    $pagina->$method();
    //set parameters 
    $layout = new Template($pagina->layout);
    foreach ($pagina->data as $key => $result) {
        $layout->set($key, $result);
    }

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

    //set header and body
    header('Content-Type: text/html; charset=UTF-8');
    echo $fullPage->output();

    Transacao::close();

    //echo console if required
    if (!empty($console) && ini_get('display_errors')) {
        echo "<br />";
        echo "<br />";
        echo "<br />";
        echo "<pre>";
        print_r($console);
        echo "</pre>";
    }
} catch (Exception $e) {
    Transacao::rollback();
    if (($code = $e->getCode()) !== 0) {
        if (ini_get('display_errors')) {
            displayErrorsDetails($e);
        } else {
            http_response_code($code);
        }
    }
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

function displayErrorsDetails($e) {
    echo "<pre style = 'background-color: red; color: white; padding: 1%;'>";
    echo $e->getMessage();
    echo "<br />";
    echo "Code: " . $e->getCode();
    echo "<br />";
    $paths = $e->getTrace();
    foreach ($paths as $path) {
        echo $path["file"] . ":" . $path["line"] . "<br />";
    }
    echo "</pre>";
}

?>