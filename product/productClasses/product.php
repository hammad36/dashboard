<?php
class product
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    protected function executeQuery($sql, $params, $types)
    {
        $statement = $this->conn->prepare($sql);
        $statement->bind_param($types, ...$params);

        if ($statement->execute()) {
            return true;
        } else {
            echo "Failed: " . $statement->error;
            return false;
        }
    }

    protected function fetchResults($sql, $params, $types)
    {
        $statement = $this->conn->prepare($sql);
        $statement->bind_param($types, ...$params);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }
}
