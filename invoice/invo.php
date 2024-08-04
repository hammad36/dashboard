<?php
include("../conn.php");

// Fetch products from the database and calculate the total sold quantity for each product
$productQuery = "SELECT p.pro_id, p.pro_name, p.pro_price, p.pro_quantity, 
                        COALESCE(SUM(ip.quantity), 0) AS total_sold 
                        FROM Product p 
                        LEFT JOIN Invoice_Product ip ON p.pro_id = ip.pro_id 
                        GROUP BY p.pro_id, p.pro_name, p.pro_price, p.pro_quantity";
$productResult = mysqli_query($conn, $productQuery);

// Generate the new invoice number
$invoiceQuery = "SELECT MAX(inv_number) AS max_invoice_number FROM Invoice";
$invoiceResult = mysqli_query($conn, $invoiceQuery);
$row = mysqli_fetch_assoc($invoiceResult);
$lastInvoiceNumber = $row['max_invoice_number'];
$newInvoiceNumber = $lastInvoiceNumber ? $lastInvoiceNumber + 1 : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceNumber = $_POST['invoiceNumber'];
    $invoiceDate = date('Y-m-d');
    $clientName = $_POST['clientName'];
    $clientEmail = $_POST['clientEmail'];
    $productIds = $_POST['productSelect'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];
    $totalAmount = $_POST['totalPrice'];

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

        // Insert into Invoice table
        $insertInvoiceQuery = "INSERT INTO Invoice (inv_number, client_name, client_email, inv_date, total_amount)
                            VALUES ('$invoiceNumber', '$clientName', '$clientEmail', '$invoiceDate', '$totalAmount')";
        $insertInvoiceResult = mysqli_query($conn, $insertInvoiceQuery);

        if (!$insertInvoiceResult) {
            throw new Exception('Failed to insert invoice: ' . mysqli_error($conn));
        }

        // Insert into Invoice_Product table
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index];
            $price = $prices[$index];
            $lineTotal = $quantity * $price;

            $insertInvoiceProductQuery = "INSERT INTO Invoice_Product (inv_number, pro_id, quantity, line_total)
                                        VALUES ('$invoiceNumber', '$productId', '$quantity', '$lineTotal')";
            $insertInvoiceProductResult = mysqli_query($conn, $insertInvoiceProductQuery);

            if (!$insertInvoiceProductResult) {
                throw new Exception('Failed to insert invoice product: ' . mysqli_error($conn));
            }

            // Update product quantity in the database
            $updateProductQuery = "UPDATE Product SET pro_quantity = pro_quantity - $quantity WHERE pro_id = '$productId'";
            $updateProductResult = mysqli_query($conn, $updateProductQuery);

            if (!$updateProductResult) {
                throw new Exception('Failed to update product quantity: ' . mysqli_error($conn));
            }
        }

        mysqli_commit($conn);
        header("Location: ilist.php?add=Invoice created successfully");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo '<p class="description text-center">Failed to create invoice: ' . $e->getMessage() . '</p>';
    }
}
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Invoice</title>
    <link rel="stylesheet" href="styles.css">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('productSelect');
            const productDetails = document.getElementById('productDetails');
            const totalPriceInput = document.getElementById('totalPrice');

            productSelect.addEventListener('change', function() {
                updateProductDetails();
            });

            function updateProductDetails() {
                const selectedOptions = Array.from(productSelect.selectedOptions);
                productDetails.innerHTML = '';

                let totalPrice = 0;

                selectedOptions.forEach(option => {
                    const productId = option.value;
                    const productName = option.text;
                    const productPrice = parseFloat(option.getAttribute('data-price'));
                    const availableQuantity = parseInt(option.getAttribute('data-quantity'));

                    const container = document.createElement('div');
                    container.classList.add('row');

                    container.innerHTML = `
                        <div class="column">
                            <label for="quantity_${productId}">${productName} Quantity (Available: ${availableQuantity})</label>
                            <input type="number" id="quantity_${productId}" name="quantity[]" data-price="${productPrice}
                            " min="1" max="${availableQuantity}" required>
                        </div>
                        <div class="column">
                            <label for="price_${productId}">${productName} Price</label>
                            <input type="number" id="price_${productId}" name="price[]" 
                            value="${productPrice.toFixed(2)}" step="0.01" readonly>
                        </div>
                    `;

                    productDetails.appendChild(container);

                    const quantityInput = container.querySelector(`#quantity_${productId}`);
                    quantityInput.addEventListener('input', function() {
                        updateTotalPrice();
                    });

                    totalPrice += productPrice;
                });

                totalPriceInput.value = totalPrice.toFixed(2);
            }

            function updateTotalPrice() {
                let totalPrice = 0;
                const quantities = document.querySelectorAll('[name="quantity[]"]');
                const prices = document.querySelectorAll('[name="price[]"]');

                quantities.forEach((quantityInput, index) => {
                    const quantity = parseInt(quantityInput.value) || 0;
                    const price = parseFloat(prices[index].value) || 0;
                    totalPrice += quantity * price;
                });

                totalPriceInput.value = totalPrice.toFixed(2);
            }
        });
    </script>
</body>