<?php

namespace dash\models;

require_once 'abstractModel.php';

class invoiceModel extends abstractModel
{
    private $inv_number;
    private $client_name;
    private $client_email;
    private $inv_date;
    private $total_amount;

    public static $db;

    protected static $tableName = 'invoice';
    protected static $tableSchema = [
        'inv_number'         => self::DATA_TYPE_INT,
        'client_name'         => self::DATA_TYPE_STR,
        'client_email'      => self::DATA_TYPE_STR,
        'inv_date'        => self::DATA_TYPE_DATE,
        'total_amount'     => self::DATA_TYPE_DECIMAL,
    ];

    protected static $primaryKey = 'inv_number';


    public function __construct($inv_number, $client_name, $client_email, $inv_date, $total_amount)
    {
        global $connection;

        $this->inv_number = $inv_number;
        $this->client_name = $client_name;
        $this->client_email = $client_email;
        $this->inv_date = $inv_date;
        $this->total_amount = $total_amount;

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

    public function clientName($client_name)
    {
        $this->client_name = $client_name;
    }
}
