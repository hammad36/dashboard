<?php

class Connection2
{
    private $database;
    private $hostname;
    private $userName;
    private $password;
    private static $instance = null;
    private $conn;

    private function __construct($hostname = 'localhost', $userName = 'hammad', $password = 'My@2530', $database = 'dash')
    {
        $this->hostname = $hostname;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;

        try {
            $this->conn = new PDO("mysql:host={$this->hostname};dbname={$this->database}", $this->userName, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    public static function getInstance($hostname = 'localhost', $userName = 'hammad', $password = 'My@2530', $database = 'dash')
    {
        if (self::$instance === null) {
            self::$instance = new Connection2($hostname, $userName, $password, $database);
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function close()
    {
        if ($this->conn) {
            $this->conn = null;
            self::$instance = null;
        }
    }
}
