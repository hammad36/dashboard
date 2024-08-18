<?php
include_once "product.php";
class productUpdate extends product
{
    public function updateProduct($id, $productName, $description, $quantity, $price)
    {
        $sql = "UPDATE `product` SET `pro_name` = ?, `description` = ?, `pro_quantity` = ?, `pro_price` = ? WHERE `pro_id` = ?";
        $params = [$productName, $description, $quantity, $price, $id];
        $types = "ssdii";

        if ($this->executeQuery($sql, $params, $types)) {
            header("Location: plist.php?edit=Data Updated successfully");
            exit();
        }
    }
}
