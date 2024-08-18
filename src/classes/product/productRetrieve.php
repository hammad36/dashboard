<?php
include_once "product.php";
class productRetrieve extends product
{
    public function getProductbyID($id)
    {
        $sql = "SELECT * FROM `product` WHERE `pro_id` = ? LIMIT 1";
        $params = [$id];
        $types = "i";

        return $this->fetchResults($sql, $params, $types);
    }
    //////////

    public function getProducts()
    {
        $query = "SELECT p.pro_id, p.pro_name, p.pro_price, p.pro_quantity, 
                        COALESCE(SUM(ip.quantity), 0) AS total_sold 
                        FROM Product p 
                        LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                        GROUP BY p.pro_id, p.pro_name, p.pro_price, p.pro_quantity";
        return $this->conn->query($query);
    }

    public function getProductQuantity($productId)
    {
        $query = "SELECT pro_quantity, COALESCE(SUM(ip.quantity), 0) AS total_sold 
                    FROM Product p 
                    LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                    WHERE p.pro_id = ? 
                    GROUP BY p.pro_id, p.pro_quantity";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
