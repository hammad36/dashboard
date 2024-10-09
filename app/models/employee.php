<?php
require_once('abstractModel.php');
class employee extends abstractModel
{
    private $id;
    private $empName;
    private $empAge;
    private $empAddress;
    private $empSalary;
    private $empTax;

    public static $db;

    protected static $tableName = 'employees';
    protected static $tableSchema = [
        'empName'       => self::DATA_TYPE_STR,
        'empAge'        => self::DATA_TYPE_INT,
        'empAddress'    => self::DATA_TYPE_STR,
        'empSalary'     => self::DATA_TYPE_DECIMAL,
        'empTax'        => self::DATA_TYPE_DECIMAL
    ];

    protected static $primaryKey = 'id';


    public function __construct($empName, $empAge, $empAddress, $empTax, $empSalary)
    {
        global $connection;

        $this->empName = $empName;
        $this->empAge = $empAge;
        $this->empAddress = $empAddress;
        $this->empSalary = $empSalary;
        $this->empTax = $empTax;

        self::$db = $connection;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function setName($empName)
    {
        $this->empName = $empName;
    }

    public function calculateSalary()
    {
        return $this->empSalary - ($this->empSalary * $this->empTax / 100);
    }

    public function fireEmployee() {}

    public function promoteEmployee() {}

    public function getTableName()
    {
        return self::$tableName;
    }
}
