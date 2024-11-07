<title>Dashboard</title>
</head>

<div class="container">
    <?php

    use dash\lib\alertHandler;

    $alertHandler = alertHandler::getInstance();
    $alertHandler->handleAlert();
    ?>

    <!-- Back button -->
    <div class="btns text-right">
        <a href="/product" class="btn btn-dark">Back to Product List</a>
    </div>

    <!-- Form Header -->
    <div class="text-center">
        <h1 class="title">Add New Product</h1>
        <p class="desc">Fill in the details below to add a new product to the inventory.</p>
    </div>

    <!-- Product Form -->
    <form method="post" enctype="application/x-www-form-urlencoded" autocomplete="off">
        <div class="row">
            <div class="column">
                <label for="pro_name">Product Name</label>
                <input type="text" id="pro_name" name="pro_name" placeholder="Enter product name" required>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" placeholder="Enter product description" required>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <label for="pro_quantity">Quantity</label>
                <input type="number" id="pro_quantity" name="pro_quantity" placeholder="Enter quantity" required>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <label for="pro_price">Price</label>
                <input type="number" id="pro_price" name="pro_price" placeholder="Enter price" required>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" name="submit">Add Product</button>
        </div>
    </form>
</div>