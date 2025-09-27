<?php

$autoloadMap = [
    'App\\' => __DIR__ . "/",
    'Coelho\\' => __DIR__ . '/core/',
];
spl_autoload_register(function ($class) use ($autoloadMap) {
    foreach ($autoloadMap as $prefix => $baseDir) {
        if (strncmp($prefix, $class, strlen($prefix)) === 0) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

foreach (glob(__DIR__ . '/routes/*.php') as $arquivo) {
    require_once $arquivo;
}
