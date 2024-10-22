<?php
require_once 'abstractModel.php';

class product extends abstractModel
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


    public function __construct($pro_name, $description, $pro_price, $pro_quantity)
    {
        global $connection;

        $this->pro_name = $pro_name;
        $this->description = $description;
        $this->pro_price = $pro_price;
        $this->pro_quantity = $pro_quantity;

        self::$db = $connection;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }


    public function getTableName()
    {
        return self::$tableName;
    }
}

$p1 = new product('iphone', 'FSFUL', 3600, 44);
$p1->save();

echo '<pre>';
var_dump($p1);
echo '</pre>';
