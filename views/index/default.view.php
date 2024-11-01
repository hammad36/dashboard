<link rel="stylesheet" href="../../public/css/style.css">
<title>Admin Dashboard</title>
</head>
<!-- Main Content -->
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Welcome Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8 text-center">
                <h1 class="display-12 fw-bold">Welcome to Your Dashboard</h1>
                <p class="lead text-muted">Effortlessly manage your product inventory and invoices with our streamlined tools.</p>
            </div>
        </div>

        <!-- Overview Cards for Total Products & Invoices -->
        <div class="container-cards">
            <div class="row justify-content-center mb-4">
                <div class="col-md-6 mb-4">
                    <div class="card p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Total Products</h5>
                                <p class="text-muted">Items in inventory: <strong><?php echo htmlspecialchars($productCount, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                            </div>
                            <div>
                                <i class="fas fa-boxes fa-3x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Total Invoices</h5>
                                <p class="text-muted">Invoices generated: <strong><?php echo htmlspecialchars($invoiceCount, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                            </div>
                            <div>
                                <i class="fas fa-file-invoice fa-3x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="row justify-content-center recent-activities">
            <div class="col-md-10 col-lg-8 mb-4">
                <div class="card p-4 shadow-sm">
                    <h5 class="mb-3">Recent Activities</h5>
                    <ul class="list-group list-group-flush">
                        <!-- Recent Product Activity -->
                        <?php if (!empty($this->_data['lastProduct'])): ?>
                            <li class="list-group-item">
                                <?php
                                echo 'New product added: <strong>' . htmlspecialchars($this->_data['lastProduct']['pro_name'], ENT_QUOTES, 'UTF-8') . '</strong>';
                                echo ' | Quantity: <strong>' . htmlspecialchars($this->_data['lastProduct']['pro_quantity'], ENT_QUOTES, 'UTF-8') . '</strong>';
                                echo ' | Price: <strong>' . htmlspecialchars($this->_data['lastProduct']['pro_price'], ENT_QUOTES, 'UTF-8') . ' EGP</strong>';
                                ?>
                            </li>
                        <?php else: ?>
                            <li class="list-group-item">No recent products added.</li>
                        <?php endif; ?>

                        <!-- Recent Invoice Activity -->
                        <?php if (!empty($this->_data['lastInvoice'])): ?>
                            <li class="list-group-item">
                                <?php
                                echo 'New invoice generated for <strong>' . htmlspecialchars($this->_data['lastInvoice']['client_name'], ENT_QUOTES, 'UTF-8') . '</strong>';
                                echo ' | Total Amount: <strong>' . htmlspecialchars($this->_data['lastInvoice']['total_amount'], ENT_QUOTES, 'UTF-8') . ' EGP</strong>';
                                ?>
                            </li>
                        <?php else: ?>
                            <li class="list-group-item">No recent invoices created.</li>
                            <?php error_log("No recent invoice available in default.view.php."); ?>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>