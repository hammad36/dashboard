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
        $inv_number = $this->getInvNumber();

        $db->beginTransaction();
        try {
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

            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
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

    public function getProducts()
    {
        $sql = "SELECT p.pro_id, p.pro_name, p.pro_price, ip.quantity, ip.line_total 
            FROM product p
            JOIN invoice_product ip ON p.pro_id = ip.pro_id
            WHERE ip.inv_number = :inv_number";

        $stmt = databaseHandler::factory()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->getInvNumber(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteProducts()
    {
        $sql = "DELETE FROM invoice_product WHERE inv_number = :inv_number";
        $stmt = databaseHandler::factory()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->getInvNumber(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getAvailableQuantity($productId)
    {
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

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['available_quantity'] : 0;
    }

    public function save()
    {
        return $this->getInvNumber() === null ? $this->create() : $this->update();
    }
}
