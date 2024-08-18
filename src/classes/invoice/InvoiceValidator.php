<?php
class InvoiceValidator
{
    private $productRetrieve;

    public function __construct(productRetrieve $productRetrieve)
    {
        $this->productRetrieve = $productRetrieve;
    }

    public function validateQuantities($productIds, $quantities)
    {
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index];
            $productRow = $this->productRetrieve->getProductQuantity($productId);
            $availableQuantity = $productRow['pro_quantity'] - $productRow['total_sold'];

            if ($quantity > $availableQuantity) {
                throw new Exception("Quantity for product ID $productId exceeds available stock.");
            }
        }
    }
}
