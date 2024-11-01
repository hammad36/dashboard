<!-- CSS and Title -->
<link rel="stylesheet" href="../../public/css/invoiceStyles.css">
<link rel="stylesheet" href="../../public/css/style.css">
<title>Invoice Dashboard</title>
</head>

<div class="container">

    <?php

    use dash\lib\alertHandler;

    alertHandler::getInstance()->handleAlert();
    ?>

    <!-- Add Invoice Button -->
    <div class="btn-container" style="display:flex; justify-content:flex-end; margin-bottom: 10px;">
        <a href="/invoice/add" class="btn btn-dark btn-enhanced">Add New Invoice</a>
    </div>

    <!-- Invoice Table -->
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered text-center mt-3">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Invoice Number</th>
                    <th scope="col">Client Name</th>
                    <th scope="col">Client Email</th>
                    <th scope="col">Date</th>
                    <th scope="col">Total Amount</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($invoices !== false) : ?>
                    <?php foreach ($invoices as $inv) : ?>
                        <tr>
                            <td><?= htmlspecialchars($inv->inv_number, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($inv->client_name, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($inv->client_email, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($inv->inv_date, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($inv->total_amount, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="action-cell" style="display: flex; justify-content: space-around; align-items: center;">
                                <a href="/invoice/edit/<?= $inv->inv_number ?>" class="link-dark">
                                    <i class="fa-solid fa-pen-to-square fs-5 me-3"></i>
                                </a>
                                <a href="#" class="link-dark delete-btn" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $inv->inv_number ?>">
                                    <i class="fa-solid fa-trash fs-5"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="no-invoices">No invoices available.</td>
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
                Are you sure you want to delete this invoice?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteLink" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Set delete link dynamically based on invoice number
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const invoiceId = button.getAttribute('data-id');
            document.getElementById('confirmDeleteLink').setAttribute('href', `/invoice/delete/${invoiceId}`);
        });
    });
</script>