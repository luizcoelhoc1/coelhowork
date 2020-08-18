<?php

function urlExists($url) {
    $file_headers = @get_headers($url);
    if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found')
        return false;
    return true;
}

function getCurrentMsTime() {
    return round(microtime(true) * 1000);
}

function boolToStr($value) {
    return $value ? 'true' : 'false';
}

function getDir($dir) {
    $dirs = scandir("$dir");
    unset($dirs[0]);
    unset($dirs[1]);
    sort($dirs);
    return $dirs;
}

function createMenuPageControl($numberPagePreSelected, $numberPagePosSelected, $totalNumberPage, $numberSelected, $everSameItem = false, array $layouts = null) {
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
 *  images functions 
 *  */

function createimagefromarg($path, $extension) {
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

function createimagefromextension($path) {
    $explode = explode(".", $path);
    return createimagefromarg($path, $explode[count($explode) - 1]);
}

function onlyFirstsUpCase($str, $delimiter = " ") {
    $strs = explode($delimiter, $str);
    $result = array();
    foreach ($strs as $str) {
        $result[] = ucfirst(strtolower($str));
    }
    return implode($delimiter, $result);
}

/*
 *  CPF functions 
 *  */

function numberToCpf($number) {
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

function CpfToNumber($cpf) {
    $cpf = substr(str_replace(".", "", str_replace("-", "", $cpf)), 0, 9);
    $cpf = str_split($cpf);
    $result = "";
    for ($i = 0; $i < 9; $i++) {
        $result .= $cpf[$i];
    }
    return intval($result);
}

function isBissextileYear($year) {
    return (($year % 400 == 0) || (($year % 4 == 0) && ($year % 100 != 0)));
}

function dateFormat($newFormat, $pastFormat, $date) {
    "j";
    "l";
    "z";
    "W";
    "F";
    "n";
    "o";
    "B";
    "g";
    "G";
    "u";
    "e";
    "I";
    "O";
    "P";
    "T";
    "Z";
    "r";
    "U";
    $dictionary1 = array("d", "D", "N", "S", "w", "m", "M", "t", "L", "Y", "y", "a", "A", "h", "H", "i", "s", "c");
    $pastFormat = str_split($pastFormat);
    foreach ($pastFormat as $info) {
        if (is_numeric($info))
            return false;
        if (!in_array($info, $dictionary1)) {
            $date = substr($date, 1);
            continue;
        }

        if ($info == "d") {

            $day = substr($date, 0, 2);
            $date = substr($date, 2);
        }
        if ($info == "S") {
            $date = substr($date, 2);
        }
        if ($info == "m") {
            $month = substr($date, 0, 2);
            $date = substr($date, 2);
        }
        if ($info == "M") {
            $month = substr($date, 0, 3);
            if ($month = "Jan")
                $month = 1;
            if ($month = "Feb")
                $month = 2;
            if ($month = "Mar")
                $month = 3;
            if ($month = "Apr")
                $month = 4;
            if ($month = "May")
                $month = 5;
            if ($month = "Jun")
                $month = 6;
            if ($month = "Jul")
                $month = 7;
            if ($month = "Aug")
                $month = 8;
            if ($month = "Sep")
                $month = 9;
            if ($month = "Oct")
                $month = 10;
            if ($month = "Nov")
                $month = 11;
            if ($month = "Dec")
                $month = 12;
            $date = substr($date, 3);
        }
        if ($info == "t") {
            $date = substr($date, 2);
        }
        if ($info == "L") {
            $date = substr($date, 1);
        }
        if ($info == "Y") {
            $year = substr($date, 0, 4);
            $date = substr($date, 4);
        }
        if ($info == "a" or $info == "A") {
            $ampm = strtoupper(substr($date, 0, 4));
            $date = substr($date, 2);
        }
        if ($info == "h") {
            $hrError = substr($date, 0, 2);
            $date = substr($date, 2);
        }
        if ($info == "H") {
            $hr = substr($date, 0, 2);
            $date = substr($date, 2);
        }
        if ($info == "i") {
            $minute = substr($date, 0, 2);
            $date = substr($date, 2);
        }
        if ($info == "s") {
            $second = substr($date, 0, 2);
            $date = substr($date, 2);
        }
        if ($info == "c") {
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 2);
            $day = substr($date, 8, 2);
            $hr = substr($date, 11, 2);
            $minute = substr($date, 14, 2);
            $second = substr($date, 17, 2);
        }
        /* if ($info == "Y") {}
          if ($info == "w") {}
          if ($info == "D") {}
          if ($info == "N") {} */
    }

    if (isset($hrError) and isset($ampm)) {
        if ($ampm == "AM") {
            if ($hrError == 12) {
                $hr = 0;
            } else {
                $hr = $hrError;
            }
        } else {
            if ($hrError == 12) {
                $hr = $hrError;
            } else {
                $hr = $hrError + 12;
            }
        }
    }


    $result = "";
    $newFormat = str_split($newFormat);
    foreach ($newFormat as $info) {
        if (!in_array($info, $dictionary1)) {
            $result .= $info;
        }
        if ($info == "d") {
            if (isset($day)) {
                $result .= strlen($day) == 2 ? $day : "0" . $day;
            } else {
                return false;
            }
        }
        if ($info == "S") {
            $result .= $second;
        }
        if ($info == "m") {
            if (isset($month)) {
                $result .= strlen($month) == 2 ? $month : "0" . $month;
            } else {
                return false;
            }
        }
        if ($info == "M") {
            if (isset($month)) {
                switch ((int) $month) {
                    case 1:
                        $result .= "Jan";
                        break;
                    case 2:
                        $result .= "Feb";
                        break;
                    case 3:
                        $result .= "Mar";
                        break;
                    case 4:
                        $result .= "Apr";
                        break;
                    case 5:
                        $result .= "May";
                        break;
                    case 6:
                        $result .= "Jun";
                        break;
                    case 7:
                        $result .= "Jul";
                        break;
                    case 8:
                        $result .= "Aug";
                        break;
                    case 9:
                        $result .= "Sep";
                        break;
                    case 10:
                        $result .= "Oct";
                        break;
                    case 11:
                        $result .= "Nov";
                        break;
                    case 12:
                        $result .= "Dec";
                        break;
                    default:
                        break;
                }
            } else {
                return false;
            }
            continue;
        }

        if ($info == "t") {
            if (isset($month)) {
                if ($month <= 7) {
                    if ($month % 2 == 0) {
                        if ($month != 2) {
                            $result .= 30;
                        } else {
                            if (isset($year)) {
                                if (isBissextileYear($year)) {
                                    $result .= 29;
                                } else {
                                    $result .= 28;
                                }
                                //calc ano bi
                            } else {
                                return false;
                            }
                        }
                    } else {
                        $result .= 31;
                    }
                } else {
                    if ($month % 2 == 0) {
                        $result .= 31;
                    } else {
                        $result .= 30;
                    }
                }
            } else {
                return false;
            }
            continue;
        }



        if ($info == "L") {
            if (isset($year)) {
                $result .= isBissextileYear($year) ? "1" : "0";
            } else {
                return false;
            }
            continue;
        }

        if ($info == "Y") {
            if (isset($year)) {
                $result .= $year;
            } else {
                return false;
            }
            continue;
        }
        if ($info == "y") {
            if (isset($year)) {
                $result .= substr($year, -2);
            } else {
                return false;
            }
            continue;
        }



        if ($info == "a") {
            if (isset($ampm)) {
                $result .= strtolower($ampm);
            } else {
                return false;
            }
            continue;
        }
        if ($info == "A") {
            if (isset($ampm)) {
                $result .= strtoupper($ampm);
            } else {
                return false;
            }
            continue;
        }

        if ($info == "h") {
            if (isset($hr)) {
                if ($hr != 12) {
                    if ($hr > 12) {
                        $result .= $hr - 12;
                    } else {
                        $result .= $hr;
                    }
                } else {
                    $result .= 12;
                }
            } else if (isset($hrError)) {
                $result .= $hrError;
            } else {
                return false;
            }
            continue;
        }
        if ($info == "H") {
            if (isset($hr)) {
                $result .= $hr;
            } else {
                return false;
            }
            continue;
        }

        if ($info == "i") {
            if (isset($minute)) {
                $result .= $minute;
            } else {
                return false;
            }
            continue;
        }
        if ($info == "s") {
            if (isset($second)) {
                $result .= $second;
            } else {
                return false;
            }
            continue;
        }
    }
    return $result;
}

function getClientIp() {
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

if (!function_exists("locale_accept_from_http")) {

    function locale_accept_from_http($str) {
        
    }

}

if (!function_exists("array_column")) {
    function array_column($array, $column, $keepKeys = false) {
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
}

if (!function_exists("session_status")) {
    define('PHP_SESSION_DISABLED', 0);
    define('PHP_SESSION_NONE', 1);
    define('PHP_SESSION_ACTIVE', 2);

    function session_status() {
        if (session_id() == '') {
            return 1;
        } else {
            return 2;
        }
    }

}

function strposn($haystack, $needle, $num = 1, $offset = 0) {
    for ($i = 0; $i < $num; $i++) {
        $x = strpos($haystack, $needle, $offset);
        if ($x === false) {
            return false;
        }
        $offset = $x + strlen($needle);
    }
    return $x;
}

function getUrlPublicHtml() {
    return "http://$_SERVER[SERVER_NAME]/";
}
