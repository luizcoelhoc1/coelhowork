<?php

final class Transacao {

    private static $conexao;
    private static $lastCountQuery;

    private function __construct() {
        
    }

    public static function open() {
        if (empty(self::$conexao)) {
            self::$conexao = Conexao::open();
            self::$conexao->beginTransaction();
        }
    }

    public static function get() {
        return self::$conexao;
    }

    public static function close() {
        if (self::$conexao) {
            self::$conexao->commit();
            self::$conexao = NULL;
        }
    }

    
    public static function rollback() {
        if (self::$conexao) {
            self::$conexao->rollback();
            self::$conexao = NULL;
        }
    }
    public static function lastInsertId() {
        return self::$conexao->lastInsertId();
    }
    public static function lastCountQuery() {
        return self::$conexao->lastInsertId();
    }   
    public static function setlastCountQuery($count) {
        self::$lastCountQuery = $count;
    }

}

?>