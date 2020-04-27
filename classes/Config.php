<?php


class Config
{
    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'tresor_naturel2';

    /**
     * Database port
     * @var string
     */
    const DB_PORT = '';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'root';

    public static function getConnectionString()
    {
        $connectionString = getenv('MYSQLCONNSTR_localdb');

        if ($connectionString === false) {
            return false;
        }

        $exploded = explode(';', $connectionString);
        $connectionStringResult = [];

        foreach ($exploded as $item) {
            $itemExploded = explode('=', $item);
            $connectionStringResult[$itemExploded[0]] = $itemExploded[1];
        }

        return $connectionStringResult;
    }

    public static function getDbName()
    {
        if (!self::getConnectionString()) {
            return self::DB_NAME;
        }

        return self::getConnectionString()['Database'];
    }

    public static function getDbUser()
    {
        if (!self::getConnectionString()) {
            return self::DB_USER;
        }

        return self::getConnectionString()['User Id'];
    }

    public static function getDbPassword()
    {
        if (!self::getConnectionString()) {
            return self::DB_PASSWORD;
        }

        return self::getConnectionString()['Password'];
    }

    public static function getDbPort()
    {
        if (!self::getConnectionString()) {
            return self::DB_PORT;
        }
        $dataSource = self::getConnectionString()['Data Source'];
        $host = explode(':', $dataSource)[1];

        return $host;
    }

    public static function getDbHost()
    {
        if (!self::getConnectionString()) {
            return self::DB_HOST;
        }
        $dataSource = self::getConnectionString()['Data Source'];
        $host = explode(':', $dataSource)[0];

        return $host;
    }
}
