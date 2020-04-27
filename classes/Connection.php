<?php

require_once __DIR__ . '/Config.php';

class Connection
{
    public static function getConnection()
    {
        $host = Config::getDbHost();
        $dbName = Config::getDbName();
        $username = Config::getDbUser();
        $password = Config::getDbPassword();

        $pdo = new PDO("mysql:dbname=$dbName;host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}