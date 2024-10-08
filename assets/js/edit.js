document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('productSelect');
    const productDetails = document.getElementById('productDetails');
    const totalPriceInput = document.getElementById('totalPrice');

    productSelect.addEventListener('change', function() {
        updateProductDetails();
    });

    function updateProductDetails() {
        const selectedOptions = Array.from(productSelect.selectedOptions);
        productDetails.innerHTML = '';

        let totalPrice = 0;

        selectedOptions.forEach(option => {
            const productId = option.value;
            const productName = option.text;
            const productPrice = parseFloat(option.getAttribute('data-price'));
            const availableQuantity = parseInt(option.getAttribute('data-quantity'));
            const existingQuantity = parseInt(option.getAttribute('data-existing-quantity')) || 1; // Default to 1 if no existing quantity

            const container = document.createElement('div');
            container.classList.add('row');

            container.innerHTML = `
                <div class="column">
                    <label for="quantity_${productId}">${productName} Quantity (Available: ${availableQuantity})</label>
                    <input type="number" id="quantity_${productId}" name="quantity[]" data-price="${productPrice}" min="1" max="${availableQuantity}" value="${existingQuantity}" required>
                </div>
                <div class="column">
                    <label for="price_${productId}">${productName} Price</label>
                    <input type="number" id="price_${productId}" name="price[]" value="${productPrice.toFixed(2)}" step="0.01" readonly>
                </div>
            `;

            productDetails.appendChild(container);

            const quantityInput = container.querySelector(`#quantity_${productId}`);
            quantityInput.addEventListener('input', function() {
                updateTotalPrice();
            });

            totalPrice += existingQuantity * productPrice;
        });

        updateTotalPrice();
    }

    function updateTotalPrice() {
        let totalPrice = 0;
        const quantities = document.querySelectorAll('[name="quantity[]"]');
        const prices = document.querySelectorAll('[name="price[]"]');

        quantities.forEach((quantityInput, index) => {
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(prices[index].value) || 0;
            totalPrice += quantity * price;
        });

        totalPriceInput.value = totalPrice.toFixed(2);
    }

    document.getElementById('invoiceForm').addEventListener('submit', function(event) {
        const products = document.querySelectorAll('#productDetails input[name^="quantity"]');
        products.forEach(function(product) {
            const quantity = parseInt(product.value, 10);
            const maxQuantity = parseInt(product.max, 10);

            if (quantity < 1 || quantity > maxQuantity) {
                alert('Invalid quantity for product: ' + product.id);
                event.preventDefault(); 
            }
        });
    });

    updateProductDetails();
});
