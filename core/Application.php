<?php

namespace Coelho;

use \Coelho\{Middleware, Guard, Route, View, helpers\Util, database\Transaction};

class Application
{

    public $openTransaction;
    public $rollbackOnException;

    function __construct(
        $openTransaction = true,
        $rollbackOnException = false
    ) {
        $this->openTransaction = $openTransaction;
        $this->rollbackOnException = $rollbackOnException;
    }

    function addNestedKey(array &$array, string $path, array $value, string $separator = '/')
    {
        $keys = explode($separator, $path);
        if (count($keys) == 1 && $keys[0] == "") {
            foreach ($value as $key => $v) {
                $array[$key] = $v;
            }
            return;
        }
        $tmp = &$array;
        foreach ($keys as $key) {
            if (!isset($tmp[$key]) || !is_array($tmp[$key])) {
                $tmp[$key] = [];
            }
            $tmp = &$tmp[$key];
        }
        $tmp = $value;
    }

    public function output($result, $result_outputed)
    {
        if (!is_callable([$this->routeFounded->object, "output"])) {
            throw new \Exception("", 500);
        }
        $this->routeFounded->object->output($result);
        echo $result_outputed;
    }

    public function close(?\Throwable $e = null)
    {
        if (!empty($e)) {

            $routeExists = !empty($this->routeFounded->object);
            if ($routeExists && !empty($this->routeFounded->object->rollback_on_exception)) {
                Transaction::rollback();
            }
            if ($routeExists && is_callable([$this->routeFounded->object, "output_error"])) {
                $this->routeFounded->object->output_error($e);
                Transaction::close();
                return;
            }

            $code = $e->getCode();
            View::load("errors/$code");
        }
    }

    public function callSafe(callable $func, array $data)
    {
        $ref = is_array($func) ? new \ReflectionMethod($func[0], $func[1]) : new \ReflectionFunction($func);
        $allows = [];
        $missing = [];
        $variadicName = null;
        foreach ($ref->getParameters() as $param) {
            $name = $param->getName();
            if ($param->isVariadic()) {
                $variadicName = $name;
                break;
            }
            if (array_key_exists($name, $data)) {
                $allows[$name] = $data[$name];
            } else {
                if (!$param->isOptional()) {
                    $missing[] = $name;
                }
            }
        }
        if (!empty($missing)) {
            throw new \InvalidArgumentException("Parâmetros obrigatórios missing: " . implode(', ', $missing));
        }
        if ($variadicName !== null) {
            return $func(...$data);
        }
        return $func(...$allows);
    }

    public function processConfigs()
    {
        include_once("config/config.php");
        $path = "config";
        $iterator = new \RecursiveIteratorIterator(
            iterator: new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            mode: \RecursiveIteratorIterator::SELF_FIRST
        );
        $filesConfig = [];
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $path = str_replace("\\", "/", $file->getPathname());
                if (str_ends_with($path, ".env")) {
                    $filesConfig[] = $path;
                }
            }
        }
        $env = $_ENV["ENVIRONMENT"];
        if (!empty($filesConfig)) {
            foreach ($filesConfig as $config) {
                if (str_starts_with($config, "config/$env")) {
                    $configFile = parse_ini_file($config);
                    if ($configFile === false) {
                        continue;
                    }
                    $config = str_replace(["config/$env/", ".env"], "", $config);
                    $this->addNestedKey($_ENV, $config, $configFile);
                }
            }
        }
    }

    public function getRowPath()
    {
        $path = $_SERVER["REQUEST_URI"];
        $path = str_replace("?" . $_SERVER["QUERY_STRING"], "", $path);

        empty($path) || $path === "/";

        if (empty($path) || $path === "/") {
            return "/";
        } elseif (str_ends_with($path, "/")) {
            return substr($path, 0, -1);
        } else {
            return $path;
        }
    }

    public function getRoute($path)
    {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            foreach ($route->paths as $pathRoute) {
                if (str_starts_with($pathRoute, "regex:")) {
                    $type = "regex";
                } else {
                    $type = "plain";
                }
                if ($type === "regex" || $type === "plain") {
                    $pathRoute = str_replace("regex:", "", $pathRoute);
                    $pathRoute = str_replace("plain:", "", $pathRoute);
                    $delimiter = Util::find_unused_char($pathRoute, $delimiters = ["/", "#", "~"]);
                    $pathRoute = preg_quote($pathRoute, $delimiter);
                    $pathRoute = "^$pathRoute$";
                    preg_match("$delimiter$pathRoute$delimiter", $path, $matches);
                    if (!empty($matches)) {
                        if ($route->method === $_SERVER['REQUEST_METHOD']) {
                            return $route;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function run()
    {
        try {
            $this->processConfigs();
            $path = $this->getRowPath();
            $this->routeFounded = $this->getRoute($path);
            if (empty($this->routeFounded) || !class_exists($this->routeFounded->class)) {
                throw new \Exception("Not found", 404);
            }
            $this->routeFounded->object = new $this->routeFounded->class;
            if (!is_callable([$this->routeFounded->object, $this->routeFounded->method])) {
                throw new \Exception("Not found", 404);
            }
            if (strtoupper($this->routeFounded->method) == "GET") {
                $params = $_GET;
            } else {
                $params = $_POST;
            }

            foreach ($this->routeFounded->middlewares as $middlewarClass) {
                $middleware = new $middlewarClass;
                $class = get_parent_class($middleware);
                if ($class == "Coelho\Middleware") {
                    $middleware->run();
                } else if ($class == "Coelho\Guard") {
                    if (!$middleware->can()) {
                        $middleware->customFail($this->routeFounded);
                        throw new \Exception("Error permission", 403);
                    }
                }
            }

            ob_start();
            $result = $this->callSafe([$this->routeFounded->object, $this->routeFounded->method], $params);
            $result_outputed = ob_get_contents();
            ob_end_clean();
            $this->output($result, $result_outputed);
            $this->close();
        } catch (\Throwable $e) {
            $this->close($e);
        }
    }
}
