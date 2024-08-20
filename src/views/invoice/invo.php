<?php
include "../../../DB/Connection.php";
include "../../classes/product/product.php";
include "../../classes/product/productRetrieve.php";
include "../../classes/product/productUpdate.php";
include "../../classes/invoice/invoiceController.php";
include "../../classes/invoice/invoiceCreator.php";
include "../../classes/invoice/invoiceNumberGenerator.php";
include "../../classes/invoice/invoiceValidator.php";

$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();

$productRetrieve = new productRetrieve($conn);
$productUpdate = new productUpdate($conn);
$invoiceNumberGenerator = new invoiceNumberGenerator($conn);
$invoiceValidator = new invoiceValidator($productRetrieve);
$invoiceCreator = new invoiceCreator($conn, $productUpdate);
$invoiceController = new invoiceController($invoiceNumberGenerator, $invoiceValidator, $invoiceCreator);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = $_POST['clientName'];
    $clientEmail = $_POST['clientEmail'];
    $productIds = $_POST['productSelect'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];
    $totalAmount = $_POST['totalPrice'];

    try {
        $invoiceController->createInvoice($clientName, $clientEmail, $productIds, $quantities, $prices, $totalAmount);
    } catch (Exception $e) {
        echo '<p class="description text-center">Failed to create invoice: ' . $e->getMessage() . '</p>';
    }
}

// Fetch products for the form
$productResult = $productRetrieve->getProducts();
$newInvoiceNumber = $invoiceNumberGenerator->generateNewInvoiceNumber();

$dbConnection->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Invoice</title>
    <link rel="stylesheet" href="../../../assets/css/Istyles.css">
</head>

<body>
    <nav>
        <p class="mp">Create New Invoice</p>
    </nav>
    <div class="container">
        <h1 class="title text-center">Create New Invoice</h1>
        <p class="description text-center">Fill in the details below to create a new invoice.</p>
        <form id="invoiceForm" class="formInt" method="POST" action="">
            <div class="row">
                <div class="column">
                    <label for="invoiceNumber">Invoice Number</label>
                    <input type="text" id="invoiceNumber" name="invoiceNumber" value="<?php echo $newInvoiceNumber; ?>" readonly>
                </div>
                <div class="column">
                    <label for="invoiceDate">Invoice Date</label>
                    <input type="date" id="invoiceDate" name="invoiceDate" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="clientName">Client Name</label>
                    <input type="text" id="clientName" name="clientName" required>
                </div>
                <div class="column">
                    <label for="clientEmail">Client Email</label>
                    <input type="email" id="clientEmail" name="clientEmail" required>
                </div>
            </div>
            <div id="productsContainer">
                <div class="row">
                    <div class="column">
                        <label for="productSelect">Select Products</label>
                        <select id="productSelect" name="productSelect[]" multiple required>
                            <?php
                            if ($productResult->num_rows > 0) {
                                while ($row = $productResult->fetch_assoc()) {
                                    $availableQuantity = $row["pro_quantity"] - $row["total_sold"];
                                    echo '<option value="' . $row["pro_id"] . '" data-price="' . $row["pro_price"] .
                                        '" data-quantity="' . $availableQuantity . '">' . $row["pro_name"] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No products available</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="productDetails"></div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="totalPrice">Total Price</label>
                    <input type="text" id="totalPrice" name="totalPrice" readonly>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="submit">Create Invoice</button>
            </div>
        </form>
    </div>

    <script src="../../../assets/js/invo.js"></script>

</body>