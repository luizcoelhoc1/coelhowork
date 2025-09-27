<?php

namespace Coelho\helpers;

use \Coelho\Template;


class Util {

    public static function urlExists($url) {
        $file_headers = @get_headers($url);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found')
            return false;
        return true;
    }

    public static function getCurrentMsTime() {
        return round(microtime(true) * 1000);
    }

    public static function boolToStr($value) {
        return $value ? 'true' : 'false';
    }

    public static function getDir($dir) {
        $dirs = scandir("$dir");
        unset($dirs[0]);
        unset($dirs[1]);
        sort($dirs);
        return $dirs;
    }

    public static function createMenuPageControl($numberPagePreSelected, $numberPagePosSelected, $totalNumberPage, $numberSelected, $everSameItem = false, array $layouts = null) {
        //Template $layout,
        // Template $item,
        //  Template $itemSelected
        //  Template $itemSelected
        if (!isset($layouts["itemSelected"]))
            $layouts["itemSelected"] = new Template("<a style='color: red;'>[@number]</a><br>");

        if (!isset($layouts["item"]))
            $layouts["item"] = new Template("<a>[@number]</a><br>");


        if ($everSameItem) {
            $numberPagePosSelected += 1 + $numberPagePreSelected - $numberSelected;
            if ((($tmp = ($numberPagePosSelected - $totalNumberPage - $numberSelected)) > 0) and ( $numberSelected + $numberPagePosSelected > $totalNumberPage)) {
                $numberPagePreSelected += $tmp;
            }
        }

        //start
        $i = ($numberSelected - $numberPagePreSelected < 1) ? 1 : $numberSelected - $numberPagePreSelected;
        //end
        $limit = ($totalNumberPage < ($numberPagePosSelected + $numberSelected)) ? $totalNumberPage : ($numberPagePosSelected + $numberSelected);


        $itens = "";
        for (; $i <= $limit; $i++) {
            $item = ($i == $numberSelected) ?
                    clone $layouts["itemSelected"] :
                    clone $layouts["item"];
            $item->set("number", $i);
            $itens .= $item->output();
        }

        return $itens;
    }

    /*
    *  images public static functions 
    *  */

    public static function createimagefromarg($path, $extension) {
        if ($extension == "png") {
            return imagecreatefrompng($path);
        } else if ($extension == "jpg" or $extension == "jpeg") {
            return imagecreatefromjpeg($path);
        } else if ($extension == "bmp") {
            return imagecreatefrombmp($path);
        } else if ($extension == "webp") {
            return imagecreatefromwebp($path);
        }
        return false;
    }

    public static function createimagefromextension($path) {
        $explode = explode(".", $path);
        return Util::createimagefromarg($path, $explode[count($explode) - 1]);
    }

    public static function onlyFirstsUpCase($str, $delimiter = " ") {
        $strs = explode($delimiter, $str);
        $result = array();
        foreach ($strs as $str) {
            $result[] = ucfirst(strtolower($str));
        }
        return implode($delimiter, $result);
    }

    /*
    *  CPF public static functions 
    *  */

    public static function numberToCpf($number) {
        $number = str_repeat("0", 9 - strlen($number)) . $number;
        $chats = str_split($number);
        $decimo = 0;
        $decimoPrimeiro = 0;
        for ($i = 1; $i < 10; $i++) {
            $decimo += $i * $chats[$i - 1];
            $decimoPrimeiro += ($i - 1) * $chats[$i - 1];
        }
        $decimo = substr($decimo % 11, -1);
        $decimoPrimeiro += 9 * $decimo;
        $decimoPrimeiro = substr($decimoPrimeiro % 11, -1);
        $number = substr($number, 0, 3) . "." . substr($number, 3, 3) . "." . substr($number, 6, 3) . "-" . $decimo . $decimoPrimeiro;
        return $number;
    }

    public static function CpfToNumber($cpf) {
        $cpf = substr(str_replace(".", "", str_replace("-", "", $cpf)), 0, 9);
        $cpf = str_split($cpf);
        $result = "";
        for ($i = 0; $i < 9; $i++) {
            $result .= $cpf[$i];
        }
        return intval($result);
    }

