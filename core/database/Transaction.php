<?php

namespace Coelho\database;
use Coelho\database\Connection;

final class Transaction {

    private static $connecitons = [];
    private function __construct() {}

     private static function open(string $nameTransaction = "default") {
        self::$connecitons[$nameTransaction] = Connection::open();
        if (!empty(self::$connecitons[$nameTransaction])) {
            self::$connecitons[$nameTransaction]->beginTransaction();
        }
    }

    public static function get($nameTransaction = "default") {
        if (empty(self::$connecitons[$nameTransaction])) {
            self::open($nameTransaction);
        }
        return self::$connecitons[$nameTransaction];
    }

    public static function close($nameTransaction = "default") {
        if (!empty(self::$connecitons[$nameTransaction])) {
            self::$connecitons[$nameTransaction]->commit();
            self::$connecitons[$nameTransaction] = NULL;
        }
    }

    public static function rollback($nameTransaction = "default") {
        if (!empty(self::$connecitons[$nameTransaction])) {
            self::$connecitons[$nameTransaction]->rollback();
            self::$connecitons[$nameTransaction] = NULL;
        }
    }

    public static function lastInsertId($nameTransaction = "default") {
        return self::$connecitons[$nameTransaction]->lastInsertId();
    }

    public static function lastCountQuery($nameTransaction = "default") {
        return self::$connecitons[$nameTransaction]->lastInsertId();
    }

}

?>