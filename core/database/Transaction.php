<?php

final class Transaction {

    private static $conneciton;
    private function __construct() {}

    public static function open() {
        if (empty(self::$conneciton)) {
            self::$conneciton = Connection::open();
            self::$conneciton->beginTransaction();
        }
    }

    public static function get() {
        return self::$conneciton;
    }

    public static function close() {
        if (self::$conneciton) {
            self::$conneciton->commit();
            self::$conneciton = NULL;
        }
    }

    
    public static function rollback() {
        if (self::$conneciton) {
            self::$conneciton->rollback();
            self::$conneciton = NULL;
        }
    }
    public static function lastInsertId() {
        return self::$conneciton->lastInsertId();
    }
    public static function lastCountQuery() {
        return self::$conneciton->lastInsertId();
    }

}

?>