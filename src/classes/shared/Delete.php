<?php
class Delete
{
    private $conn;

    public function __construct()
    {
        $dbConnection = Connection::getInstance();
        $this->conn = $dbConnection->getConnection();
    }

    public function deleteRecord($table, $column, $id, $redirectPage)
    {
        $id = intval($id);
        $sql = "DELETE FROM `$table` WHERE `$column` = $id";
        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            header("Location: $redirectPage?remove=Record deleted successfully");
            exit();
        } else {
            echo "Failed: " . mysqli_error($this->conn);
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
