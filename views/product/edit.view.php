<title>Dashboard</title>
</head>
<div class="container">
    <!-- Back Button -->
    <div class="btns text-right">
        <a href="/product" class="btn btn-dark">Back to Product List</a>
    </div>

    <!-- Title and Description -->
    <div class="text-center">
        <h2 class="title">Edit Product</h2>
        <p class="desc">Edit the details to update the product information.</p>
    </div>

    <!-- Edit Product Form -->
    <form action="/product/edit/<?= htmlspecialchars($product->pro_id); ?>" method="post">
        <div class="mb-3">
            <label for="pro_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="pro_name" name="pro_name"
                value="<?= htmlspecialchars($product->pro_name); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description"
                value="<?= htmlspecialchars($product->description); ?>" required>
        </div>

        <div class="mb-3">
            <label for="pro_quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="pro_quantity" name="pro_quantity"
                value="<?= htmlspecialchars($product->pro_quantity); ?>" required>
        </div>

        <div class="mb-3">
            <label for="pro_price" class="form-label">Price</label>
            <input type="number" class="form-control" id="pro_price" name="pro_price"
                value="<?= htmlspecialchars($product->pro_price); ?>" required>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" name="submit">Update Product</button>

        </div>
    </form>
</div>