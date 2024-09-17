<?php
include "../../../DB/Connection.php";
include "../../classes/product/productAdd.php";
include "../../classes/product/productRetrieve.php";
include "../../classes/product/productUpdate.php";

$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (isset($_POST['submit'])) {
    $pro_name = isset($_POST['pro_name']) ? $_POST['pro_name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $pro_quantity = isset($_POST['pro_quantity']) ? $_POST['pro_quantity'] : '';
    $pro_price = isset($_POST['pro_price']) ? $_POST['pro_price'] : '';

    if (empty($pro_name) || empty($description) || empty($pro_quantity) || empty($pro_price) || !is_numeric($pro_quantity) || $pro_quantity <= 0 || !is_numeric($pro_price) || $pro_price <= 0) {
        header("Location: plist.php?error=Please ensure all fields are completed before submitting. Kindly try again.");
        exit();
    }

    $productUPdate = new productUpdate($conn);
    $productUPdate->updateProduct($id, $pro_name, $description, $pro_quantity, $pro_price);

    header("Location: plist.php?success=Product updated successfully.");
    exit();
}

$productRetrieve = new productRetrieve($conn);
$row = $productRetrieve->getProductbyID($id);
