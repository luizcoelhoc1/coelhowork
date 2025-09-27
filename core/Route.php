<?php
namespace Coelho;

final class Route {

    private static $routes = [];
    private function __construct() { }

    public static function addRoute($class, $paths, $methods = "GET", $middlewares = []) {
        if (is_array($paths)) {
            foreach ($paths as $path) {
                self::addRoute($class, $path, $methods, $middlewares);
            }
            return;
        }

        if (is_array($methods)) {
            foreach ($methods as $method) {
                self::addRoute($class, $paths, $method, $middlewares);
            }
            return;
        }

        $routeId = md5(json_encode([$class, $paths, $methods, $middlewares]));
        self::$routes[$routeId] = (object)[
            'class' => $class,
            'method' => $methods,
            'middlewares' => $middlewares,
            'paths' => (array)$paths
        ];
    }

    public static function getRoutes() {
        return self::$routes;
    }

}