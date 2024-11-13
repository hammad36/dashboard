<?php

namespace dash\models;

use dash\lib\database\databaseHandler;
use PDO;

class invoiceModel extends abstractModel
{
    protected $inv_number;
    protected $client_name;
    protected $client_email;
    protected $inv_date;
    protected $total_amount;

    protected static $tableName = 'invoice';
    protected static $primaryKey = 'inv_number';
    protected static $tableSchema = [
        'inv_number'   => self::DATA_TYPE_INT,
        'client_name'  => self::DATA_TYPE_STR,
        'client_email' => self::DATA_TYPE_STR,
        'inv_date'     => self::DATA_TYPE_DATE,
        'total_amount' => self::DATA_TYPE_DECIMAL
    ];

    // Getter and setter methods for invoice properties...
    public function setInvNumber($inv_number)
    {
        $this->inv_number = $inv_number;
    }

    public function getInvNumber()
    {
        return $this->inv_number;
    }

    public function setClientName($client_name)
    {
        $this->client_name = $client_name;
    }

    public function setClientEmail($client_email)
    {
        $this->client_email = $client_email;
    }

    public function setInvDate($inv_date)
    {
        $this->inv_date = $inv_date;
    }

    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;
    }

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

    // Save product data related to this invoice
    public function saveProducts($productData)
    {
        $db = databaseHandler::factory();
        $inv_number = $this->getInvNumber(); // Use existing inv_number

        // Begin transaction
        $db->beginTransaction();
        try {
            // Loop through each product and insert it into the invoice_product table
            foreach ($productData as $product) {
                $query = "INSERT INTO invoice_product (inv_number, pro_id, quantity, line_total) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->execute([
                    $inv_number,
                    $product['pro_id'],
                    $product['quantity'],
                    $product['line_total']
                ]);
            }

            // Commit the transaction if all inserts were successful
            $db->commit();
        } catch (\Exception $e) {
            // Rollback if there’s an error
            $db->rollBack();
            throw $e;
        }
    }


    // Calculate total amount of an invoice based on the product line totals
    public function calculateTotalAmount($productData)
    {
        $totalAmount = 0;
        foreach ($productData as $product) {
            if (isset($product['line_total']) && is_numeric($product['line_total'])) {
                $totalAmount += $product['line_total'];
            }
        }
        return $totalAmount;
    }

    // Fetch products associated with this invoice
    public function getProducts()
    {
        $sql = "SELECT p.pro_id, p.pro_name, p.pro_price, ip.quantity, ip.line_total 
            FROM product p
            JOIN invoice_product ip ON p.pro_id = ip.pro_id
            WHERE ip.inv_number = :inv_number";

        $stmt = databaseHandler::factory()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->getInvNumber(), PDO::PARAM_INT);
        $stmt->execute();

        // Fetch all results as objects, not arrays
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Add this method to your invoiceModel class
    public function deleteProducts()
    {
        // SQL query to delete all product entries linked to this invoice
        $sql = "DELETE FROM invoice_product WHERE inv_number = :inv_number";
        $stmt = databaseHandler::factory()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->getInvNumber(), PDO::PARAM_INT);

        // Execute the statement and return true if successful
        return $stmt->execute();
    }

    // Get the available quantity for each product
    public function getAvailableQuantity($productId)
    {
        // SQL query to calculate available quantity for a specific product
        $sql = "
            SELECT p.pro_quantity - COALESCE(SUM(ip.quantity), 0) AS available_quantity
            FROM product p
            LEFT JOIN invoice_product ip ON p.pro_id = ip.pro_id
            WHERE p.pro_id = :pro_id
            GROUP BY p.pro_id
        ";

        $stmt = databaseHandler::factory()->prepare($sql);
        $stmt->bindValue(':pro_id', $productId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result and return the available quantity
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['available_quantity'] : 0;
    }

    // Save the invoice (either create or update)
    public function save()
    {
        return $this->getInvNumber() === null ? $this->create() : $this->update();
    }
}
