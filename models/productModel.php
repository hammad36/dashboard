<?php

namespace dash\models;

require_once 'abstractModel.php';

class productModel extends abstractModel
{
    protected $pro_id;
    protected $pro_name;
    protected $description;
    protected $pro_price;
    protected $pro_quantity;

    public static $db;

    protected static $tableName = 'product';
    protected static $tableSchema = [
        'pro_name'         => self::DATA_TYPE_STR,
        'description'      => self::DATA_TYPE_STR,
        'pro_price'        => self::DATA_TYPE_DECIMAL,
        'pro_quantity'     => self::DATA_TYPE_INT,
    ];

    protected static $primaryKey = 'pro_id';

    public function __construct() {}

    public function __get($prop)
    {
        return $this->$prop;
    }

    // Setter methods with validation
    public function setProName($pro_name)
    {
        if ($pro_name === null) {
            header("Location: add.view.php?error=Product name cannot be null.");
            exit();
        }
        $this->pro_name = $pro_name;
    }

    public function setDescription($description)
    {
        if ($description === null) {
            header("Location: add.view.php?error=Description cannot be null.");
            exit();
        }
        $this->description = $description;
    }

    public function setProPrice($pro_price)
    {
        if ($pro_price === null || $pro_price <= 0) {
            header("Location: add.view.php?error=Price must be greater than zero.");
            exit();
        }
        $this->pro_price = $pro_price;
    }

    public function setProQuantity($pro_quantity)
    {
        if ($pro_quantity === null || $pro_quantity < 0) {
            header("Location: add.view.php?error=Quantity cannot be negative.");
            exit();
        }
        $this->pro_quantity = $pro_quantity;
    }

    public function getTableName()
    {
        return self::$tableName;
    }
}
