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