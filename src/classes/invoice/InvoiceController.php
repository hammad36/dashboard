<?php

class invoiceController
{
    private $invoiceNumberGenerator;
    private $invoiceValidator;
    private $invoiceCreator;

    public function __construct(invoiceNumberGenerator $invoiceNumberGenerator, invoiceValidator $invoiceValidator, invoiceCreator $invoiceCreator)
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
