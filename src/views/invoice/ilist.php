<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Manage Invoices</title>
    <link rel="stylesheet" href="../../../assets/css/Istyles.css">
</head>

<body>
    <nav>
        <h1 class="mp">Manage Invoices</h1>
    </nav>

    <div class="container">
        <?php

        include_once "../../classes/shared/alertHandler.php";

        $alertHandler = new alertHandler();
        $alertHandler->handleAlert();

        ?>

        <div class="constainer" style="display: flex; justify-content:space-between;">
            <a href="invo.php" class="btn btn-dark">Add New Invoice</a>
            <a href="../../../index.php" class="btn btn-dark">back</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered text-center" style="margin-top: 20px;">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Invoice Number</th>
                        <th scope="col">Invoice Date</th>
                        <th scope="col">Client Name</th>
                        <th scope="col">Client Email</th>
                        <th scope="col">Total Quantity</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    include_once "../../classes/invoice/invoiceListController.php";

                    $invoiceListController = new invoiceListController();
                    $result = $invoiceListController->fetchInvoices();

                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $row['inv_number'] ?></td>
                            <td><?php echo $row['inv_date'] ?></td>
                            <td><?php echo $row['client_name'] ?></td>
                            <td><?php echo $row['client_email'] ?></td>
                            <td><?php echo $row['total_quantity'] ?></td>
                            <td><?php echo $row['total_amount'] ?></td>
                            <td style="display: flex; justify-content:space-between; vertical-align:middle;">
                                <a href="edit.php?inv_number=<?php echo $row['inv_number'] ?>" class="link-dark">
                                    <i class="fa-solid fa-pen-to-square fs-5 me-3"></i>
                                </a>
                                <a href="../../classes/invoice/deleteInvoice.php?inv_number=<?php echo $row['inv_number'] ?>" class="link-dark">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
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