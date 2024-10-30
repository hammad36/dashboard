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