<?php

use \Coelho\Route;

Route::addRoute(
    class: \App\controller\Home::class,
    paths: "/",
    methods: "GET", 
    middlewares: [\App\middleware\guard\GuardHome::class]
);

Route::addRoute(
    class: \App\controller\api\TestApiController::class, 
    paths: ["/test", "/tttt"], 
    methods: "GET"
);

?>