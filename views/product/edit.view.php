<div class="container my-5 p-4 bg-light rounded shadow">
    <?php

    use dash\lib\alertHandler;

    $alertHandler = alertHandler::getInstance();
    $alertHandler->handleAlert();
    ?>

    <!-- Back Button -->
    <div class="text-right mb-3">
        <a href="/product" class="btn btn-dark">
            <i class="fas fa-arrow-left"></i> Back to Product List
        </a>
    </div>

    <!-- Form Header -->
    <div class="text-center mb-4">
        <h1 class="display-5">Edit Product</h1>
        <p class="text-muted">Modify the fields below to update the product information.</p>
    </div>

    <!-- Edit Product Form -->
    <form action="/product/edit/<?= htmlspecialchars($product->pro_id); ?>" method="post" class="needs-validation" novalidate>

        <!-- Product Name -->
        <div class="mb-3">
            <label for="pro_name" class="form-label">Product Name</label>
            <input type="text" id="pro_name" name="pro_name" class="form-control" placeholder="Enter product name"
                value="<?= htmlspecialchars($product->pro_name); ?>" required>
            <div class="invalid-feedback">Product name is required.</div>
        </div>

        <!-- Product Description -->
        <div class="mb-3">
            <label for="pro_description" class="form-label">Description</label>
            <input type="text" id="pro_description" name="pro_description" class="form-control" placeholder="Enter product description"
                value="<?= htmlspecialchars($product->pro_description); ?>" required>
            <div class="invalid-feedback">Product description is required.</div>
        </div>

        <!-- Quantity -->
        <div class="mb-3">
            <label for="pro_quantity" class="form-label">Quantity</label>
            <input type="number" id="pro_quantity" name="pro_quantity" class="form-control" placeholder="Enter quantity" min="1" max="500000"
                value="<?= htmlspecialchars($product->pro_quantity); ?>" required>
            <div class="invalid-feedback">Please enter a valid product quantity between 1 and 500,000.</div>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="pro_price" class="form-label">Price</label>
            <input type="number" id="pro_price" name="pro_price" class="form-control" placeholder="Enter price" min="1" max="3000000"
                value="<?= htmlspecialchars($product->pro_price); ?>" required>
            <div class="invalid-feedback">Please enter a valid product price between 1 and 3,000,000.</div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
            <button type="submit" name="submit" class="btn btn-primary btn-dark btn-lg animate__animated animate__pulse"> Update Product</button>
        </div>
    </form>
</div>

<!-- Optional JavaScript for Form Validation -->
<script>
    // JavaScript to enable Bootstrap validation styles
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>