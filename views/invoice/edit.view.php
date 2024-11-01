<link rel="stylesheet" href="../../public/css/invoiceStyles.css">
<link rel="stylesheet" href="../../public/css/style.css">
<title>Invoice Dashboard</title>
</head>


<body>

    <div class="container">
        <div class=" btns" style="display: flex; justify-content:flex-end; margin-bottom: -40px;">
            <a href="/invoice" class="btn btn-dark">back</a>
        </div>
        <h1 class="title text-center">Edit Invoice</h1>
        <p class="description text-center">Edit the details to update the invoice information.</p>

        <form id="invoiceForm" class="formInt" method="POST" action="">
            <div class="row">
                <div class="column">
                    <label for="invoiceDate">Invoice Date</label>
                    <input type="date" id="invoiceDate" name="invoiceDate" value="<?php ?>" readonly>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="clientName">Client Name</label>
                    <input type="text" id="clientName" name="clientName" value="<?php ?>">
                </div>
                <div class="column">
                    <label for="clientEmail">Client Email</label>
                    <input type="email" id="clientEmail" name="clientEmail" value="<?php echo $invoice['client_email']; ?>">
                </div>
            </div>
            <div id="productsContainer">
                <div class="row">
                    <div class="column">
                        <label for="productSelect">Select Products <span class="description text-center"> (To modify quantities, please reselect the products.) </span></label>
                        <select id="productSelect" name="productSelect[]" multiple required>
                            <?php
                            if ($products->num_rows > 0) {
                                while ($row = $products->fetch_assoc()) {
                                    $selected = isset($existingProducts[$row['pro_id']]) ? 'selected' : '';
                                    $availableQuantity = $row["pro_quantity"] - $row["total_sold"];
                                    echo '<option value="' . $row["pro_id"] . '" data-price="' . $row["pro_price"] .
                                        '" data-quantity="' . $availableQuantity . '" ' . $selected . '>' . $row["pro_name"] .
                                        '</option>';
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