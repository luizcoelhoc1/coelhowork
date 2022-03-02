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
                throw new Exception("key $key does not exist on result", 500);
        }
    }
    return $arr;
}

function select($sql, $values = [], $kindOfReturn = "arrayOfObject") {
    $stmt = query($sql, $values);
    if ($kindOfReturn == "arrayOfObject") {
        return fetchObjectToArrayStdClass($stmt);
    }
    if ($kindOfReturn == "object") {
        return $stmt->fetchObject();
    }

}



function query($sql, array $values = array()) {
    $conexao = Transaction::get();

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

    return $stmt;
}

function query_by_file($file, $values = array()) {
    return query(file_get_contents(__DIR__ . "/querys/" . $file), $values);
}