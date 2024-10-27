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

    // Setter methods with validation and improved alert messages
    public function setProName($pro_name)
    {
        if ($pro_name === null) {
            $this->redirectWithError('Product name cannot be empty. Please provide a valid name.');
        }
        $this->pro_name = $pro_name;
    }

    public function setDescription($description)
    {
        if ($description === null) {
            $this->redirectWithError('Description is required. Please provide a valid product description.');
        }
        $this->description = $description;
    }

    public function setProPrice($pro_price)
    {
        if ($pro_price === null || $pro_price <= 0) {
            $this->redirectWithError('Price must be a positive number greater than zero. Please enter a valid price.');
        }
        $this->pro_price = $pro_price;
    }

    public function setProQuantity($pro_quantity)
    {
        if ($pro_quantity === null || $pro_quantity < 0) {
            $this->redirectWithError('Quantity cannot be negative. Please enter a valid quantity.');
        }
        $this->pro_quantity = $pro_quantity;
    }

    public function getProName()
    {
        return $this->pro_name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getProPrice()
    {
        return $this->pro_price;
    }

    public function getProQuantity()
    {
        return $this->pro_quantity;
    }


    public function getTableName()
    {
        return self::$tableName;
    }

    // Helper function to handle errors and redirect
    private function redirectWithError($message)
    {
        header("Location: add?error=" . urlencode($message));
        exit();
    }
}
