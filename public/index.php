<?php

namespace dashboard;

use dashboard\lib\frontcontroller;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once '..' . DS . 'app' . DS . 'config.php';
require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';

$frontcontroller = new frontcontroller();
$frontcontroller->dispatch();

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

    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>

<body>
    <nav>
        <h1 class="mp">Dashboard</h1>
    </nav>
    <div class="container text-center">
        <h1 class="title">Welcome to the Dashboard</h1>
        <p class="desc">Choose an option below to manage products or invoices.</p>
        <div class="d-flex justify-content-center mt-4">
            <button onclick="location.href='src/views/product/plist.php'" class="btn btn-dark mx-2">Product List</button>
            <button onclick="location.href='src/views/invoice/ilist.php'" class="btn btn-dark mx-2">Invoice List</button>
        </div>
    </div>
</body>

</html>