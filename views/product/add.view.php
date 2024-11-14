<div class="container my-5 p-4 bg-light rounded shadow">
    <?php

    use dash\lib\alertHandler;

    $alertHandler = alertHandler::getInstance();
    $alertHandler->handleAlert();
    ?>

    <div class="text-right mb-3">
        <a href="/product" class="btn btn-dark">
            <i class="fas fa-arrow-left"></i> Back to Product List
        </a>
    </div>

    <div class="text-center mb-4">
        <h1 class="display-5">Add New Product</h1>
        <p class="text-muted">Fill in the details below to add a new product to the inventory.</p>
    </div>

    <form method="post" enctype="application/x-www-form-urlencoded" autocomplete="off" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="pro_name" class="form-label">Product Name</label>
            <input type="text" id="pro_name" name="pro_name" class="form-control" placeholder="Enter product name" required>
            <div class="invalid-feedback">Product name is required.</div>
        </div>
        <div class="mb-3">
            <label for="pro_description" class="form-label">Description</label>
            <input type="text" id="pro_description" name="pro_description" class="form-control" placeholder="Enter product description" required>
            <div class="invalid-feedback">Product description is required.</div>
        </div>
        <div class="mb-3">
            <label for="pro_quantity" class="form-label">Quantity</label>
            <input type="number" id="pro_quantity" name="pro_quantity" class="form-control" placeholder="Enter quantity" min="1" max="1500000" required>
            <div class="invalid-feedback">Please enter a valid product quantity.</div>
        </div>
        <div class="mb-3">
            <label for="pro_price" class="form-label">Price</label>
            <input type="number" id="pro_price" name="pro_price" class="form-control" placeholder="Enter price" min="1" max="1500000" required>
            <div class="invalid-feedback">Please enter a valid product price.</div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" name="submit" class="btn btn-primary btn-dark btn-lg animate__animated animate__pulse">Add Product</button>
        </div>
    </form>
</div>

<script>
    // document.getElementById("sidebarCollapse").addEventListener("click", function() {
    //     const sidebar = document.getElementById("sidebar");
    //     const content = document.getElementById("content");

    //     sidebar.classList.toggle("active");

    //     if (sidebar.classList.contains("active")) {
    //         content.style.marginLeft = "0";
    //     } else {
    //         content.style.marginLeft = "250px";
    //     }
    // });


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