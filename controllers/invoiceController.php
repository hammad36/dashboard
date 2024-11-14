<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\invoiceModel;
use dash\models\productModel;
use dash\lib\alertHandler;
use dash\lib\database\databaseHandler;

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

        $availableQuantities = [];
        foreach ($this->_data['products'] as $product) {
            $availableQuantities[$product->pro_id] = (new invoiceModel())->getAvailableQuantity($product->pro_id);
        }
        $this->_data['availableQuantities'] = $availableQuantities;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleInvoiceForm();
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $invoice = invoiceModel::getByPK($id);

        if (!$invoice) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Invoice not found.");
            return;
        }

        $this->_data['invoice'] = $invoice;
        $this->_data['products'] = productModel::getAll();

        // Fetch the selected products for the invoice
        $invoiceProducts = $invoice->getProducts();
        $selectedProducts = [];
        foreach ($invoiceProducts as $product) {
            $selectedProducts[$product->pro_id] = $product->quantity;
        }
        $this->_data['selectedProducts'] = $selectedProducts;

        // Fetch available quantities for each product
        $availableQuantities = [];
        foreach ($this->_data['products'] as $product) {
            $availableQuantities[$product->pro_id] = (new invoiceModel())->getAvailableQuantity($product->pro_id);
        }
        $this->_data['availableQuantities'] = $availableQuantities;;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditInvoiceForm($invoice);
        }

        $this->_view();
    }

    private function handleEditInvoiceForm($invoice)
    {
        try {
            list($client_name, $client_email, $inv_date, $productData) = $this->validateInvoiceInputs();

            $invoice->setClientName($client_name);
            $invoice->setClientEmail($client_email);
            $invoice->setInvDate($inv_date);
            $invoice->setTotalAmount(array_sum(array_column($productData, 'line_total')));

            if (!$invoice->save()) {
                throw new \Exception('Failed to update the invoice.');
            }

            // Delete old products and save new products
            $invoice->deleteProducts();
            $invoice->saveProducts($productData);

            $this->alertHandler->redirectWithMessage("/invoice", "edit", "Invoice #{$invoice->getInvNumber()} updated successfully.");
        } catch (\Exception $e) {
            $this->alertHandler->redirectWithMessage("/invoice/edit/{$invoice->getInvNumber()}", "error", $e->getMessage());
        }
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $product = invoiceModel::getByPK($id);
        if ($product && $product->delete()) {
            $this->alertHandler->redirectWithMessage("/invoice", "remove", "invoice deleted successfully.");
        } else {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "invoice deletion failed.");
        }
    }

    private function handleInvoiceForm()
    {
        try {
            list($client_name, $client_email, $inv_date, $productData) = $this->validateInvoiceInputs();

            $invoice = $this->createInvoice($client_name, $client_email, $inv_date, $productData);

            $invoice->saveProducts($productData);

            $this->alertHandler->redirectWithMessage("/invoice", "add", "Invoice #{$invoice->getInvNumber()} added successfully.");
        } catch (\Exception $e) {
            $this->alertHandler->redirectWithMessage("/invoice/add", "error", $e->getMessage());
        }
    }

    private function validateInvoiceInputs()
    {
        $client_name = $this->filterString($_POST['client_name'], 1, 255);
        if (!$client_name) {
            throw new \Exception('Client name is required and must be a valid string.');
        }

        $client_email = $this->filterString($_POST['client_email'], 1, 255);
        if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email address format.');
        }

        $inv_date = $this->filterString($_POST['inv_date'], 1, 255);
        if (!$this->validateDateFormat($inv_date)) {
            throw new \Exception('Invalid date format. Use YYYY-MM-DD.');
        }

        $productData = $this->validateProductSelection($_POST['products']);

        if (empty($productData)) {
            throw new \Exception('Please select at least one product to create an invoice.');
        }

        return [$client_name, $client_email, $inv_date, $productData];
    }

    private function validateDateFormat($date)
    {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        return $dateTime && $dateTime->format('Y-m-d') === $date;
    }

    private function validateProductSelection($productIds)
    {
        $productData = [];
        $invoiceTotal = 0;

        foreach ($productIds as $productId => $quantity) {
            $product = productModel::getByPK($productId);
            if ($product && is_numeric($quantity) && $quantity > 0) {
                $availableQuantity = (new invoiceModel())->getAvailableQuantity($productId);

                if ($quantity > $availableQuantity) {
                    throw new \Exception("Selected quantity for {$product->pro_name} exceeds available stock.");
                }

                $line_total = $product->pro_price * $quantity;
                $invoiceTotal += $line_total;

                $productData[] = [
                    'pro_id' => $productId,
                    'quantity' => $quantity,
                    'line_total' => $line_total
                ];
            } else {
                throw new \Exception("Invalid product selection or quantity for product ID: {$productId}");
            }
        }

        if ($invoiceTotal <= 0) {
            throw new \Exception('Total price must be greater than zero.');
        }

        return $productData;
    }

    private function createInvoice($client_name, $client_email, $inv_date, $productData)
    {
        $invoice = new invoiceModel();

        $invoice->setClientName($client_name);
        $invoice->setClientEmail($client_email);
        $invoice->setInvDate($inv_date);
        $invoice->setTotalAmount(array_sum(array_column($productData, 'line_total')));

        if (!$invoice->save()) {
            throw new \Exception('Failed to save the invoice.');
        }

        return $invoice;
    }
}
