<link rel="stylesheet" href="../../public/css/proudctStyles.css">
<link rel="stylesheet" href="../../public/css/style.css">

<title>Dashboard</title>
</head>

<div class="container">

    <?php

    use dash\lib\alertHandler;

    $alertHandler = alertHandler::getInstance();
    $alertHandler->handleAlert();
    ?>
    <div class="btns" style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <a href="/product/add" class="btn btn-dark btn-enhanced">Add New Product</a>
    </div>


    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered text-center" style="margin-top: 20px; ">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (false !== $product) {
                    foreach ($product as $product) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product->getProName(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($product->getProQuantity(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($product->getProPrice(), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="display: flex; justify-content:space-between; vertical-align:middle;">
                                <a href="/product/edit/<?php echo $product->pro_id ?>" class="link-dark">
                                    <i class="fa-solid fa-pen-to-square fs-5 me-3"></i>
                                </a>
                                <a href="/product/delete/<?php echo $product->pro_id ?>" class="link-dark" title="Delete" onclick="return confirm('Are you sure you want to delete this employee?');">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <td colspan="6">
                        <p>sorry no products here</p>
                    </td>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>
</div>