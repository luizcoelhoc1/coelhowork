<?php

$host = "localhost";
$porta = "3306";
$db = "id564090_db";
$user = "id564090_db";
$pass = "94671835liz";

$con = new PDO("mysql:host={$host};port={$porta};dbname={$db}", $user, $pass);

$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$con->Query("SET NAMES utf8");

function doLimit(array $rows, $startRow = 0, $maxRow = false) {
    $results = array();
    foreach ($rows as $key => $row) {
        if (($key + 1) < $startRow) {
            continue;
        }
        if ((1 + $key) > $startRow + $maxRow) {
            break;
        }
        $results[] = $row;
    }
    return $results;
}

//select
function selectBySQL($sql, $values = array()) {
    $conection = Transacao::get();
    $stmt = $conection->prepare($sql);

    //bind values
    if (!empty($values)) {
        $i = 0;
        foreach ($values as $key => & $value) {
            $stmt->bindParam(++$i, $value);
        }
    }

    //verifica erro
    if (!$stmt->execute()) {
        $erro = $stmt->errorInfo();
        throw new Exception($erro[2], 10 . $erro[1]);
    }
    return $stmt;
}

function fetchObjectToArrayStdClass($stmt, $key = "index", $option = null) {
    $arr = array();
    if ($key == "index") {
        while ($row = $stmt->fetchObject())
            $arr[] = $row;
    } else if ($key == "primary") {
        if ($option == null)
            throw new Exception("don't set primary key for fetchObject", 1);
        while ($row = $stmt->fetchObject())
            $arr[$row->id] = $row;
    } else {
        throw new Exception("don't exist '$key' for fetchObject", 1);
    }
    return $arr;
}

function select(array $options, $type = "sql", $kindOfReturn = "arrayStdClass") {

    switch ($type) {
        case "sql":
            if (!isset($options["sql"]))
                throw new Exception("The sql option is not set", 1);
            if (!isset($options["values"]))
                $options["values"] = array();
            $stmt = selectBySQL($options["sql"], $options["values"]);
            break;
        default:
            throw new Exception("type $type does not exist", 1);
    }
    switch ($kindOfReturn) {
        case "arrayStdClass":
            if (isset($options["keyArray"])) {
                if ($options["keyArray"] == "primary") {
                    if (!isset($options["primaryKeyColumnName"])) {
                        throw new Exception("Don't primaryKeyColumnName option set for feachObject ", 1);
                    }
                    return fetchObjectToArrayStdClass($stmt, "primary", $options["primaryKeyColumnName"]);
                } else {
                    return fetchObjectToArrayStdClass($stmt);
                }
            } else {
                return fetchObjectToArrayStdClass($stmt);
            }
        case "PDOStatement":
            return $stmt;
        default:
            throw new Exception("return $kindOfReturn does not exist", 1);
    }
}

$tabelas = select(["sql" => "SHOW TABLE STATUS"]);
foreach ($tabelas as $tabela) {
    echo "<pre>";
    print_r($tabela);
    echo "</pre>";
}


/*
if (isset($_POST["tabelas"])) {
    $tabela = $_POST["tabelas"];
    $sql = "-- Sistema de backup feito por Fabyo Guimaraes 25/06/2006\r\n";
    $sql .= "-- Servidor: " . SERVIDOR . "\r\n";
    $sql .= "-- Banco de dados: " . $_POST["db"] . "\r\n";
    $sql .= "-- Data backup: " . date("d/m/Y H:i:s") . "\r\n";
    $sql .= "-- Versao MySQL: " . mysql_get_server_info() . "\r\n";
    $sql .= "-- Versao PHP: " . phpversion() . "\r\n\r\n";

    mysql_select_db($_POST["db"]);
    $re = mysql_query("SHOW TABLE STATUS");
    while ($l = mysql_fetch_assoc($re)) {
        $tbl_stat[$l["Name"]] = $l["Auto_increment"];
    }
    for ($i = 0; $i < count($tabela); $i++) {
        $re2 = mysql_query("SHOW CREATE TABLE $tabela[$i]");
        $sql .= "-- Estrutura da tabela $tabela[$i]\r\n\r\n";
        $l2 = mysql_fetch_array($re2);
        if ($tbl_stat[$tabela[$i]] != "") {
            $sql .= str_replace("  ", "\t", str_replace("`", "", $l2[1])) . " AUTO_INCREMENT=" . $tbl_stat[$tabela[$i]] . ";\r\n\r\n";
        } else {
            $sql .= str_replace("  ", "\t", str_replace("`", "", $l2[1])) . ";\r\n\r\n";
        }
        $re3 = mysql_query("SHOW COLUMNS FROM $tabela[$i]");
        $campos = "";
        while ($row = mysql_fetch_array($re3)) {
            $campos[] = $row[0];
        }
        $re4 = mysql_query("SELECT * FROM $tabela[$i]");
        if (mysql_num_rows($re4)) {
            while ($dt = mysql_fetch_row($re4)) {
                $valores = "";
                for ($j = 0; $j < sizeof($dt); $j++) {
                    $valores[] .= "'" . $dt[$j] . "'";
                }
                $campo = implode(", ", $campos);
                $valor = implode(", ", $valores);
                $sql .= "INSERT INTO $tabela[$i] ($campo) VALUES ($valor);\r\n";
            }
        }
        $sql .= "\r\n";
    }
    echo "<pre>$sql</pre>";
    if (isset($_POST["sql"])) {
        $fp = fopen($_POST["db"] . ".sql", "w+");
        if (!fwrite($fp, $sql)) {
            echo "Erro na criação do arquivo, verifique a permissao de escrita";
            exit;
        }
        fclose($fp);
    }
    exit;
}
 * 
 */