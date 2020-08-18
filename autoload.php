<?php

spl_autoload_register(function ($classe) {
    if ($classe == "PHPMailer") {
        require_once 'plugin/PHPMailer/PHPMailerAutoload.php';
        return;
    }
    if ($classe == "Facebook\Facebook") {
        require_once 'plugins/FacebookLogin/autoload.php';
    }

    $getDirTmpFunction = function($path, $classe, $getDirTmpFunction) {
        $dirs = getDir($path);
        foreach ($dirs as $dir) {
            if (is_dir("$path/$dir")) {
                $getDirTmpFunction("$path/$dir", $classe, $getDirTmpFunction);
            } else {
                if ($classe . __EXTENSIONFILESCLASS == $dir) {
                    include_once "$path/$dir";
                }
            }
        }
    };

    $getDirTmpFunction(__ROOT . "/class/model", $classe, $getDirTmpFunction);
});
