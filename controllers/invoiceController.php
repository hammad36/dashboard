<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\invoiceModel;
use dash\lib\alertHandler;

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
        if (isset($_POST['submit'])) {
            $this->handleInvoiceForm(new invoiceModel(), "Invoice added successfully.", "add", "add");
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $invoice = invoiceModel::getByPK($id);

        if ($invoice === false) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Please re-enter valid values and try again.");
        }

        $this->_data['invoice'] = $invoice;

        if (isset($_POST['submit'])) {
            $this->handleInvoiceForm($invoice, "Invoice updated successfully.", "edit", "edit");
        }

        $this->_view();
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $invoice = invoiceModel::getByPK($id);
        if ($invoice && $invoice->delete()) {
            $this->alertHandler->redirectWithMessage("/invoice", "remove", "Invoice deleted successfully.");
        } else {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Invoice deletion failed.");
        }
    }

    private function handleInvoiceForm($invoice, $successMessage, $redirectPath, $alertType)
    {
        try {
            list($inv_number, $client_name, $client_email, $inv_date, $total_amount) = $this->validateInvoiceInputs();

            // Set invoice fields
            $invoice->inv_number = $inv_number;
            $invoice->client_name = $client_name;
            $invoice->client_email = $client_email;
            $invoice->inv_date = $inv_date;
            $invoice->total_amount = $total_amount;

            // Save the updated invoice to the database
            if ($invoice->save()) {
                $this->alertHandler->redirectWithMessage("/invoice", $alertType, $successMessage);
            }
        } catch (\Exception $e) {
            $this->alertHandler->redirectWithMessage($redirectPath, "error", "Please re-enter valid values and try again.");
        }
    }

    private function validateInvoiceInputs()
    {
        $inv_number = $this->filterInt($_POST['inv_number']);
        $client_name = $this->filterString($_POST['client_name'], 1, 255);
        $client_email = $this->filterString($_POST['client_email'], 1, 255);
        $inv_date = $this->filterString($_POST['inv_date']);  // Adjust as needed for date format
        $total_amount = $this->filterFloat($_POST['total_amount']);

        if (!$inv_number || !$client_name || !$client_email || !$inv_date || !$total_amount) {
            throw new \Exception('Invalid input');
        }

        return [$inv_number, $client_name, $client_email, $inv_date, $total_amount];
    }
}
