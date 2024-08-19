<?php
include "../../../DB/Connection.php";
include "../../classes/product/productAdd.php";
include "../../classes/product/productRetrieve.php";
include "../../classes/product/productUpdate.php";

$dbConnection = Connection::getInstance();
$conn = $dbConnection->getConnection();
$id = intval($_GET['id']);

if (isset($_POST['submit'])) {
    $productName = $_POST['pro_name'];
    $description = $_POST['description'];
    $quantity = $_POST['pro_quantity'];
    $price = $_POST['pro_price'];

    $productUPdate = new productUpdate($conn);
    $productUPdate->updateProduct($id, $productName, $description, $quantity, $price);
}

$productRetrieve = new productRetrieve($conn);
$row = $productRetrieve->getProductbyID($id);

$dbConnection->close();
?>

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
    <link rel="stylesheet" href="../../../assets/css/Pstyles.css">

</head>

<body>
    <nav>
        <h1 class="mp">Manage Products</h1>
    </nav>
    <div class="container">
        <div class="text-center">
            <h1 class="title">Edit Product</h1>
            <p class="desc">Edit the details to update the product information.</p>
        </div>



        <form action="" method="post">
            <div class="row">
                <div class="column">
                    <label for="pro_name">Product Name</label>
                    <input type="text" id="pro_name" name="pro_name" value="<?php echo $row['pro_name'] ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" value="<?php echo $row['description'] ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="pro_quantity">Quantity</label>
                    <input type="number" id="pro_quantity" name="pro_quantity" value="<?php echo $row['pro_quantity'] ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="pro_price">Price</label>
                    <input type="number" id="pro_price" name="pro_price" value="<?php echo $row['pro_price'] ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <button type="submit" name="submit">Update Product</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>