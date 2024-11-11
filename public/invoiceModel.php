<?php

namespace dash\models;

use dash\lib\InputFilter;
use dash\lib\alertHandler;
use Exception;

class invoiceModel extends abstractModel
{
    use InputFilter;

    protected $inv_number;
    protected $client_name;
    protected $client_email;
    protected $inv_date;
    protected $total_amount;

    protected static $tableName = 'invoice';
    protected static $tableSchema = [
        'client_name'   => self::DATA_TYPE_STR,
        'client_email'  => self::DATA_TYPE_STR,
        'inv_date'      => self::DATA_TYPE_DATE,
        'total_amount'  => self::DATA_TYPE_INT,
    ];

    protected static $primaryKey = 'inv_number';

    public function saveWithProducts(array $products)
    {
        $connection = self::getConnection();
        $connection->beginTransaction();

        try {
            // Validate and set invoice data
            if (!$this->validateInvoiceData($products)) {
                throw new Exception("Invalid invoice data.");
            }

            // Save the invoice itself
            if (!$this->save()) {
                throw new Exception("Failed to save the invoice.");
            }

            $inv_number = $this->getInvNumber();  // Get the generated invoice number
            $totalAmount = 0;  // Initialize total amount for invoice

            // Insert products and check stock
            foreach ($products as $productId => $quantity) {
                if ($quantity > 0) {
                    $product = productModel::getByPK($productId);
                    if (!$product) {
                        throw new Exception("Product with ID $productId not found.");
                    }

                    // Check if enough stock is available
                    $availableQuantity = $product->getProQuantity();
                    if ($quantity > $availableQuantity) {
                        throw new Exception("Not enough stock for product {$product->getProName()}.");
                    }

                    // Calculate line total and save product details in invoice_product
                    $lineTotal = $product->getProPrice() * $quantity;
                    $totalAmount += $lineTotal;  // Add to total amount of invoice

                    $invoiceProductModel = new invoiceProductModel();
                    $invoiceProductModel->setInvNumber($inv_number);
                    $invoiceProductModel->setProId($productId);
                    $invoiceProductModel->setQuantity($quantity);
                    $invoiceProductModel->setLineTotal($lineTotal);

                    if (!$invoiceProductModel->save()) {
                        throw new Exception("Failed to save product {$product->getProName()} for invoice.");
                    }

                    // Update product stock quantity after the sale
                    $newQuantity = $availableQuantity - $quantity;
                    $product->setProQuantity($newQuantity);
                    if (!$product->save()) {
                        throw new Exception("Failed to update stock for product {$product->getProName()}.");
                    }
                }
            }

            // Update the total amount for the invoice
            $this->setTotalAmount($totalAmount);
            if (!$this->save()) {
                throw new Exception("Failed to update total amount for the invoice.");
            }

            // Commit transaction if all is successful
            $connection->commit();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            $connection->rollBack();
            error_log("Error saving invoice: " . $e->getMessage());
            return false;
        }
    }

    private function validateInvoiceData(array $products)
    {
        if (empty($products)) {
            throw new Exception("At least one product must be selected.");
        }

        // Ensure all product IDs are valid
        foreach ($products as $productId => $quantity) {
            if (!is_numeric($productId) || $quantity <= 0) {
                throw new Exception("Invalid product ID or quantity.");
            }
        }

        return true;
    }

    // Getter methods
    public function getInvNumber()
    {
        return $this->inv_number;
    }

    public function getTableName()
    {
        return self::$tableName;
    }

    // Setter methods
    public function setClientName($client_name)
    {
        $this->client_name = $this->filterString($client_name, 1, 255);
    }

    public function setClientEmail($client_email)
    {
        $this->client_email = $this->filterString($client_email, 1, 255);
    }

    public function setInvDate($inv_date)
    {
        $this->inv_date = $this->filterDate($inv_date);
    }

    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }

    // In invoiceModel class
    public function getClientName()
    {
        return $this->client_name;
    }

    public function getClientEmail()
    {
        return $this->client_email;
    }

    public function getInvDate()
    {
        return $this->inv_date;
    }

    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    // Additional methods for counting, fetching last, etc. can be added similarly.
}
