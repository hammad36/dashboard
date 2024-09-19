<?php
include "../../../DB/Connection.php";
include "../../classes/product/product.php";
include "../../classes/product/productRetrieve.php";
include "../../classes/product/productUpdate.php";
include "../../classes/invoice/invoiceController.php";
include "../../classes/invoice/invoiceCreator.php";
include "../../classes/invoice/invoiceNumberGenerator.php";
include "../../classes/invoice/invoiceValidator.php";

// Initialize database connection and required classes
$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();

$productRetrieve = new productRetrieve($conn);
$productUpdate = new productUpdate($conn);
$invoiceNumberGenerator = new invoiceNumberGenerator($conn);
$invoiceValidator = new invoiceValidator($productRetrieve);
$invoiceCreator = new invoiceCreator($conn, $productUpdate);
$invoiceController = new invoiceController($invoiceNumberGenerator, $invoiceValidator, $invoiceCreator);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = isset($_POST['clientName']) ? $_POST['clientName'] : '';
    $clientEmail = isset($_POST['clientEmail']) ? $_POST['clientEmail'] : '';
    $productSelect = isset($_POST['productSelect']) ? $_POST['productSelect'] : [];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $price = isset($_POST['price']) ? $_POST['price'] : [];
    $totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : 0;

    // Ensure the total price is calculated correctly on the server-side
    $calculatedTotalPrice = 0;
    foreach ($productSelect as $index => $productId) {
        $productRow = $productRetrieve->getProductDetails($productId);
        $pricePerItem = $productRow['pro_price'];
        $quantityForProduct = $quantity[$index];
        $calculatedTotalPrice += $pricePerItem * $quantityForProduct;
    }

    // Check if the provided total price matches the calculated total price
    if ($totalPrice != $calculatedTotalPrice) {
        header("Location: ilist.php?error=Calculated total price does not match the provided total price. Kindly try again.");
        exit();
    }

    // Validate input fields
    if (empty($clientName) || empty($clientEmail) || empty($productSelect) || empty($quantity) || empty($price) || empty($totalPrice) || !is_numeric($totalPrice) || $totalPrice <= 0) {
        header("Location: ilist.php?error=Please ensure all fields are completed before submitting. Kindly try again.");
        exit();
    }

    try {
        $invoiceValidator->validateQuantities($productSelect, $quantity);
        $invoiceController->createInvoice($clientName, $clientEmail, $productSelect, $quantity, [], $totalPrice);
        header("Location: ilist.php?success=Invoice created successfully.");
        exit();
    } catch (Exception $e) {
        echo '<p class="description text-center">Failed to create invoice: ' . $e->getMessage() . '</p>';
    }
}

$productResult = $productRetrieve->getProducts();
$newInvoiceNumber = $invoiceNumberGenerator->generateNewInvoiceNumber();

// Close the database connection
$dbConnection->close();

// Include the HTML form
include 'createInvoiceForm.php';
