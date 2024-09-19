<?php
include "../../../DB/Connection.php";
include_once "../../classes/invoice/invoiceUpdater.php";
include_once "../../classes/invoice/invoiceFetcher.php";
include_once "../../classes/product/productValidator.php";

$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();
$invoiceUpdater = new invoiceUpdater($conn, new productValidator($conn));
$invoiceFetcher = new invoiceFetcher($conn);
$id = isset($_GET['inv_number']) ? intval($_GET['inv_number']) : 0;
$existingProducts = $invoiceFetcher->fetchExistingProducts($id) ?? [];

$originalQuantities = [];
foreach ($existingProducts as $productId => $product) {
    $originalQuantities[$productId] = $product['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceNumber = isset($_POST['invoiceNumber']) ? $_POST['invoiceNumber'] : '';
    $invoiceDate = date('Y-m-d');
    $clientName = isset($_POST['clientName']) ? $_POST['clientName'] : '';
    $clientEmail = isset($_POST['clientEmail']) ? $_POST['clientEmail'] : '';
    $productIds = isset($_POST['productSelect']) ? $_POST['productSelect'] : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $prices = isset($_POST['price']) ? $_POST['price'] : [];
    $totalPrice = isset($_POST['totalPrice']) ? $_POST['totalPrice'] : 0;

    foreach ($productIds as $index => $productId) {
        $quantity = $quantities[$index];
        $price = $prices[$index];

        $productQuery = "SELECT pro_price, pro_quantity FROM Product WHERE pro_id = ? LIMIT 1";
        $stmt = $conn->prepare($productQuery);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $availableQuantity = $row['pro_quantity'];
            $dbPrice = $row['pro_price'];

            if ($quantity > $availableQuantity) {
                header("Location: ilist.php?error=Quantity for product ID $productId exceeds available stock.");
                exit();
            }

            if (abs($price - $dbPrice) > 0.01) {
                header("Location: ilist.php?error=Price mismatch detected for product ID $productId.");
                exit();
            }
        } else {
            header("Location: ilist.php?error=Invalid product ID $productId.");
            exit();
        }
    }

    if (empty($invoiceNumber) || empty($clientName) || empty($clientEmail) || empty($productIds) || empty($quantities) || empty($prices) || !is_numeric($totalPrice) || $totalPrice <= 0) {
        header("Location: ilist.php?error=Please ensure all fields are completed before submitting. Kindly try again.");
        exit();
    }

    $message = $invoiceUpdater->updateInvoice($invoiceNumber, $invoiceDate, $clientName, $clientEmail, $productIds, $quantities, $prices, $totalPrice);
    header("Location: ilist.php?edit=$message");
    exit();
}

$invoice = $invoiceFetcher->fetchInvoiceDetails($id);

$products = $invoiceFetcher->fetchProducts();

$existingProducts = $invoiceFetcher->fetchExistingProducts($id);
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
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="../../../assets/css/Istyles.css">
</head>

<body>
    <nav>
        <p class="mp">Manage invoices</p>
    </nav>
    <div class="container">
        <div class=" btns" style="display: flex; justify-content:flex-end; margin-bottom: -40px;">
            <a href="./ilist.php" class="btn btn-dark">back</a>
        </div>
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
                            if ($products->num_rows > 0) {
                                foreach ($products as $row) {
                                    $selected = isset($existingProducts[$row['pro_id']]) ? 'selected' : '';
                                    $existingQuantity = isset($existingProducts[$row['pro_id']]) ? $existingProducts[$row['pro_id']]['quantity'] : 0;
                                    $availableQuantity = $row['pro_quantity'] - $row['total_sold'];

                                    echo '<option value="' . $row['pro_id'] . '" data-price="' . $row['pro_price'] . '" data-quantity="' . $availableQuantity . '" data-existing-quantity="' . $existingQuantity . '" ' . $selected . '>' . $row['pro_name'] . '</option>';
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
                                    <input type="number" id="quantity_' . $productId . '" name="quantity[]" data-price="' . $productRow['pro_price'] . '" min="1" value="' . $product['quantity'] . '" readonly>
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

    <script src="../../../assets/js/edit.js"></script>

</body>

</html>