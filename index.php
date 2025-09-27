<?php
function dd($params) {
    echo "<pre>";
    print_r($params);
    echo "</pre>";
    die();
}

//requireds
require_once "autoload.php";

$app = new \Coelho\Application();
$app->run();

?>  