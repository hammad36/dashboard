<!-- CSS and Title -->
<link rel="stylesheet" href="../../public/css/proudctStyles.css">
<link rel="stylesheet" href="../../public/css/style.css">
<title>Dashboard</title>
</head>

<div class="container">

    <?php

    use dash\lib\alertHandler;

    alertHandler::getInstance()->handleAlert();
    ?>

    <!-- Add Product Button -->
    <div class="btn-container">
        <a href="/product/add" class="btn btn-dark btn-enhanced">Add New Product</a>
    </div>

    <!-- Product Table -->
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered text-center mt-3">
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
                <?php if ($product !== false) : ?>
                    <?php foreach ($product as $prod) : ?>
                        <tr>
                            <td><?= htmlspecialchars($prod->getProName(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($prod->getDescription(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($prod->getProQuantity(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($prod->getProPrice(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="action-cell" style=" display: flex; justify-content: space-around;align-items: center;">
                                <a href="/product/edit/<?= $prod->pro_id ?>" class="link-dark">
                                    <i class="fa-solid fa-pen-to-square fs-5 me-3"></i>
                                </a>
                                <a href="#" class="link-dark delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $prod->pro_id ?>">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="no-products">Sorry, no products available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Modal for Delete Confirmation -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteLink" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Set delete link dynamically based on product ID
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.getAttribute('data-id');
            document.getElementById('confirmDeleteLink').setAttribute('href', `/product/delete/${productId}`);
        });
    });
</script>