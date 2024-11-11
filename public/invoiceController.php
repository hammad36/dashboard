<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\invoiceModel;
use dash\models\invoiceProductModel;
use dash\lib\alertHandler;
use dash\models\productModel;
use Exception;

class invoiceController extends abstractController
{
    use InputFilter;

    private $alertHandler;

    public function __construct()
    {
        $this->alertHandler = alertHandler::getInstance();
        parent::__construct();  // Ensure the parent constructor is called to initialize any necessary database connection or dependencies
    }

    public function defaultAction()
    {
        $this->_data['invoices'] = invoiceModel::getAll();
        $this->_view();
    }

    public function addAction()
    {
        $this->_data['products'] = productModel::getAll();

        if (isset($_POST['submit'])) {
            try {
                $invoice = new invoiceModel();
                $this->processInvoice($invoice, "Invoice added successfully.", "/invoice", "add");
            } catch (Exception $e) {
                $this->alertHandler->redirectWithMessage("/invoice", "add", $e->getMessage());
            }
        }

        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $invoice = invoiceModel::getByPK($id);

        if (!$invoice) {
            $this->alertHandler->redirectWithMessage("/invoice", "default", "Invoice not found.");
            return;
        }

        $this->_data['invoice'] = $invoice;
        $this->_data['products'] = productModel::getAll();
        $this->_data['invoice_products'] = invoiceProductModel::getByPK($id);

        if (isset($_POST['submit'])) {
            try {
                $this->processInvoice($invoice, "Invoice updated successfully.", "/invoice", "edit");
            } catch (Exception $e) {
                $this->alertHandler->redirectWithMessage("/invoice/edit/{$id}", "default", $e->getMessage() ?: "Try again");
            }
        }

        $this->_view();
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $invoice = invoiceModel::getByPK($id);

        if ($invoice && $invoice->delete()) {
            $this->alertHandler->redirectWithMessage("/invoice", "default", "Invoice deleted successfully.");
        } else {
            $this->alertHandler->redirectWithMessage("/invoice", "default", "Invoice deletion failed.");
        }
    }

    private function processInvoice($invoice, $successMessage, $redirectPath, $alertType)
    {
        try {
            list($client_name, $client_email, $inv_date, $products) = $this->validateInvoiceInputs();

            if (empty($products)) {
                throw new Exception("At least one product must be selected.");
            }

            // Set invoice fields
            $invoice->setClientName($client_name);
            $invoice->setClientEmail($client_email);
            $invoice->setInvDate($inv_date);

            // Start a transaction for invoice and product saving
            $connection = invoiceModel::getConnection(); // Retrieve connection from model
            $connection->beginTransaction();

            // Save the invoice
            if (!$invoice->save()) {
                throw new Exception("Failed to save the invoice.");
            }

            // Save the products associated with the invoice
            $this->saveInvoiceProducts($invoice, $products);

            // Commit the transaction if everything is successful
            $connection->commit();
            $this->alertHandler->redirectWithMessage("/invoice", $alertType, $successMessage);
        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            $connection->rollBack();
            $this->alertHandler->redirectWithMessage($redirectPath, "default", $e->getMessage());
        }
    }

    private function saveInvoiceProducts($invoice, $products)
    {
        foreach ($products as $productId => $quantity) {
            $product = productModel::getByPK($productId);

            if (!$product || $product->getProQuantity() < $quantity) {
                throw new Exception("Invalid product quantity for product ID: {$productId}.");
            }

            // Save each product for the invoice
            $invoiceProduct = new invoiceProductModel();
            $invoiceProduct->setInvNumber($invoice->getInvNumber());
            $invoiceProduct->setProId($productId);
            $invoiceProduct->setQuantity($quantity);
            $invoiceProduct->setLineTotal($product->getProPrice() * $quantity);

            if (!$invoiceProduct->save()) {
                throw new Exception("Failed to associate product ID: {$productId} with the invoice.");
            }

            // Update the product's available quantity
            $product->setProQuantity($product->getProQuantity() - $quantity);
            $product->save();
        }
    }

    private function validateInvoiceInputs()
    {
        $client_name = $this->filterString($_POST['client_name'], 1, 255);
        $client_email = $this->filterString($_POST['client_email'], 1, 255);
        $inv_date = $this->filterDate($_POST['inv_date']);
        $products = $_POST['products'] ?? []; // Expected format: [product_id => quantity]

        // Validate inputs
        if (empty($client_name) || empty($client_email)) {
            throw new Exception("Client name and email are required.");
        }

        if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address.");
        }

        return [$client_name, $client_email, $inv_date, $products];
    }
}
