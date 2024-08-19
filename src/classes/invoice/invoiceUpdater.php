<?php
class InvoiceUpdater
{
    private $conn;
    private $productValidator;

    public function __construct($conn, productValidator $productValidator)
    {
        $this->conn = $conn;
        $this->productValidator = $productValidator;
    }

    public function updateInvoice($invoiceNumber, $invoiceDate, $clientName, $clientEmail, $productIds, $quantities, $prices, $totalPrice)
    {
        mysqli_begin_transaction($this->conn);

        try {
            $this->productValidator->validate($productIds, $quantities);

            $this->updateInvoiceTable($invoiceNumber, $invoiceDate, $clientName, $clientEmail, $totalPrice);

            $this->deleteExistingProducts($invoiceNumber);

            $this->insertInvoiceProducts($invoiceNumber, $productIds, $quantities, $prices);

            mysqli_commit($this->conn);
            return "Data Updated successfully";
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return 'Failed to update invoice: ' . $e->getMessage();
        }
    }

    private function updateInvoiceTable($invoiceNumber, $invoiceDate, $clientName, $clientEmail, $totalPrice)
    {
        $sql = "UPDATE `Invoice` SET `inv_date`='$invoiceDate', `client_name`='$clientName', `client_email`='$clientEmail',
                `total_amount`='$totalPrice' WHERE inv_number='$invoiceNumber'";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            throw new Exception('Failed to update invoice: ' . mysqli_error($this->conn));
        }
    }

    private function deleteExistingProducts($invoiceNumber)
    {
        $deleteInvoiceProductQuery = "DELETE FROM `Invoice_Product` WHERE inv_number='$invoiceNumber'";
        $deleteInvoiceProductResult = mysqli_query($this->conn, $deleteInvoiceProductQuery);
        if (!$deleteInvoiceProductResult) {
            throw new Exception('Failed to delete existing invoice products: ' . mysqli_error($this->conn));
        }
    }

    private function insertInvoiceProducts($invoiceNumber, $productIds, $quantities, $prices)
    {
        foreach ($productIds as $index => $productId) {
            $quantity = $quantities[$index];
            $price = $prices[$index];
            $lineTotal = $quantity * $price;

            $insertInvoiceProductQuery = "INSERT INTO `Invoice_Product` (inv_number, pro_id, quantity, line_total)
            VALUES ('$invoiceNumber', '$productId', '$quantity', '$lineTotal')";
            $insertInvoiceProductResult = mysqli_query($this->conn, $insertInvoiceProductQuery);
            if (!$insertInvoiceProductResult) {
                throw new Exception('Failed to insert updated invoice product: ' . mysqli_error($this->conn));
            }
        }
    }
}
