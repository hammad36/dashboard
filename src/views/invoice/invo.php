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
    $clientName = isset($_POST['clientName']) ? $_POST['clientName'] : '';
    $clientEmail = isset($_POST['clientEmail']) ? $_POST['clientEmail'] : '';
    $productSelect = isset($_POST['productSelect']) ? $_POST['productSelect'] : [];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $price = isset($_POST['price']) ? $_POST['price'] : [];
    $totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : 0;

    if (empty($clientName) || empty($clientEmail) || empty($productSelect) || empty($quantity) || empty($price) || empty($totalPrice)) {
        header("Location: ilist.php?error=Please ensure all fields are completed before submitting. Kindly try again.");
        exit();
    }


    try {
        $invoiceValidator->validateQuantities($productSelect, $quantity);

        foreach ($productSelect as $index => $productId) {
            $productRow = $productRetrieve->getProductDetails($productId);
            $pricePerItem = $productRow['pro_price'];
            $quantityForProduct = $quantity[$index];
            $totalPrice += $pricePerItem * $quantityForProduct;
        }

        $invoiceController->createInvoice($clientName, $clientEmail, $productSelect, $quantity, [], $totalPrice);
    } catch (Exception $e) {
        echo '<p class="description text-center">Failed to create invoice: ' . $e->getMessage() . '</p>';
    }
}

$productResult = $productRetrieve->getProducts();
$newInvoiceNumber = $invoiceNumberGenerator->generateNewInvoiceNumber();

$dbConnection->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Create New Invoice</title>
    <link rel="stylesheet" href="../../../assets/css/Istyles.css">
</head>

<body>
    <nav>
        <p class="mp">Create New Invoice</p>
    </nav>
    <div class="container">
        <div class=" btns" style="display: flex; justify-content:flex-end; margin-bottom: -40px;">
            <a href="./ilist.php" class="btn btn-dark">back</a>
        </div>
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