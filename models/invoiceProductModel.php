<?php

namespace dash\models;

require_once 'abstractModel.php';

class invoiceProductModel extends abstractModel
{
    protected $inv_number;
    protected $pro_id;
    protected $quantity;

    protected static $tableName = 'invoice_product';
    protected static $tableSchema = [
        'inv_number'   => self::DATA_TYPE_INT,
        'pro_id'   => self::DATA_TYPE_INT,
        'quantity' => self::DATA_TYPE_INT,
    ];

    protected static $primaryKey = 'id';

    public function __get($prop)
    {
        return $this->$prop;
    }

    // Save the invoice-product relationship
    public function save()
    {
        $sql = 'INSERT INTO ' . self::$tableName . ' (inv_number, pro_id, quantity) VALUES (:inv_number, :pro_id, :quantity)';
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':inv_number', $this->inv_number, self::DATA_TYPE_INT);
        $stmt->bindValue(':pro_id', $this->pro_id, self::DATA_TYPE_INT);
        $stmt->bindValue(':quantity', $this->quantity, self::DATA_TYPE_INT);
        return $stmt->execute();
    }
}
