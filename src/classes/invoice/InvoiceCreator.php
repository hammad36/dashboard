<?php
class invoiceCreator
{
    private $conn;
    private $productUpdate;

    public function __construct($conn, productUpdate $productUpdate)
    {
        $this->conn = $conn;
        $this->productUpdate = $productUpdate;
    }

    public function createInvoice($invoiceNumber, $clientName, $clientEmail, $invoiceDate, $totalAmount, $productIds, $quantities, $prices)
    {
        mysqli_begin_transaction($this->conn);

        try {
            // Insert into Invoice table
            $insertInvoiceQuery = "INSERT INTO Invoice (inv_number, client_name, client_email, inv_date, total_amount)
                                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($insertInvoiceQuery);
            $stmt->bind_param("isssd", $invoiceNumber, $clientName, $clientEmail, $invoiceDate, $totalAmount);
            if (!$stmt->execute()) {
                throw new Exception('Failed to insert invoice: ' . mysqli_error($this->conn));
            }

            // Insert into Invoice_Product table and update product quantity
            foreach ($productIds as $index => $productId) {
                $quantity = $quantities[$index];
                $price = $prices[$index];
                $lineTotal = $quantity * $price;

                $insertInvoiceProductQuery = "INSERT INTO Invoice_Product (inv_number, pro_id, quantity, line_total)
                                                VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insertInvoiceProductQuery);
                $stmt->bind_param("iiid", $invoiceNumber, $productId, $quantity, $lineTotal);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to insert invoice product: ' . mysqli_error($this->conn));
                }
            }

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }
}
