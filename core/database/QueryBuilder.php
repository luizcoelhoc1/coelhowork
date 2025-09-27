<?php

namespace Coelho\database;

class QueryBuilder {

    private $_select = [];
    private $_from = "";
    private $_join = [];
    private $_where = [];
    private $_groupBy = [];
    private $_having = [];
    private $_orderBy = [];
    private $_limit = -1;
    private $_offset = -1;
    private $_distinct = false;
    private $_values = [];
    private $connection;

    public function __construct(?\PDO $connection = null) {
        $this->connection = $connection;
    }

    public function build() {
        if (empty($this->_from)) {
            throw new \Exception("building query without from");
        }
        $distinct = $this->_distinct ? " DISTINCT " : "";
        $select = empty($this->_select) ? " * " : implode(",", $this->_select);
        $from = $this->_from;
        $join = implode("\n", $this->_join);
        $where = $this->implodeAndConcat("WHERE ", " AND ", $this->_where);
        $groupBy = $this->implodeAndConcat("GROUP BY ", ",", $this->_groupBy);
        $having = $this->implodeAndConcat("HAVING ", " AND ", $this->_having);
        $orderBy = $this->implodeAndConcat("ORDER BY ", ",", $this->_orderBy);
        if ($this->_limit !== -1) {
            $limit = "LIMIT " . $this->_limit;
            $offset = $this->_offset === -1 ? "" : "OFFSET " . $this->_offset;
        } else {
            $limit = "";
            $offset = "";
        }

        return <<<SQL
            SELECT $distinct $select
            FROM $from
            $join
            $where
            $groupBy
            $having
            $orderBy
            $limit
            $offset
        SQL;
    }

    public function select($column, $escape = true) {
        if (empty($escape)) {
            $this->_select = $column;
            return $this;
        }
        [$column, $alias] = $this->objectName($column);
        if (!empty($alias)) {
            $column = "$column AS $alias";
        }
        $this->_select[] = $column;
    }

    public function from($table, $escape = true) {
        if (empty($escape)) {
            $this->_from = $table;
            return $this;
        } 
        [$table, $alias] = $this->objectName($table);
        if (!empty($alias)) {
            $table = "$table AS $alias";
        }
        $this->_from = $table;
        return $this;
    }

    private function _join($table, $on = "", $type = "INNER", $escape = true) {
        
        [$table, $alias] = $this->objectName($table);
        if (!empty($on)) {
            $on = " ON $on";
        }
        if (!empty($alias)) {
            $alias = " AS $alias";
        } else {
            $alias = "";
        }
        $this->_join[] = "$type JOIN $table $alias $on";

    }

    public function join($table, $on, $escape = true) {
        return $this->_join($table, $on, "INNER", $escape);
    }

    public function naturalJoin($table, $escape = true) {
        return $this->_join($table, "", "NATURAL", $escape);
    }

    public function leftJoin($table, $on, $escape = true) {
        return $this->_join($table, $on, "LEFT", $escape);
    }

    public function rightJoin($table, $on, $escape = true) {
        return $this->_join($table, $on, "RIGHT", $escape);
    }

    public function fullOuterJoin($table, $on, $escape = true) {
        return $this->_join($table, $on, "FULL OUTER", $escape);
    }

    public function where($target, $operator, $value, $escape = true) {
        if ($value === null) {
            return $this->whereIsNull($target, $escape);
        }
        if (empty($escape)) {
            $this->_where[] = "$target $operator $value";
            return $this;
        }

        [$target] = $this->objectName($target);
        $value = $this->escape($value);
        $this->_where[] = "$target $operator $value";

    }

    public function whereIsNull($target, $escape = true) {
        if (empty($escape)) {
            $this->_where[] = "$target IS NULL";
            return $this;
        }
        
        [$target] = $this->objectName($target);
        $this->_where[] = "$target IS NULL";
    }

    public function whereIn($target, array $array, $escape = true) {
        if (empty($escape)) {
            $value = implode(",", array_map([$this, "writeValue"],  $array));
            $this->_where[] = "$target IN ($value)";
            return $this;
        }
        $values = [];
        foreach ($array as $value) {
            $values[] = $this->escape($value);
        }
        $value = implode(",", $values);
        
        [$target] = $this->objectName($target);
        return $this->_where[] = "$target IN ($value)";
    }

    public function groupBy($_, $escape = true) {

    }

    public function limit($limit, $escape = true) {
        $this->_limit = $limit;
    }

    public function offset($offset, $escape = true) {
        $this->_offset = $offset;

    }

    public function distinct($value = true) {
        $this->_distinct = $value;
    }
    
    private function implodeAndConcat($concat, $delimiter, $array) {
        if (empty($array)) {
            return "";
        }
        return $concat . " " . implode($delimiter, $array);
    }

    public function escape($str) {
        if (empty($this->connection)) {
            return $this->simpleEscape($str);
        }
        $count = count($this->_values) + 1;
        $this->_values[$count] = $str;
        return ":p$count";
        
    }

    public function simpleEscape($str) {
        $search  = ["\\",   "\0",  "\n",  "\r",  "\x1a", "'",  '"', ";"];
        $replace = ["\\\\", "\\0", "\\n", "\\r", "\\Z",  "\\'", '\\"', "\\;"];
        return str_replace($search, $replace, $str);
    }

    public function writeValue($value) {
        if (is_numeric($value)) {
            return $value;
        }
        if ($value === null) {
            return "NULL";
        }
        return "'$value'";
    }

    public function objectName($str) {
        $alias = "";
        $str = $this->simpleEscape($str);
        $str = str_replace([ " as ",  " As ",  " aS "],  " AS ", $str);
        if (str_contains($str, " AS ")) {
            [$str, $alias] = explode(" AS ", $str);
        }
        $explodedColumn = explode(".", $str);
        $explodedColumn = array_filter($explodedColumn, fn ($c) => $c !== " AS ");
        $str = implode(".", array_map(fn ($c) => "`$c`", $explodedColumn));

        return [$str, $alias];
    }

    public function generateStmt($sql, $data) {

		$stmt = $this->connection->prepare($sql);
		foreach ($data as $column => $value) {
			$stmt->bindValue(":p$column", $value);
		}
		if (!$stmt || !($stmt instanceof \PDOStatement)) {
			throw new \Exception("Erro ao preparar a query SQL.");
		}
		return $stmt;
		
	}

    public function run($pdoFetch = \PDO::FETCH_OBJ, ...$params) {
        $sql = $this->build();
        $stmt = $this->generateStmt($sql, $this->_values);
        $result = $stmt->execute();
        if (empty($result)) {
            throw new \Exception(json_encode($stmt->errorInfo()));
        }
        if ($pdoFetch === null) {
            return $stmt;
        }
        
        $args = array_merge([
            "mode" => $pdoFetch
        ], $params);

        return $stmt->fetchAll(...$args);
    }
}