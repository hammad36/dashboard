<?php

namespace dash\models;

use dash\lib\database\databaseHandler;

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

    // Getter and setter methods
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

    public function saveProducts($productData)
    {
        $db = databaseHandler::factory();
        $inv_number = $db->lastInsertId(); // Assuming the invoice was already inserted

        // Begin transaction
        $db->beginTransaction();
        try {
            // Loop through each product and insert it into the invoice_product table
            foreach ($productData as $product) {
                $query = "INSERT INTO invoice_product (inv_number, pro_id, quantity, line_total) 
                          VALUES (?, ?, ?, ?)";
                $stmt = $db->prepare($query); // Prepare the query
                $stmt->execute([   // Execute the prepared statement with parameters
                    $inv_number,
                    $product['pro_id'],
                    $product['quantity'],
                    $product['line_total']
                ]);
            }

            // Commit the transaction if all inserts were successful
            $db->commit();
        } catch (\Exception $e) {
            // Rollback the transaction if there is an error
            $db->rollBack();
            throw $e;  // Rethrow the exception after rolling back
        }
    }



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

    public function save()
    {
        return $this->getInvNumber() === null ? $this->create() : $this->update();
    }
}
