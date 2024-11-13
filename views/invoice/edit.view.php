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

    <h1 class="title text-center mb-4 animate__animated animate__fadeIn">Edit Invoice</h1>
    <p class="description text-center mb-5 animate__animated animate__fadeIn">Update the details below to modify the invoice.</p>

    <form id="invoiceForm" class="formInt" method="POST" action="/invoice/edit/<?= $invoice->getInvNumber() ?>">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <label for="invoiceDate">Invoice Date</label>
                <input type="date" id="invoiceDate" name="inv_date" value="<?= htmlspecialchars($invoice->getInvDate(), ENT_QUOTES, 'UTF-8') ?>"
                    readonly class="form-control">
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="clientName">Client Name</label>
                <input type="text" id="clientName" name="client_name" value="<?= htmlspecialchars($invoice->getClientName(), ENT_QUOTES, 'UTF-8') ?>" required class="form-control">
            </div>
            <div class="col-md-6">
                <label for="clientEmail">Client Email</label>
                <input type="email" id="clientEmail" name="client_email" value="<?= htmlspecialchars($invoice->getClientEmail(), ENT_QUOTES, 'UTF-8') ?>"
                    required class="form-control">
            </div>
        </div>

        <!-- Product Selection Section -->
        <div id="productsContainer">
            <h3 class="text-center mb-4 animate__animated animate__fadeIn">Select Products</h3>
            <div class="row">
                <?php
                $products = $this->_data['products'] ?? [];
                $selectedProducts = $this->_data['selectedProducts'] ?? [];

                if (count($products) > 0) {
                    foreach ($products as $product) {
                        $productId = $product->getProId();
                        $productName = $product->getProName();
                        $productPrice = $product->getProPrice();
                        $availableQuantity = $availableQuantities[$productId] ?? 0;

                        $checked = isset($selectedProducts[$productId]) ? 'checked' : '';
                        $selectedQuantity = $checked ? $selectedProducts[$productId] : 0;
                        $disabled = $availableQuantity == 0 ? 'disabled' : '';

                        echo '
                <div class="col-md-4 mb-4 product-card-container">
                    <div class="card product-card" data-id="' . $productId . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $productName . '</h5>
                            <p class="card-text">Price: ' . number_format($productPrice) . ' EGP</p>
                            <p class="card-text">Available: ' . $availableQuantity . '</p>
                            <input type="checkbox" id="product_' . $productId . '" data-id="' . $productId . '" data-price="' . $productPrice . '" data-quantity="' . $availableQuantity . '" class="form-check-input product-checkbox" ' . $checked . ' ' . $disabled . '>
                            <label for="product_' . $productId . '" class="form-check-label">Select Product</label>
                            <input type="number" id="quantity_' . $productId . '" class="form-control product-quantity" name="products[' . $productId . ']" value="' . $selectedQuantity . '" min="1" max="' . $availableQuantity . '" ' . ($checked ? '' : 'disabled') . '>
                            <span id="total_' . $productId . '" class="product-total">Total: ' . ($selectedQuantity * $productPrice) . ' EGP</span>
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
                <input type="text" id="totalPrice" name="totalPrice" value="<?= htmlspecialchars($invoice->getTotalAmount(), ENT_QUOTES, 'UTF-8') ?>"
                    readonly class="form-control">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" name="submit" class="btn btn-primary btn-dark btn-lg animate__animated animate__pulse">Update Invoice</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const totalPriceInput = document.getElementById('totalPrice');

        productCheckboxes.forEach(checkbox => {
            const productId = checkbox.dataset.id;
            const productPrice = parseInt(checkbox.dataset.price);
            const maxAvailable = parseInt(checkbox.dataset.quantity);
            const quantityInput = document.getElementById('quantity_' + productId);
            const totalElement = document.getElementById('total_' + productId);

            // Initialize for checked items
            if (checkbox.checked) {
                quantityInput.disabled = false;
                updateTotal();
            }

            // Checkbox change event
            checkbox.addEventListener('change', function() {
                quantityInput.disabled = !this.checked;
                quantityInput.value = this.checked ? 1 : 0;
                updateTotal();
            });

            // Quantity input event
            quantityInput.addEventListener('input', function() {
                if (parseInt(this.value) > maxAvailable) this.value = maxAvailable; // Cap input to available quantity
                updateTotal();
            });

            function updateTotal() {
                let overallTotal = 0;

                productCheckboxes.forEach(cb => {
                    const id = cb.dataset.id;
                    const price = parseInt(cb.dataset.price);
                    const qty = parseInt(document.getElementById('quantity_' + id).value) || 0;
                    const lineTotal = qty * price;

                    document.getElementById('total_' + id).textContent = 'Total: ' + lineTotal + ' EGP';
                    overallTotal += lineTotal;
                });

                totalPriceInput.value = overallTotal + ' EGP';
            }
        });
    });
</script>