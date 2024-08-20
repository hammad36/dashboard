<?php

class Connection
{
    private $database;
    private $hostname;
    private $userName;
    private $password;
    private static $instance = null;
    private $conn;

    private function __construct($hostname = 'localhost', $userName = 'hammad', $password = '', $database = 'dash')
    {
        $this->hostname = $hostname;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;

        $this->conn = mysqli_connect($this->hostname, $this->userName, $this->password, $this->database);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public static function getInstance($hostname = 'localhost', $userName = 'hammad', $password = '', $database = 'dash')
    {
        if (self::$instance === null) {
            self::$instance = new Connection($hostname, $userName, $password, $database);
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
            mysqli_close($this->conn);
            self::$instance = null;
        }
    }
}
