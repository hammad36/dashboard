<?php

class Connection
{
    private $database;
    private $hostname;
    private $userName;
    private $password;
    private static $instance = null;
    private $conn;

    // Private constructor to prevent multiple instances
    private function __construct($hostname, $userName, $password, $database)
    {
        $this->hostname = $hostname;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;

        // Establish the connection
        $this->conn = mysqli_connect($this->hostname, $this->userName, $this->password, $this->database);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    // Static method to get the single instance of the class
    public static function getInstance($hostname, $userName, $password, $database)
    {
        if (self::$instance === null) {
            self::$instance = new Connection($hostname, $userName, $password, $database);
        }
        return self::$instance;
    }

    // Method to get the connection resource
    public function getConnection()
    {
        return $this->conn;
    }

    // Method to close the connection (optional, usually handled at script end)
    public function close()
    {
        if ($this->conn) {
            mysqli_close($this->conn);
            self::$instance = null; // Reset instance after closing connection
        }
    }
}
