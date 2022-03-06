<?php 
final class Connection {

    private function __construct() {}

    public static function open(array $info = null) {
        
        //adaptação para php < 8.0 (constante indefinida)
        $dbUser = defined("__DB_USER") ? __DB_USER : null;
        $dbUserPass = defined("__DB_USER_PASS") ? __DB_USER_PASS : null;
        $dbName = defined("__DB_NAME") ? __DB_NAME : null;
        $dbHost = defined("__DB_HOST") ? __DB_HOST : null;
        $dbPortporta = defined("__DB_PORT") ? __DB_PORT : null;;

        $dbUser = $info["__DB_USER"] ?? $dbUser ?? "root";
        $dbUserPass = $info["__DB_USER_PASS"] ?? $dbUserPass ?? "";
        $dbName = $info["__DB_NAME"] ?? $dbName ?? "information_schema";
        $dbHost = $info["__DB_HOST"] ?? $dbHost ?? "localhost";
        $dbPortporta = $info["__DB_PORT"] ?? $dbPortporta ?? "3306";

        try {
            $con = new PDO("mysql:host={$dbHost};port={$dbPortporta};dbname={$dbName}", $dbUser, $dbUserPass);
    
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $con->Query("SET NAMES utf8");
    
            return $con;
        } catch (Exception $e) {
            return false;
        }
    }

}