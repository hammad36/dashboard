<?php

class InvoiceFetcher
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function fetchInvoiceDetails($id)
    {
        $sql = "SELECT * FROM `Invoice` WHERE inv_number = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function fetchProducts()
    {
        $sql = "SELECT p.pro_id, p.pro_name, p.pro_price, p.pro_quantity, 
                    COALESCE(SUM(ip.quantity), 0) AS total_sold 
                FROM Product p 
                LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                GROUP BY p.pro_id, p.pro_name, p.pro_price, p.pro_quantity";
        return $this->conn->query($sql);
    }

    public function fetchExistingProducts($id)
    {
        $sql = "SELECT * FROM `Invoice_Product` WHERE inv_number = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingProducts = [];
        while ($row = $result->fetch_assoc()) {
            $existingProducts[$row['pro_id']] = $row;
        }
        return $existingProducts;
    }
}
