<?php

namespace dash\lib\database;

class PDODatabaseHandler extends databaseHandler
{
    private static $_instance;
    private static $_handler;

    // Private constructor to prevent multiple instances
    private function __construct()
    {
        self::init();
    }

    // This method delegates method calls to the PDO handler
    public function __call($name, $arguments)
    {
        return call_user_func_array([self::$_handler, $name], $arguments);
    }

    // Initialize the PDO connection
    protected static function init()
    {
        try {
            // Correct DSN string format for PDO
            self::$_handler = new \PDO(
                'mysql:host=' . DATABASE_HOST_NAME . ';dbname=' . DATABASE_DB_NAME,
                DATABASE_USER_NAME,
                DATABASE_PASSWORD
            );
            self::$_handler->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // It's better to log the error or rethrow it
            die('Database connection error: ' . $e->getMessage());
        }
    }

    // Get the singleton instance of the PDO handler
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_handler; // Return the actual PDO connection
    }
}
