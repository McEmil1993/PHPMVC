<?php 
require_once 'QueryBuilder.php';

class DB {
    private static $pdo;

    public static function connect($host, $dbname, $username, $password) {
        self::$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function table($table) {
        return new QueryBuilder($table);
    }

    public static function getPdo() {
        return self::$pdo;
    }
}

?>