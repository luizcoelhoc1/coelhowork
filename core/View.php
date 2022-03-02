<?php

function loadView($path, array $args=[], $returnString = false) {
    $path = "view/" . trim(str_replace(".php", "", $path), "/") . ".php";
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
