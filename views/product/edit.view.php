<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Edit Product</title>
    <link rel="stylesheet" href="../../assets/css/Pstyles.css">
</head>

<body>

    <div class="container">
        <!-- Back Button -->
        <div class="d-flex justify-content-end mb-3">
            <a href="./plist.php" class="btn btn-dark">Back</a>
        </div>

        <!-- Title and Description -->
        <div class="text-center">
            <h2 class="title">Edit Product</h2>
            <p class="desc">Edit the details to update the product information.</p>
        </div>

        <!-- Edit Product Form -->
        <form action="editHandler.php?id=<?php echo $product->pro_id; ?>" method="post">
            <div class="mb-3">
                <label for="pro_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="pro_name" name="pro_name"
                    value="<?php echo htmlspecialchars($product->pro_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="<?php echo htmlspecialchars($product->description); ?>" required>
            </div>
            <div class="mb-3">
                <label for="pro_quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="pro_quantity" name="pro_quantity"
                    value="<?php echo htmlspecialchars($product->pro_quantity); ?>" required>
            </div>
            <div class="mb-3">
                <label for="pro_price" class="form-label">Price</label>
                <input type="number" class="form-control" id="pro_price" name="pro_price"
                    value="<?php echo htmlspecialchars($product->pro_price); ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" name="submit">Update Product</button>
            </div>
        </form>

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>