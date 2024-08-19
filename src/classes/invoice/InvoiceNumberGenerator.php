<?php
class invoiceNumberGenerator
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function generateNewInvoiceNumber()
    {
        $invoiceQuery = "SELECT MAX(inv_number) AS max_invoice_number FROM Invoice";
        $invoiceResult = mysqli_query($this->conn, $invoiceQuery);
        $row = mysqli_fetch_assoc($invoiceResult);
        $lastInvoiceNumber = $row['max_invoice_number'];
        return $lastInvoiceNumber ? $lastInvoiceNumber + 1 : 1;
    }
}
