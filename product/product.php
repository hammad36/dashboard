<?php
class product
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }
}
