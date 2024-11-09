<div class="container">
    <h1 class="title text-center">Create New Invoice</h1>
    <p class="description text-center">Fill in the details below to create a new invoice.</p>

    <form method="post" action="/invoice/add" class="invoice-form">
        <!-- Invoice Number -->
        <div class="row">
            <div class="column">
                <div class="form-group">
                    <label for="inv_number">Invoice Number</label>
                    <input type="text" name="inv_number" id="inv_number" required class="form-control">
                </div>
            </div>
        </div>

        <!-- Client Name and Email -->
        <div class="row">
            <div class="column">
                <div class="form-group">
                    <label for="client_name">Client Name</label>
                    <input type="text" name="client_name" id="client_name" required class="form-control">
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label for="client_email">Client Email</label>
                    <input type="email" name="client_email" id="client_email" required class="form-control">
                </div>
            </div>
        </div>

        <!-- Invoice Date -->
        <div class="row">
            <div class="column">
                <div class="form-group">
                    <label for="inv_date">Invoice Date</label>
                    <input type="date" name="inv_date" id="inv_date" required class="form-control">
                </div>
            </div>
        </div>

        <!-- Product Selection -->
        <h3 class="text-center">Select Products</h3>
        <div id="product-list">
            <?php foreach ($this->_data['products'] as $product): ?> <!-- Correct the reference -->
                <div class="product-item">
                    <input type="checkbox" name="selected_products[]" value="<?= $product->pro_id ?>" id="product_<?= $product->pro_id ?>">
                    <label for="product_<?= $product->pro_id ?>"><?= $product->pro_name ?> (Price: <?= $product->pro_price ?>)</label>
                    <input type="number" name="product_quantities[<?= $product->pro_id ?>]" placeholder="Quantity" min="1">
                </div>
            <?php endforeach; ?>
        </div>


        <!-- Total Amount -->
        <div class="row">
            <div class="column">
                <div class="form-group">
                    <label for="total_amount">Total Amount</label>
                    <input type="text" name="total_amount" id="total_amount" readonly class="form-control">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-primary">Add Invoice</button>
        </div>
    </form>
</div>