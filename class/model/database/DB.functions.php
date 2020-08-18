<?php

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
            $stmt->bindParam( ++$i, $value);
        }
    }

    //verifica erro
    if (!$stmt->execute()) {
        $erro = $stmt->errorInfo();
        throw new Exception($erro[2], 10 . $erro[1]);
    }
    return $stmt;
}

function fetchObjectToArrayStdClass($stmt, $key = null) {
    $arr = array();
    if ($key == null) {
        while ($row = $stmt->fetchObject())
            $arr[] = $row;
    } else {
        while ($row = $stmt->fetchObject()) {
            if (isset($row->$key))
                $arr[$row->$key] = $row;
            else
                throw new Exception("return $kindOfReturn does not exist", 500);
        }
    }
    return $arr;
}

function select(array $options, $kindOfReturn = "arrayStdClass", $type = "sql") {
    switch ($type) {
        case "sql":
            if (!isset($options["sql"]))
                throw new Exception("The sql option is not set");
            if (!isset($options["values"]))
                $options["values"] = array();
            $stmt = selectBySQL($options["sql"], $options["values"]);
            break;
        default:
            throw new Exception("type $type does not exist");
    }

    switch ($kindOfReturn) {
        case "arrayStdClass":
            if (isset($options["keyArray"])) {
                return fetchObjectToArrayStdClass($stmt, $options["keyArray"]);
            } else {
                return fetchObjectToArrayStdClass($stmt);
            }
        case "PDOStatement":
            return $stmt;
        case "stdClass":
            return $stmt->fetchObject();
        default:
            throw new Exception("return $kindOfReturn does not exist");
    }
}


function query($sql, array $values = array()) {
    $conexao = Transacao::get();
    for ($i = 0; $i < count($values); $i++) {
        if ($values[$i] === null) {
            $index = strposn($sql, "?", $i + 1);
            $sql = substr($sql, 0, $index) . "null" . substr($sql, $index + 1);
        }
    }

    $stmt = $conexao->prepare($sql);
    if (!empty($values)) {
        $i = 1;
        foreach ($values as $key => & $value) {
            if ($value !== null) {
                $stmt->bindParam($i++, $value);
            }
        }
    }
    if (!$stmt->execute())
        throw new Exception("Error in query($sql)", 2);
    $result = new stdClass();
    $result->lastInsertId = $conexao->lastInsertId();
    $result->stmt = $stmt;
    return $result;
}
