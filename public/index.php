<?php

namespace dashboard;

use dashboard\lib\frontController;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once '..' . DS . 'app' . DS . 'config.php';
require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/index.css">
    <title>Dashboard</title>
    <style>
        /* Sidebar Styling */
        .wrapper {
            display: flex;
            align-items: stretch;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #3c4045;
            text-align: center;
            position: relative;
        }

        #sidebar .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        #sidebar .sidebar-header .username {
            font-size: 18px;
            font-weight: bold;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #47748b;
        }

        #sidebar ul li a {
            padding: 15px 20px;
            font-size: 1.1em;
            display: block;
            color: #ddd;
            transition: all 0.3s;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: #575757;
            text-decoration: none;
        }

        #sidebar ul li.active>a {
            color: #fff;
            background: #007bff;
        }

        /* Content Styling */
        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Navbar Styling */
        .navbar {
            padding: 10px 20px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }

        /* Toggle Button */
        #sidebarCollapse {
            background: #343a40;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        #sidebarCollapse:hover {
            background: #495057;
        }

        /* Footer Styling */
        footer {
            background: #f8f9fa;
            padding: 10px 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        /* Additional Custom Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .mp {
            color: #fff;
            font-size: 24px;
            margin: 0;
            animation: fadeInDown 1s ease-in-out;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 28px;
            margin-bottom: 10px;
            color: #333;
            animation: fadeIn 1s ease-in-out;
        }

        .desc {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666;
            animation: fadeIn 1.5s ease-in-out;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #sidebarCollapse {
                display: inline-block;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <!-- User Profile -->
                <img src="../assets/images/userPhoto.png" alt="User Photo"> <!-- Replace with dynamic user image -->
                <div class="username">Mohammed Hammad</div> <!-- Replace with dynamic username -->
            </div>

            <ul class="list-unstyled components">
                <li class="active">
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
                    <!-- Optional: Search Bar -->
                    <form class="d-flex me-auto">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <!-- User Profile Dropdown (Optional in Navbar) -->
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
                    <!-- Example Cards -->
                    <div class="col-md-4 mb-4">
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
                    <div class="col-md-4 mb-4">
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
                    <div class="col-md-4 mb-4">
                        <div class="card p-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-0">Total Users</h5>
                                    <p class="text-muted">25</p>
                                </div>
                                <div>
                                    <i class="fas fa-users fa-3x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row justify-content-center mb-4">
                    <div class="col-md-8 text-center">
                        <h1 class="title">Welcome to the Dashboard</h1>
                        <p class="desc">Choose an option below to manage products or invoices.</p>
                        <div class="d-flex justify-content-center mt-4">
                            <button onclick="location.href='../src/views/product/plist.php'" class="btn me-3">
                                <i class="fas fa-boxes me-2"></i> Product List
                            </button>
                            <button onclick="location.href='../src/views/invoice/ilist.php'" class="btn">
                                <i class="fas fa-file-invoice me-2"></i> Invoice List
                            </button>
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
                &copy; <?php echo date("Y"); ?> Your Company Name. All rights reserved.
            </footer>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS (optional if you need icons in scripts) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Custom JS for Sidebar Toggle -->
    <script>
        document.getElementById("sidebarCollapse").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("active");
        });
    </script>
</body>

</html>