<?php
class invoiceValidator
{
    private $productRetrieve;

    public function __construct(productRetrieve $productRetrieve)
    {
        $this->productRetrieve = $productRetrieve;
    }

    public function validateQuantities($productSelect, $quantities)
    {
        foreach ($productSelect as $index => $productId) {
            $quantity = $quantities[$index];
            $productRow = $this->productRetrieve->getProductQuantity($productId);
            $availableQuantity = $productRow['pro_quantity'] - $productRow['total_sold'];

            if ($quantity > $availableQuantity) {
                throw new Exception("Quantity for product ID $productId exceeds available stock.");
            }
        }
    }
    public function validatePrices($productSelect, $prices)
    {
        foreach ($productSelect as $index => $productId) {
            $submittedPrice = $prices[$index];
            $productRow = $this->productRetrieve->getProductPrice($productId);
            $actualPrice = $productRow['pro_price'];

            if ($submittedPrice != $actualPrice) {
                throw new Exception("Price mismatch for product ID $productId.");
            }
        }
    }
}
