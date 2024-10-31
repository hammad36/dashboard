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

    // Create alertHandler instance
    private $alertHandler;

    public function __construct()
    {
        $this->alertHandler = alertHandler::getInstance();
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    // Setter methods with input filtering and alert handling
    public function setProName($pro_name)
    {
        $filteredName = $this->filterString($pro_name, 1, 255);
        if ($filteredName === null) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Product name cannot be empty.");
        }
        $this->pro_name = $filteredName;
    }

    public function setDescription($description)
    {
        $filteredDescription = $this->filterString($description, 1, 150);
        if ($filteredDescription === null) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Description must be provided and should not exceed the character limit.");
        }
        $this->description = $filteredDescription;
    }

    public function setProPrice($pro_price)
    {
        $filteredPrice = $this->filterFloat($pro_price);
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

    // Getter methods
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
}