    public static function getClientIp() {
        $ipaddress = '';
        if ($ipaddress = getenv('HTTP_CLIENT_IP'))
            return $ipaddress;
        else if ($ipaddress = getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');
        else if ($ipaddress = getenv('HTTP_X_FORWARDED'))
            return getenv('HTTP_X_FORWARDED');
        else if ($ipaddress = getenv('HTTP_FORWARDED_FOR'))
            return getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            return getenv('HTTP_FORWARDED');
        else if ($ipaddress = getenv('REMOTE_ADDR'))
            return getenv('REMOTE_ADDR');

        return false;
    }

    public static function locale_accept_from_http($str) {
        
    }

    public static function array_column($array, $column, $keepKeys = false) {
        $result = array();
        $i = 0;
        foreach ($array as $key => $value) {
            if ($keepKeys === true) {
                $chave = $key;
            } else if ($keepKeys === false) {
                $chave = $i++;
            } else if (isset($value->$keepKeys)) {
                $chave = $value->$keepKeys;
            } else {
                $chave = $i++;
            }
            $result[$chave] = $value->$column;
        }

        return $result;
    }

    public static function strposn($haystack, $needle, $num = 1, $offset = 0) {
        for ($i = 0; $i < $num; $i++) {
            $x = strpos($haystack, $needle, $offset);
            if ($x === false) {
                return false;
            }
            $offset = $x + strlen($needle);
        }
        return $x;
    }

    public static function getUrlPublicHtml() {
        return "http://$_SERVER[SERVER_NAME]/";
    }

    public static function dd(...$params) {
        echo "<pre>";
        print_r($params);
        echo "</pre>";
        die();
    }

    public static function redirect($url, $die=true) {
        header('Location: ' . $url);
        if ($die) {
            die();
        }
    }

    public static function get_dicionary_http_code() {
        return [
            100 => "Continue",
            101 => "Switching protocols",
            102 => "Processing",
            103 => "Early Hints",
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            203 => "	Non-Authoritative Information",
            204 => "No Content",
            205 => "Reset Content",
            206 => "Partial Content",
            207 => "Multi-Status",
            208 => "Already Reported",
            226 => "IM Used",
            300 => "Multiple Choices",
            301 => "Moved Permanently",
            302 => 'Found (Previously "Moved Temporarily")',
            303 => "See Other",
            304 => "Not Modified",
            305 => "Use Proxy",
            306 => "Switch Proxy",
            307 => "Temporary Redirect",
            308 => "Permanent Redirect  ",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Timeout",
            409 => "Conflict",
            410 => "Gone",
            411 => "Length Required",
            412 => "Precondition Failed",
            413 => "Payload Too Large",
            414 => "URI Too Long",
            415 => "Unsupported Media Type",
            416 => "Range Not Satisfiable",
            417 => "Expectation Failed",
            418 => "I'm a Teapot",
            421 => "Misdirected Request",
            422 => "Unprocessable Entity",
            423 => "Locked",
            424 => "Failed Dependency",
            425 => "Too Early",
            426 => "Upgrade Required",
            428 => "Precondition Required",
            429 => "Too Many Requests",
            431 => "Request Header Fields Too Large",
            451 => "Unavailable For Legal Reasons",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout",
            505 => "HTTP Version Not Supported",
            506 => "Variant Also Negotiates",
            507 => "Insufficient Storage",
            508 => "Loop Detected",
            510 => "Not Extended",
            511 => "Network Authentication Require",
        ];
    }

    public static function is_http_code($code) {
        return !empty(Util::get_dicionary_http_code()[$code]);
    }

    /**
     * Retorna o primeiro caractere do array $charSet que não aparece na string $input.
     *
     * @param string $input     A string onde os caracteres já usados estão presentes.
     * @param array|null $charSet Array de caracteres permitidos. Se null, usa ASCII imprimíveis (32–126).
     * @return string|null      O primeiro caractere não usado, ou null se todos estiverem presentes.
     */
    public static function find_unused_char(string $input, array $charSet = null): ?string {
        if ($charSet === null) {
            // ASCII imprimíveis do espaço (32) até o til (126)
            $charSet = array_map('chr', range(32, 126));
        }

        foreach ($charSet as $char) {
            if (strpos($input, $char) === false) {
                return $char;
            }
        }

        return null; // Todos os caracteres estão presentes
    }

}