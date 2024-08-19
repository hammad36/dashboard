<?php

include "../../../DB/Connection.php";

class invoiceListController
{
    private $conn;

    public function __construct()
    {
        $dbConnection = Connection::getInstance();
        $this->conn = $dbConnection->getConnection();
    }

    public function fetchInvoices()
    {
        $sql = "SELECT inv.inv_number, inv.inv_date, inv.client_name, inv.client_email, 
                        SUM(ip.quantity) AS total_quantity, inv.total_amount
                        FROM Invoice inv
                        LEFT JOIN Invoice_Product ip ON inv.inv_number = ip.inv_number
                        GROUP BY inv.inv_number, inv.inv_date, inv.client_name, inv.client_email, inv.total_amount";

        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    public function closeConnection()
    {
        Connection::getInstance()->close();
    }
}
