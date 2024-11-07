document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-id');
        document.getElementById('confirmDeleteLink').setAttribute('href', `/product/delete/${productId}`);
    });
});