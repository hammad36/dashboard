<div class="container">
    <?php

    use dash\lib\alertHandler;

    $alertHandler = alertHandler::getInstance();
    $alertHandler->handleAlert();
    ?>

    <!-- Back button -->
    <div class="btns text-right mb-3">
        <a href="/invoice" class="btn btn-dark">Back to Invoice List</a>
    </div>

    <h1 class="title text-center mb-4 animate__animated animate__fadeIn">Create New Invoice</h1>
    <p class="description text-center mb-5 animate__animated animate__fadeIn">Fill in the details below to create a new invoice.</p>

    <form id="invoiceForm" class="formInt" method="POST" action="/invoice/add">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="invoiceDate">Invoice Date</label>
                <input type="date" id="invoiceDate" name="inv_date" value="<?php echo date('Y-m-d'); ?>" readonly class="form-control">
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="clientName">Client Name</label>
                <input type="text" id="clientName" name="client_name" required class="form-control">
            </div>
            <div class="col-md-6">
                <label for="clientEmail">Client Email</label>
                <input type="email" id="clientEmail" name="client_email" required class="form-control">
            </div>
        </div>

        <!-- Product Selection Section -->
        <div id="productsContainer">
            <h3 class="text-center mb-4 animate__animated animate__fadeIn">Select Products</h3>
            <div class="row">
                <?php
                // Fetch products and available quantities passed from the controller
                $products = $this->_data['products'];
                $availableQuantities = $this->_data['availableQuantities'];

                if (isset($products) && count($products) > 0) {
                    foreach ($products as $product) {
                        $productId = $product->getProId();
                        $productName = $product->getProName();
                        $productPrice = $product->getProPrice();
                        $availableQuantity = $availableQuantities[$productId] ?? 0;

                        // If the product is out of stock, disable selection and set quantity to 0
                        $disabled = $availableQuantity == 0 ? 'disabled' : '';
                        $quantityValue = $availableQuantity == 0 ? 0 : 1;

                        echo '
                        <div class="col-md-4 mb-4 product-card-container">
                            <div class="card product-card" data-id="' . $productId . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $productName . '</h5>
                                    <p class="card-text">Price: ' . number_format($productPrice) . ' EGP</p>
                                    <p class="card-text">Available: ' . $availableQuantity . '</p>
                                    <input type="checkbox" id="product_' . $productId . '" data-id="' . $productId . '" data-price="' . $productPrice . '" data-quantity="' . $availableQuantity . '" class="form-check-input product-checkbox" ' . $disabled . '>
                                    <label for="product_' . $productId . '" class="form-check-label">Select Product</label>
                                    <input type="number" id="quantity_' . $productId . '" class="form-control product-quantity" name="products[' . $productId . ']" value="' . $quantityValue . '" min="1" max="' . $availableQuantity . '" ' . $disabled . '>
                                    <span id="total_' . $productId . '" class="product-total">Total: 0 EGP</span>
                                </div>
                            </div>
                        </div>
                    ';
                    }
                } else {
                    echo '<p>No products available</p>';
                }
                ?>
            </div>
        </div>

        <!-- Total Price Section -->
        <div class="row mt-4 justify-content-center text-center">
            <div class="col-md-6">
                <label for="totalPrice">Total Price</label>
                <input type="text" id="totalPrice" name="totalPrice" readonly class="form-control">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" name="submit" class="btn btn-primary btn-dark btn-lg animate__animated animate__pulse">Create Invoice</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const totalPriceInput = document.getElementById('totalPrice');
        const submitButton = document.querySelector('button[type="submit"]');
        let overallTotal = 0;

        function updateOverallTotal() {
            overallTotal = Array.from(productCheckboxes).reduce((acc, cb) => {
                const id = cb.dataset.id;
                if (cb.checked) {
                    const qty = parseInt(document.getElementById('quantity_' + id).value) || 0;
                    acc += qty * parseInt(cb.dataset.price);
                }
                return acc;
            }, 0);
            totalPriceInput.value = `${overallTotal} EGP`;
        }

        productCheckboxes.forEach(checkbox => {
            const productId = checkbox.dataset.id;
            const productPrice = parseInt(checkbox.dataset.price);
            const availableQuantity = parseInt(checkbox.dataset.quantity);
            const quantityInput = document.getElementById('quantity_' + productId);
            const totalElement = document.getElementById('total_' + productId);

            // Disable quantity input initially if the product is not selected
            quantityInput.disabled = !checkbox.checked;

            // Handle checkbox state changes
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    quantityInput.disabled = false;
                    quantityInput.value = 1; // Set to minimum when checked
                    totalElement.textContent = `Total: ${productPrice} EGP`;
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = 0;
                    totalElement.textContent = 'Total: 0 EGP';
                }
                updateOverallTotal();
            });

            // Quantity input change
            quantityInput.addEventListener('input', function() {
                if (checkbox.checked) {
                    let quantity = parseInt(this.value) || 0;

                    // Constrain the quantity to available stock and minimum 1
                    if (quantity > availableQuantity) {
                        quantity = availableQuantity;
                        this.value = availableQuantity;
                    } else if (quantity < 1) {
                        quantity = 1;
                        this.value = 1;
                    }

                    const total = quantity * productPrice;
                    totalElement.textContent = `Total: ${total} EGP`;
                    updateOverallTotal();
                }
            });

            // Prevent non-numeric input
            quantityInput.addEventListener('keypress', function(event) {
                if (!/^\d+$/.test(event.key)) {
                    event.preventDefault();
                }
            });
        });

        // Ensure at least one product is selected before allowing form submission
        document.getElementById('invoiceForm').addEventListener('submit', function(event) {
            const hasSelectedProduct = Array.from(productCheckboxes).some(checkbox => checkbox.checked);

            if (!hasSelectedProduct) {
                event.preventDefault();
                alert("Please select at least one product to create an invoice.");
                return false;
            }

            // Set unchecked product quantities to 0 before submission
            productCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    const productId = checkbox.dataset.id;
                    document.getElementById('quantity_' + productId).value = 0;
                }
            });
        });
    });
</script>