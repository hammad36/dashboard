<div class="container">

    <?php

    use dash\lib\alertHandler;

    alertHandler::getInstance()->handleAlert();
    ?>

    <div class="btn-container" style="display:flex; justify-content:flex-end; margin-bottom: 10px;">
        <a href="/product/add" class="btn btn-dark btn-enhanced">Add New Product</a>
    </div>

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
                            <td><?= htmlspecialchars($prod->pro_name, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($prod->pro_description, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($prod->pro_quantity, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($prod->pro_price, ENT_QUOTES, 'UTF-8') ?></td>

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
    document.getElementById("sidebarCollapse").addEventListener("click", function() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");

        sidebar.classList.toggle("active");

        if (sidebar.classList.contains("active")) {
            content.style.marginLeft = "0";
        } else {
            content.style.marginLeft = "250px";
        }
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.getAttribute('data-id');
            document.getElementById('confirmDeleteLink').setAttribute('href', `/product/delete/${productId}`);
        });
    });
</script>