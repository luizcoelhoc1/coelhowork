<?php

namespace Coelho;

/**
 * @return mixed
 */

class View
{
    static function handlingPath($resource, $posfix = "php")
    {
        if (is_array($posfix)) {
            foreach ($posfix as $extension) {
                $prepath = self::handlingPath($resource, $extension);
                if (!empty($prepath)) {
                    return $prepath;
                }
            }
            return false;
        }

        $prepath = "view/" . trim(str_replace(".$posfix", "", $resource), "/") . ".$posfix";
        if (file_exists($prepath)) {
            return $prepath;
        } else {
            return false;
        }
    }

    static function load($path, array $args = [], $returnString = true)
    {
        $path = self::handlingPath($path, ["php", "html", "tpl", "json"]);
 
        if (empty($path)) {
            throw new \Exception("View not found", 400);
        }
        
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


    static function exists($path) {
        if (file_exists(self::handlingPath($path, "php"))) {
            return true;
        }
        if (file_exists(self::handlingPath($path, "html"))) {
            return true;
        }
        if (file_exists(self::handlingPath($path, "tpl"))) {
            return true;
        }
        if (file_exists(self::handlingPath($path, "json"))) {
            return true;
        }
        return false;
    }
}
