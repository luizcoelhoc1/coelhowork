<?php
/**
 * @return mixed
 */
function handlingPath($resource, $posfix = "php") {
    if (is_array($posfix)) {
        return array_map(function ($pf) use ($resource) {
            return "view/" . trim(str_replace(".$pf", "", $resource), "/") . ".$pf";
        }, $posfix);
    } else {
        return "view/" . trim(str_replace(".$posfix", "", $resource), "/") . ".$posfix";

    }
}

function loadView($path, array $args=[], $returnString = false) {
    $path = handlingPath($path);
    foreach ($args as $key => $value) {
        $$key = $value;
    }
    ob_start();
    include($path);
    $var = ob_get_contents();
    ob_end_clean();
    if ($returnString) {
        return $var;
    } 
    echo $var;
}


function viewExists($path) {
    $paths = handlingPath($path, ["php", "html", "tpl"]);
    foreach ($paths as $path) {
        if (file_exists($path)) {
            return true;
        }
    }
    return false;
}