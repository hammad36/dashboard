<?php

namespace dash\models;

require_once 'abstractModel.php';

use dash\lib\InputFilter;
use dash\lib\alertHandler;

class invoiceModel extends abstractModel
{
    use InputFilter;

    protected $inv_number;
    protected $client_name;
    protected $client_email;
    protected $inv_date;
    protected $total_amount;

    protected static $tableName = 'invoice';
    protected static $tableSchema = [
        'inv_number'    => self::DATA_TYPE_INT,
        'client_name'   => self::DATA_TYPE_STR,
        'client_email'  => self::DATA_TYPE_STR,
        'inv_date'      => self::DATA_TYPE_DATE,
        'total_amount'  => self::DATA_TYPE_DECIMAL,
    ];

    protected static $primaryKey = 'inv_number';

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
    public function setInvNumber($inv_number)
    {
        $filteredNumber = $this->filterInt($inv_number);
        if ($filteredNumber === null) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Invoice number must be a valid integer.");
        }
        $this->inv_number = $filteredNumber;
    }

    public function setClientName($client_name)
    {
        $filteredName = $this->filterString($client_name, 1, 255);
        if ($filteredName === null) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Client name cannot be empty.");
        }
        $this->client_name = $filteredName;
    }

    public function setClientEmail($client_email)
    {
        $filteredEmail = $this->filterString($client_email, 1, 255);
        if ($filteredEmail === null) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Client email must be provided.");
        }
        $this->client_email = $filteredEmail;
    }

    public function setInvDate($inv_date)
    {
        $filteredDate = $this->filterString($inv_date);
        if ($filteredDate === null) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Invoice date must be a valid date.");
        }
        $this->inv_date = $filteredDate;
    }

    public function setTotalAmount($total_amount)
    {
        $filteredAmount = $this->filterFloat($total_amount);
        if ($filteredAmount === null) {
            $this->alertHandler->redirectWithMessage("/invoice", "error", "Total amount must be a valid number.");
        }
        $this->total_amount = $filteredAmount;
    }

    public static function getLastAddedElement($orderByColumn = 'inv_date', $orderDirection = 'DESC')
    {
        return self::executeWithConnection(function ($connection) use ($orderByColumn, $orderDirection) {
            $sql = "SELECT * FROM " . static::$tableName . " ORDER BY $orderByColumn $orderDirection LIMIT 1";

            try {
                $stmt = $connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(\PDO::FETCH_ASSOC);

                if (!$result) {
                    error_log("No invoice found in getLastAddedElement().");
                }

                return $result ?: null;
            } catch (\PDOException $e) {
                error_log("Error fetching last added invoice: " . $e->getMessage());
                return null;
            }
        });
    }

    // Getter methods
    public function getInvNumber()
    {
        return $this->inv_number;
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

    public function getTableName()
    {
        return self::$tableName;
    }
}
