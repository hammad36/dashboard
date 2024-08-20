<?php
include "../../../DB/Connection.php";
include_once "invoiceUpdater.php";
include_once "../product/productValidator.php";
include_once "invoiceFetcher.php";

class invoiceUpdaterController
{
    private $conn;
    private $invoiceUpdater;
    private $invoiceFetcher;

    public function __construct()
    {
        $dbConnection = Connection::getInstance();
        $this->conn = $dbConnection->getConnection();
        $productValidator = new productValidator($this->conn);
        $this->invoiceUpdater = new invoiceUpdater($this->conn, $productValidator);
        $this->invoiceFetcher = new invoiceFetcher($this->conn);
    }

    public function handleRequest()
    {
        $id = $_GET['inv_number'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $invoiceNumber = $_POST['invoiceNumber'];
            $invoiceDate = date('Y-m-d');
            $clientName = $_POST['clientName'];
            $clientEmail = $_POST['clientEmail'];
            $productIds = $_POST['productSelect'];
            $quantities = $_POST['quantity'];
            $prices = $_POST['price'];
            $totalPrice = $_POST['totalPrice'];

            $message = $this->invoiceUpdater->updateInvoice($invoiceNumber, $invoiceDate, $clientName, $clientEmail, $productIds, $quantities, $prices, $totalPrice);
            header("Location: ilist.php?edit=$message");
            exit();
        }

        $invoice = $this->invoiceFetcher->fetchInvoiceDetails($id);
        $products = $this->invoiceFetcher->fetchProducts();
        $existingProducts = $this->invoiceFetcher->fetchExistingProducts($id);
    }
}

$controller = new invoiceUpdaterController();
$controller->handleRequest();
