<?php

namespace dash\models;


namespace dash\models;

use dash\lib\database\databaseHandler;

class invoiceProductModel extends abstractModel
{
    protected $inv_number;
    protected $pro_id;
    protected $quantity;
    protected $line_total;

    protected static $tableName = 'invoice_product';
    protected static $tableSchema = [
        'inv_number'   => self::DATA_TYPE_INT,
        'pro_id'       => self::DATA_TYPE_INT,
        'quantity'     => self::DATA_TYPE_INT,
        'line_total'   => self::DATA_TYPE_DECIMAL,
    ];

    protected static $primaryKey = 'id';

    public function __get($prop)
    {
        return $this->$prop;
    }

    // Setter methods for better handling of object state
    public function setInvoiceNumber($inv_number)
    {
        $this->inv_number = $inv_number;
    }

    public function setProductId($pro_id)
    {
        $this->pro_id = $pro_id;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function setLineTotal($line_total)
    {
        $this->line_total = $line_total;
    }

    // Calculate line total
    public function calculateLineTotal($productPrice)
    {
        $this->line_total = $productPrice * $this->quantity;
    }

    // Save the invoice product (create or update)
    public function save()
    {
        // Insert or Update based on primary key
        return $this->{static::$primaryKey} === null ? $this->create() : $this->update();
    }

    // Override for create logic (to support insert)
    protected function create()
    {
        $sql = 'INSERT INTO ' . static::$tableName . ' (inv_number, pro_id, quantity, line_total) VALUES (:inv_number, :pro_id, :quantity, :line_total)';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->inv_number, self::DATA_TYPE_INT);
        $stmt->bindValue(':pro_id', $this->pro_id, self::DATA_TYPE_INT);
        $stmt->bindValue(':quantity', $this->quantity, self::DATA_TYPE_INT);
        $stmt->bindValue(':line_total', $this->line_total, self::DATA_TYPE_DECIMAL);
        return $stmt->execute();
    }

    // Override for update logic (to support updates)
    protected function update()
    {
        $sql = 'UPDATE ' . static::$tableName . ' SET quantity = :quantity, line_total = :line_total WHERE inv_number = :inv_number AND pro_id = :pro_id';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':quantity', $this->quantity, self::DATA_TYPE_INT);
        $stmt->bindValue(':line_total', $this->line_total, self::DATA_TYPE_DECIMAL);
        $stmt->bindValue(':inv_number', $this->inv_number, self::DATA_TYPE_INT);
        $stmt->bindValue(':pro_id', $this->pro_id, self::DATA_TYPE_INT);
        return $stmt->execute();
    }

    // Delete product entries for a specific invoice
    public static function deleteByInvoice($inv_number)
    {
        $sql = 'DELETE FROM ' . static::$tableName . ' WHERE inv_number = :inv_number';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':inv_number', $inv_number, self::DATA_TYPE_INT);
        return $stmt->execute();
    }

    // Fetch invoice products by invoice number (overriding the method in abstractModel)
    public static function getByInvoice($inv_number)
    {
        return self::executeWithConnection(function ($connection) use ($inv_number) {
            $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE inv_number = :inv_number';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':inv_number', $inv_number, self::DATA_TYPE_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());
        });
    }

    // Override delete method to include extra validation
    public function delete()
    {
        $sql = 'DELETE FROM ' . static::$tableName . ' WHERE inv_number = :inv_number AND pro_id = :pro_id';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->inv_number, self::DATA_TYPE_INT);
        $stmt->bindValue(':pro_id', $this->pro_id, self::DATA_TYPE_INT);
        return $stmt->execute();
    }

    // Get count of products for a specific invoice
    public static function countByInvoice($inv_number)
    {
        return self::executeWithConnection(function ($connection) use ($inv_number) {
            $sql = 'SELECT COUNT(*) AS count FROM ' . static::$tableName . ' WHERE inv_number = :inv_number';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':inv_number', $inv_number, self::DATA_TYPE_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result ? $result['count'] : 0;
        });
    }
}
