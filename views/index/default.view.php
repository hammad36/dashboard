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
                                            <p class="text-muted"><?php echo htmlspecialchars($productCount, ENT_QUOTES, 'UTF-8'); ?></p>
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
                                            <p class="text-muted">40000000</p>
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
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8 mb-4">
                        <div class="card p-4">
                            <h5>Recent Activities</h5>
                            <ul class="list-group list-group-flush">
                                <?php if (!empty($this->_data['lastProduct'])): ?>
                                    <li class="list-group-item">
                                        Added new product : <strong><?php echo htmlspecialchars($this->_data['lastProduct']['pro_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    </li>
                                <?php else: ?>
                                    <li class="list-group-item">No recent products added. </li>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                </div>



            </div>