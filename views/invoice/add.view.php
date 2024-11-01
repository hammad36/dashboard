<link rel="stylesheet" href="../../public/css/invoiceStyles.css">
<link rel="stylesheet" href="../../public/css/style.css">
<title>Invoice Dashboard</title>
</head>

<div class="container">
    <div class="btns" style="display: flex; justify-content:flex-end; margin-bottom: -40px;">
        <a href="/invoice" class="btn btn-dark">back</a>
    </div>
    <h1 class="title text-center">Create New Invoice</h1>
    <p class="description text-center">Fill in the details below to create a new invoice.</p>
    <form id="invoiceForm" class="formInt" method="POST" action="">
        <div class="row">
            <div class="column">
                <label for="invoiceDate">Invoice Date</label>
                <input type="date" id="invoiceDate" name="inv_date" value="<?php echo date('Y-d-m'); ?>" readonly>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <label for="clientName">Client Name</label>
                <input type="text" id="clientName" name="client_name" required>
            </div>
            <div class="column">
                <label for="clientEmail">Client Email</label>
                <input type="email" id="clientEmail" name="client_email" required>
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
                                echo '<option value="' . htmlspecialchars($row["pro_id"], ENT_QUOTES, 'UTF-8') . '" 
                                        data-price="' . htmlspecialchars($row["pro_price"], ENT_QUOTES, 'UTF-8') . '" 
                                        data-quantity="' . htmlspecialchars($availableQuantity, ENT_QUOTES, 'UTF-8') . '">'
                                    . htmlspecialchars($row["pro_name"], ENT_QUOTES, 'UTF-8') .
                                    '</option>';
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
                <input type="text" id="totalPrice" name="total_price" readonly>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" name="submit">Create Invoice</button>
        </div>
    </form>
</div>