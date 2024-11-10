<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\invoiceModel;
use dash\models\invoiceProductModel;
use dash\lib\alertHandler;
use dash\models\productModel;

class invoiceController extends abstractController
{
    use InputFilter;

    private $alertHandler;

    public function __construct()
    {
        $this->alertHandler = alertHandler::getInstance();
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
            $this->handleInvoiceForm(new invoiceModel(), "Invoice added successfully.", "default", "add");
        }
        $this->_view();
    }



    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $invoice = invoiceModel::getByPK($id);

        if ($invoice === false) {
            $this->alertHandler->redirectWithMessage("/invoice", "default", "Please re-enter valid values and try again.");
        }

        // Fetch related products for the invoice
        $this->_data['invoice'] = $invoice;
        $this->_data['invoice_products'] = invoiceProductModel::getByPK($id);

        if (isset($_POST['submit'])) {
            $this->handleInvoiceForm($invoice, "Invoice updated successfully.", "default", "edit");
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

    private function handleInvoiceForm($invoice, $successMessage, $redirectPath, $alertType)
    {
        try {
            list($client_name, $client_email, $inv_date, $products) = $this->validateInvoiceInputs();

            // Set invoice fields
            $invoice->setClientName($client_name);
            $invoice->setClientEmail($client_email);
            $invoice->setInvDate($inv_date);

            // Save the invoice
            if ($invoice->save()) {
                // Link products to the invoice
                $this->saveInvoiceProducts($invoice->getInvNumber(), $products);

                $this->alertHandler->redirectWithMessage("/invoice", $alertType, $successMessage);
            }
        } catch (\Exception $e) {
            $this->alertHandler->redirectWithMessage($redirectPath, "default", "Please re-enter valid values and try again.");
        }
    }

    private function saveInvoiceProducts($inv_number, $products)
    {
        invoiceProductModel::delete($inv_number); // Clear old entries for edits

        foreach ($products as $product_id => $quantity) {
            $line_total = $quantity * $this->getProductPrice($product_id); // Calculate line total
            $invoiceProduct = new invoiceProductModel();
            $invoiceProduct->setInvoiceNumber($inv_number);
            $invoiceProduct->setProductId($product_id);
            $invoiceProduct->setQuantity($quantity);
            $invoiceProduct->setLineTotal($line_total);
            $invoiceProduct->save();
        }
    }

    private function validateInvoiceInputs()
    {
        $client_name = $this->filterString($_POST['client_name'], 1, 255);
        $client_email = $this->filterString($_POST['client_email'], 1, 255);
        $inv_date = $this->filterDate($_POST['inv_date']);
        $products = $_POST['products'] ?? []; // Expected as an associative array [product_id => quantity]

        if (!$client_name || !$client_email || !$inv_date || empty($products)) {
            throw new \Exception('Invalid input');
        }

        return [$client_name, $client_email, $inv_date, $products];
    }

    private function getProductPrice($product_id)
    {
        $product = productModel::getByPK($product_id);
        return $product ? $product->getProPrice() : 0;
    }
}
