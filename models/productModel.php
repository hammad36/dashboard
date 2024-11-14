<?php

namespace dash\models;

require_once 'abstractModel.php';

use dash\lib\InputFilter;
use dash\lib\alertHandler;

class productModel extends abstractModel
{
    use InputFilter;

    protected $pro_id;
    protected $pro_name;
    protected $pro_description;
    protected $pro_price;
    protected $pro_quantity;

    public static $db;

    protected static $tableName = 'product';
    protected static $tableSchema = [
        'pro_name'         => self::DATA_TYPE_STR,
        'pro_description'  => self::DATA_TYPE_STR,
        'pro_price'        => self::DATA_TYPE_INT,
        'pro_quantity'     => self::DATA_TYPE_INT,
    ];

    protected static $primaryKey = 'pro_id';

    private $alertHandler;

    public function __construct()
    {
        $this->alertHandler = alertHandler::getInstance();
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function setProName($pro_name)
    {
        $filteredName = $this->filterString($pro_name, 1, 255);
        if ($filteredName === null) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Product name cannot be empty.");
        }
        $this->pro_name = $filteredName;
    }

    public function setDescription($pro_description)
    {
        $filteredDescription = $this->filterString($pro_description, 1, 1000);
        if ($filteredDescription === null) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Description must be provided and should not exceed the character limit.");
        }
        $this->pro_description = $filteredDescription;
    }

    public function setProPrice($pro_price)
    {
        $filteredPrice = $this->filterInt($pro_price);
        if ($filteredPrice === null) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Price must be a valid number.");
        }
        $this->pro_price = $filteredPrice;
    }

    public function setProQuantity($pro_quantity)
    {
        $filteredQuantity = $this->filterInt($pro_quantity);
        if ($filteredQuantity === null) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Quantity must be a valid integer.");
        }
        $this->pro_quantity = $filteredQuantity;
    }

    public function getProId()
    {
        return $this->pro_id;
    }

    public function getProName()
    {
        return $this->pro_name;
    }

    public function getProPrice()
    {
        return $this->pro_price;
    }

    public function getProQuantity()
    {
        return $this->pro_quantity;
    }

    public static function getAvailableQuantity($pro_id)
    {
        $sql = "SELECT pro_quantity FROM " . self::$tableName . " WHERE pro_id = :pro_id";
        $result = self::get($sql, ['pro_id' => [self::DATA_TYPE_INT, $pro_id]]);
        return $result ? $result[0]->pro_quantity : null;
    }

    public static function reduceQuantity($pro_id, $quantity)
    {
        $availableQuantity = self::getAvailableQuantity($pro_id);
        if ($availableQuantity !== null && $availableQuantity >= $quantity) {
            $sql = "UPDATE " . self::$tableName . " SET pro_quantity = pro_quantity - :quantity WHERE pro_id = :pro_id";
            return self::executeQuery($sql, ['pro_id' => [self::DATA_TYPE_INT, $pro_id], 'quantity' => [self::DATA_TYPE_INT, $quantity]]);
        }
        return false;
    }
}
