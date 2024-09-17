<?php
include "../../../DB/Connection.php";
include "../../classes/product/productAdd.php";

$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();

if (isset($_POST['submit'])) {

    $pro_name = isset($_POST['pro_name']) ? $_POST['pro_name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $pro_quantity = isset($_POST['pro_quantity']) ? $_POST['pro_quantity'] : '';
    $pro_price = isset($_POST['pro_price']) ? $_POST['pro_price'] : '';

    if (empty($pro_name) || empty($description) || empty($pro_quantity) || empty($pro_price) || !is_numeric($pro_quantity) || $pro_quantity <= 0 || !is_numeric($pro_price) || $pro_price <= 0) {
        header("Location: plist.php?error=Please ensure all fields are completed before submitting. Kindly try again.");
        exit();
    }

    $productAdd = new productAdd($conn);
    $productAdd->addProduct($pro_name, $description, $pro_quantity, $pro_price);
}
$dbConnection->close();
