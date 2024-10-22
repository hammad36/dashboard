<?php
include_once "product.php";
require_once '../../../app/models/abstractModel.php';
class productAdd extends product
{
    public function addProduct($productName, $description, $quantity, $price)
    {
        $sql = "INSERT INTO `Product`(`pro_name`, `description`, `pro_price`, `pro_quantity`) 
                VALUES (?, ?, ?, ?)";
        $params = [$productName, $description, $price, $quantity];
        $types = "ssdi";

        if ($this->executeQuery($sql, $params, $types)) {
            header("Location: plist.php?add=New record created successfully");
            exit();
        }
    }
}
