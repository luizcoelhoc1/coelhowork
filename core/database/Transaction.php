<?php

final class Transaction {

    private static $conneciton = false;
    private function __construct() {}

    public static function open() {
        if (empty(self::$conneciton)) {
            self::$conneciton = Connection::open();
            if (self::$conneciton) {
                self::$conneciton->beginTransaction();
            }
        }
        return self::$conneciton;
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