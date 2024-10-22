<?php
include "../../../DB/Connection.php";
include "../../classes/product/productAdd.php";

$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();

if (isset($_POST['submit'])) {

    // Sanitize inputs
    $pro_name = isset($_POST['pro_name']) ? htmlspecialchars(trim($_POST['pro_name']), ENT_QUOTES, 'UTF-8') : '';
    $description = isset($_POST['description']) ? htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8') : '';
    $pro_quantity = isset($_POST['pro_quantity']) ? filter_var(trim($_POST['pro_quantity']), FILTER_SANITIZE_NUMBER_INT) : '';
    $pro_price = isset($_POST['pro_price']) ? filter_var(trim($_POST['pro_price']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : '';


    // Validation
    if (
        empty($pro_name) || empty($description) || empty($pro_quantity) || empty($pro_price) ||
        !is_numeric($pro_quantity) || $pro_quantity <= 0 ||
        !is_numeric($pro_price) || $pro_price <= 0
    ) {
        header("Location: plist.php?error=Please ensure all fields are completed and valid before submitting. Kindly try again.");
        exit();
    }

    $productAdd = new productAdd($conn);
    $productAdd->addProduct($pro_name, $description, $pro_quantity, $pro_price);
}
$dbConnection->close();
