<?php
class InvoiceController
{
    private $invoiceNumberGenerator;
    private $invoiceValidator;
    private $invoiceCreator;

    public function __construct(InvoiceNumberGenerator $invoiceNumberGenerator, InvoiceValidator $invoiceValidator, InvoiceCreator $invoiceCreator)
    {
        $this->invoiceNumberGenerator = $invoiceNumberGenerator;
        $this->invoiceValidator = $invoiceValidator;
        $this->invoiceCreator = $invoiceCreator;
    }

    public function createInvoice($clientName, $clientEmail, $productIds, $quantities, $prices, $totalAmount)
    {
        $invoiceNumber = $this->invoiceNumberGenerator->generateNewInvoiceNumber();
        $invoiceDate = date('Y-m-d');

        // Validate product quantities
        $this->invoiceValidator->validateQuantities($productIds, $quantities);

        // Create the invoice
        $this->invoiceCreator->createInvoice($invoiceNumber, $clientName, $clientEmail, $invoiceDate, $totalAmount, $productIds, $quantities, $prices);

        header("Location: ilist.php?add=Invoice created successfully");
        exit();
    }
}
