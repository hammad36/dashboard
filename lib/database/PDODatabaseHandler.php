<?php

namespace dash\lib\database;

class PDODatabaseHandler extends databaseHandler
{
    private static $_instance;
    private static $_handler;

    private function __construct()
    {
        self::init();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([self::$_handler, $name], $arguments);
    }

    protected static function init()
    {
        try {
            self::$_handler = new \PDO(
                'mysql:host=' . DATABASE_HOST_NAME . ';dbname=' . DATABASE_DB_NAME,
                DATABASE_USER_NAME,
                DATABASE_PASSWORD
            );
            self::$_handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Database connection error: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_handler;
    }
}
