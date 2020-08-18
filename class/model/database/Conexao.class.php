<?php

final class Conexao {

    private function __construct() {
        
    }

    public static function open(array $info = null) {

        if ($info == NULL) {
            $user = __DB_USER;
            $pass = __DB_USER_PASS;
            $db = __DB_NAME;
            $host = __DB_HOST;
            $porta = __DB_PORT;
        } else {
            $user = $info["USER"];
            $pass = $info["USER_PASS"];
            $db = $info["NAME"];
            $host = $info["HOST"];
            $porta = $info["PORT"];
        }
        $con = new PDO("mysql:host={$host};port={$porta};dbname={$db}", $user, $pass);

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->Query("SET NAMES utf8");

        return $con;
    }

}

?>