<?php
include "../DB/Connection.php";
$dbConnection = Connection::getInstance('localhost', 'hammad', 'My@2530', 'dash');
$conn = $dbConnection->getConnection();

$id = $_GET['inv_number'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceNumber = $_POST['invoiceNumber'];
    $invoiceDate = date('Y-m-d');
    $clientName = $_POST['clientName'];
    $clientEmail = $_POST['clientEmail'];
    $productIds = $_POST['productSelect'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];
    $totalPrice = $_POST['totalPrice']; // Get total price from form input

    mysqli_begin_transaction($conn);

    try {
        // Validate quantities
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index];
            $productQuery = "SELECT pro_quantity, COALESCE(SUM(ip.quantity), 0) AS total_sold 
                                    FROM Product p 
                                    LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                                    WHERE p.pro_id = '$productId' 
                                    GROUP BY p.pro_id, p.pro_quantity";
            $productResult = mysqli_query($conn, $productQuery);
            $productRow = mysqli_fetch_assoc($productResult);

            $availableQuantity = $productRow['pro_quantity'] - $productRow['total_sold'];

            if ($quantity > $availableQuantity) {
                throw new Exception("Quantity for product ID $productId exceeds available stock.");
            }
        }

        // Update Invoice table
        $sql = "UPDATE `Invoice` SET `inv_date`='$invoiceDate', `client_name`='$clientName', `client_email`='$clientEmail', `total_amount`='$totalPrice' WHERE inv_number='$invoiceNumber'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            throw new Exception('Failed to update invoice: ' . mysqli_error($conn));
        }

        // Delete existing Invoice_Product records
        $deleteInvoiceProductQuery = "DELETE FROM `Invoice_Product` WHERE inv_number='$invoiceNumber'";
        $deleteInvoiceProductResult = mysqli_query($conn, $deleteInvoiceProductQuery);
        if (!$deleteInvoiceProductResult) {
            throw new Exception('Failed to delete existing invoice products: ' . mysqli_error($conn));
        }

        // Insert updated Invoice_Product records
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index];
            $price = $prices[$index];
            $lineTotal = $quantity * $price;

            $insertInvoiceProductQuery = "INSERT INTO `Invoice_Product` (inv_number, pro_id, quantity, line_total)
            VALUES ('$invoiceNumber', '$productId', '$quantity', '$lineTotal')";
            $insertInvoiceProductResult = mysqli_query($conn, $insertInvoiceProductQuery);
            if (!$insertInvoiceProductResult) {
                throw new Exception('Failed to insert updated invoice product: ' . mysqli_error($conn));
            }
        }

        mysqli_commit($conn);
        header("Location: ilist.php?edit=Data Updated successfully");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo '<p class="description text-center">Failed to update invoice: ' . $e->getMessage() . '</p>';
    }
}

// Fetch invoice details
$invoiceQuery = "SELECT * FROM `Invoice` WHERE inv_number = '$id' LIMIT 1";
$invoiceResult = mysqli_query($conn, $invoiceQuery);
$invoice = mysqli_fetch_assoc($invoiceResult);

// Fetch products from the database and calculate the total sold quantity for each product
$productQuery = "SELECT p.pro_id, p.pro_name, p.pro_price, p.pro_quantity, 
                        COALESCE(SUM(ip.quantity), 0) AS total_sold 
                FROM Product p 
                LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                GROUP BY p.pro_id, p.pro_name, p.pro_price, p.pro_quantity";
$productResult = mysqli_query($conn, $productQuery);

// Fetch existing products for this invoice
$existingProductQuery = "SELECT * FROM `Invoice_Product` WHERE inv_number = '$id'";
$existingProductResult = mysqli_query($conn, $existingProductQuery);
$existingProducts = [];
while ($row = mysqli_fetch_assoc($existingProductResult)) {
    $existingProducts[$row['pro_id']] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <nav>
        <p class="mp">Manage invoices</p>
    </nav>
    <div class="container">

        <h1 class="title text-center">Edit Invoice</h1>
        <p class="description text-center">Edit the details to update the invoice information.</p>

        <form id="invoiceForm" class="formInt" method="POST" action="">
            <div class="row">
                <div class="column">
                    <label for="invoiceNumber">Invoice Number</label>
                    <input type="text" id="invoiceNumber" name="invoiceNumber" value="<?php echo $invoice['inv_number']; ?>" readonly>
                </div>
                <div class="column">
                    <label for="invoiceDate">Invoice Date</label>
                    <input type="date" id="invoiceDate" name="invoiceDate" value="<?php echo $invoice['inv_date']; ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="clientName">Client Name</label>
                    <input type="text" id="clientName" name="clientName" value="<?php echo $invoice['client_name']; ?>">
                </div>
                <div class="column">
                    <label for="clientEmail">Client Email</label>
                    <input type="email" id="clientEmail" name="clientEmail" value="<?php echo $invoice['client_email']; ?>">
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
                                    $selected = isset($existingProducts[$row['pro_id']]) ? 'selected' : '';
                                    $availableQuantity = $row["pro_quantity"] - $row["total_sold"];
                                    echo '<option value="' . $row["pro_id"] . '" data-price="' . $row["pro_price"] . '" data-quantity="' . $availableQuantity . '" ' . $selected . '>' . $row["pro_name"] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No products available</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div id="productDetails">
                    <?php
                    foreach ($existingProducts as $productId => $product) {
                        $productQuery = "SELECT pro_name, pro_price FROM Product WHERE pro_id = '$productId' LIMIT 1";
                        $productResult = mysqli_query($conn, $productQuery);
                        $productRow = mysqli_fetch_assoc($productResult);
                        echo '<div class="row">
                                <div class="column">
                                    <label for="quantity_' . $productId . '">' . $productRow['pro_name'] . ' Quantity</label>
                                    <input type="number" id="quantity_' . $productId . '" name="quantity[]" data-price="' . $productRow['pro_price'] . '" min="1" value="' . $product['quantity'] . '" required>
                                </div>
                                <div class="column">
                                    <label for="price_' . $productId . '">' . $productRow['pro_name'] . ' Price</label>
                                    <input type="number" id="price_' . $productId . '" name="price[]" value="' . $productRow['pro_price'] . '" step="0.01" readonly>
                                </div>
                            </div>';
                    }
                    $dbConnection->close();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="totalPrice">Total Price</label>
                    <input type="text" id="totalPrice" name="totalPrice" readonly>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="submit">Update Invoice</button>
            </div>
        </form>
    </div>

    <script src="js/edit.js"></script>

</body>

</html>