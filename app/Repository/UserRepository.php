<?php

namespace PROGAMERANYARAN\PHP\LOGIN\Repository;

use PDO;

class UserRepository
{
    private static ?\PDO $connection;

    public static function getConnection(string $env='test'): \PDO
    {
        if(self::$connection == null){
            require_once __DIR__ . "/../../config/db.php";

            $config = getDatabaseConfig();
            self::$connection = new \PDO (
                $config['database'][$env]['url'],
                $config['database'][$env]['username'],
                $config['database'][$env]['password']
            );

        }

        return self::$connection;
    }

    public static function beginTransaction()
    {
        self::$connection->beginTransaction();
    }

    public static function commit()
    {
        self::$connection->commit();
    }

    public static function rollback()
    {
        self::$connection->rollBack();
    }

}
