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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <title>Add New Product</title>
    <link rel="stylesheet" href="../../../assets/css/Pstyles.css">
</head>

<body>
    <nav>
        <h1 class="mp">Manage Products</h1>
    </nav>

    <div class="container">

        <?php
        include_once "../../classes/shared/alertHandler.php";

        $alertHandler = new alertHandler();
        $alertHandler->handleAlert();
        ?>


        <div class=" btns" style="display: flex; justify-content:space-between;">
            <a href="addNew.html" class="btn btn-dark">Add New Product</a>
            <a href="../../../public/index.php" class="btn btn-dark">back</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-center" style="margin-top: 20px; ">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../../../DB/Connection.php";
                    $dbConnection = Connection::getInstance();
                    $conn = $dbConnection->getConnection();

                    $sql = "SELECT * FROM Product";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['pro_id'] ?></td>
                            <td><?php echo $row['pro_name'] ?></td>
                            <td><?php echo $row['description'] ?></td>
                            <td><?php echo $row['pro_quantity'] ?></td>
                            <td><?php echo $row['pro_price'] ?></td>
                            <td style="display: flex; justify-content:space-between; vertical-align:middle;">
                                <a href="edit.php?id=<?php echo $row['pro_id'] ?>" class="link-dark">
                                    <i class="fa-solid fa-pen-to-square fs-5 me-3"></i>
                                </a>
                                <a href="../../classes/product/deleteProduct.php?id=<?php echo $row['pro_id'] ?>" class="link-dark" title="Delete" onclick="return confirm('Are you sure you want to delete this employee?');">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </a>
                            </td>
                        </tr>
                    <?php }
                    $dbConnection->close();
                    ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>