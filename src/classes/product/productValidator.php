<?php

class ProductValidator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function validate($productIds, $quantities)
    {
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index];
            $productRow = $this->getProductQuantity($productId);

            $availableQuantity = $productRow['pro_quantity'] - $productRow['total_sold'];
            if ($quantity > $availableQuantity) {
                throw new Exception("Quantity for product ID $productId exceeds available stock.");
            }
        }
    }

    private function getProductQuantity($productId)
    {
        $sql = "SELECT pro_quantity, COALESCE(SUM(ip.quantity), 0) AS total_sold 
                FROM Product p 
                LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                WHERE p.pro_id = ? 
                GROUP BY p.pro_id, p.pro_quantity";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
