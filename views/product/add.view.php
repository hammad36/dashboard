<link rel="stylesheet" href="../../assets/css/Pstyles.css">

<div class="container">
    <div class=" btns" style="display: flex; justify-content:flex-end; margin-bottom: -40px;">
        <a href="/product" class="btn btn-dark">back</a>
    </div>
    <div class="text-center">
        <h1 class="title">Add New Product</h1>
        <p class="desc">Fill in the details below to add a new product to the inventory.</p>
    </div>
    <form method="post" enctype="application/x-www-form-urlencoded" autocomplete="off">
        <div class="row">
            <div class="column">
                <label for="pro_name">Product Name</label>
                <input type="text" id="pro_name" name="pro_name" required>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" required>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <label for="pro_quantity">Quantity</label>
                <input type="number" id="pro_quantity" name="pro_quantity" required>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <label for="pro_price">Price</label>
                <input type="number" id="pro_price" name="pro_price" required>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" name="submit">Add Product</button>
        </div>
    </form>
</div>