<?php 

namespace Coelho\database;

final class Connection {

    private function __construct() {}

    public static function open(array $info = []) {

        $user = $info["user"] ?? $_ENV["DB_USER"] ?? "root";
        $pass = $info["pass"] ?? $_ENV["DB_PASS"] ?? "";
        $database = $info["database"] ?? $_ENV["DB_DATABASE"] ?? "coelhowork";
        $host = $info["host"] ?? $_ENV["DB_HOST"] ?? "localhost";
        $port = $info["port"] ?? $_ENV["DB_PORT"] ?? "3306";

        $con = new \PDO("mysql:host={$host};port={$port};dbname={$database}", $user, $pass);

        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $con->Query("SET NAMES utf8");

        return $con;
    }

    public static function openByName(string $name) {

    }

}