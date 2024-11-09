<div class="container">
    <?php

    use dash\lib\alertHandler;
    use dash\models\productModel;

    $alertHandler = alertHandler::getInstance();
    $alertHandler->handleAlert();
    ?>

    <!-- Back button -->
    <div class="btns text-right">
        <a href="/invoice" class="btn btn-dark">Back to Invoice List</a>
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
                        // Access product data from _data array
                        $this->_data['productResult'] = productModel::getAll();
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



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('productSelect');
        const productDetails = document.getElementById('productDetails');

        console.log("Product Select Loaded:", productSelect.options); // Debugging line

        productSelect.addEventListener('change', function() {
            productDetails.innerHTML = ''; // Clear previous details
            Array.from(productSelect.selectedOptions).forEach(option => {
                console.log("Selected Option:", option); // Debugging line
                const productId = option.value;
                const productName = option.textContent;
                const productPrice = option.getAttribute('data-price');
                const productQuantity = option.getAttribute('data-quantity');

                const productDiv = document.createElement('div');
                productDiv.classList.add('product-item');

                productDiv.innerHTML = `
                <h4>${productName}</h4>
                <p>Price: ${productPrice}</p>
                <p>Available Quantity: ${productQuantity}</p>
                <label for="quantity_${productId}">Quantity:</label>
                <input type="number" id="quantity_${productId}" name="product_quantities[${productId}]" min="1" max="${productQuantity}" placeholder="Quantity">
            `;

                productDetails.appendChild(productDiv);
            });
        });
    });
</script>