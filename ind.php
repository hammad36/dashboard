<?php

namespace dash;

use dash\lib\frontController;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once  'config' . DS . 'config.php';
require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';

$frontController = new frontController();
$frontController->dispatch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Dashboard</title>

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-user-circle" style="font-size: 100px;"></i>
                <div class="username">Mohammed Hammad</div>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="#"><i class="fas fa-tachometer-alt"></i> Home</a>
                </li>
                <li>
                    <a href="../src/views/product/plist.php"><i class="fas fa-boxes"></i> Product List</a>
                </li>
                <li>
                    <a href="../src/views/invoice/ilist.php"><i class="fas fa-file-invoice"></i> Invoice List</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-bell"></i> Notifications</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-cogs"></i> Settings</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-life-ring"></i> Support</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">



            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn me-3">
                        <i class="fas fa-bars"></i>
                    </button>
                    <form class="d-flex me-auto">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Admin
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row mb-4">
                    <!-- Welcome Section -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-8 text-center">
                            <h1 class="display-4 fw-bold">Welcome to the Dashboard</h1>
                            <p class="lead text-muted">Effortlessly oversee and manage your product inventory and invoices with comprehensive tools at your disposal.</p>
                        </div>
                    </div>

                    <!-- Cards for Total Products & Invoices -->
                    <div class="container-cards">
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="card p-4">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="mb-0">Total Products</h5>
                                            <p class="text-muted">150</p>
                                        </div>
                                        <div>
                                            <i class="fas fa-boxes fa-3x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card p-4">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="mb-0">Total Invoices</h5>
                                            <p class="text-muted">75</p>
                                        </div>
                                        <div>
                                            <i class="fas fa-file-invoice fa-3x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Optional: Additional Content Sections -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card p-4">
                            <h5>Recent Activities</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Added new product: <strong>Product A</strong></li>
                                <li class="list-group-item">Created invoice #12345</li>
                                <li class="list-group-item">User <strong>Mohammed Hammad</strong> registered</li>
                                <!-- Add more activities -->
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card p-4">
                            <h5>Notifications</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">New product <strong>Product B</strong> added</li>
                                <li class="list-group-item">Invoice #12346 has been paid</li>
                                <li class="list-group-item">User <strong>Mohammed Hammad</strong> updated profile</li>
                                <!-- Add more notifications -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer>
                &copy; <?php echo date("Y"); ?> Hammad. All rights reserved.
            </footer>
        </div> <!-- End of content -->
    </div> <!-- End of wrapper -->

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Custom JS for Sidebar Toggle -->
    <script>
        document.getElementById("sidebarCollapse").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");

            sidebar.classList.toggle("active");

            // Adjust the content margin based on sidebar state
            if (sidebar.classList.contains("active")) {
                content.style.marginLeft = "0"; // If active, no margin
            } else {
                content.style.marginLeft = "250px"; // Reset margin for visible sidebar
            }
        });
    </script>
</body>

</html>