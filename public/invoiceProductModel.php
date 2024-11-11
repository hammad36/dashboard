<?php

namespace dash\models;

use dash\lib\database\databaseHandler;
use Exception;

class invoiceProductModel extends abstractModel
{
    protected $inv_number;
    protected $pro_id;
    protected $quantity;
    protected $line_total;

    protected static $tableName = 'invoice_product';
    protected static $tableSchema = [
        'inv_number'  => self::DATA_TYPE_INT,
        'pro_id'      => self::DATA_TYPE_INT,
        'quantity'    => self::DATA_TYPE_INT,
        'line_total'  => self::DATA_TYPE_INT,
    ];

    protected static $primaryKey = ['inv_number', 'pro_id']; // Composite primary key

    /**
     * Set the invoice number
     */
    public function setInvNumber($inv_number)
    {
        $this->inv_number = $inv_number;
    }

    /**
     * Set the product ID
     */
    public function setProId($pro_id)
    {
        $this->pro_id = $pro_id;
    }

    /**
     * Set the quantity for the product
     */
    public function setQuantity($quantity)
    {
        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than zero.");
        }
        $this->quantity = $quantity;
    }

    /**
     * Set the line total for the product
     */
    public function setLineTotal($line_total)
    {
        if ($line_total < 0) {
            throw new Exception("Line total cannot be negative.");
        }
        $this->line_total = $line_total;
    }

    /**
     * Save the invoice product details to the database
     */
    public function save()
    {
        try {
            $query = "INSERT INTO invoice_product (inv_number, pro_id, quantity, line_total)
                      VALUES (:inv_number, :pro_id, :quantity, :line_total)";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':inv_number', $this->inv_number);
            $stmt->bindParam(':pro_id', $this->pro_id);
            $stmt->bindParam(':quantity', $this->quantity);
            $stmt->bindParam(':line_total', $this->line_total);

            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Failed to insert invoice product into database.");
            }

            return true;
        } catch (Exception $e) {
            // Log error
            error_log("Error saving invoice product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update the quantity and line total for a specific invoice product
     */
    public function update()
    {
        try {
            $query = "UPDATE invoice_product 
                      SET quantity = :quantity, line_total = :line_total
                      WHERE inv_number = :inv_number AND pro_id = :pro_id";

            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':inv_number', $this->inv_number);
            $stmt->bindParam(':pro_id', $this->pro_id);
            $stmt->bindParam(':quantity', $this->quantity);
            $stmt->bindParam(':line_total', $this->line_total);

            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Failed to update invoice product in database.");
            }

            return true;
        } catch (Exception $e) {
            // Log error
            error_log("Error updating invoice product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete an invoice product
     */
    public function delete()
    {
        try {
            $query = "DELETE FROM invoice_product WHERE inv_number = :inv_number AND pro_id = :pro_id";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':inv_number', $this->inv_number);
            $stmt->bindParam(':pro_id', $this->pro_id);

            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Failed to delete invoice product from database.");
            }

            return true;
        } catch (Exception $e) {
            // Log error
            error_log("Error deleting invoice product: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all invoice products for a specific invoice
     */
    public static function getByInvoiceNumber($inv_number)
    {
        try {
            $query = "SELECT * FROM invoice_product WHERE inv_number = :inv_number";
            $stmt = self::getConnection()->prepare($query);
            $stmt->bindParam(':inv_number', $inv_number);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Log error
            error_log("Error fetching invoice products: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch a specific product's details in an invoice
     */
    public static function getByProductAndInvoice($inv_number, $pro_id)
    {
        try {
            $query = "SELECT * FROM invoice_product WHERE inv_number = :inv_number AND pro_id = :pro_id";
            $stmt = self::getConnection()->prepare($query);
            $stmt->bindParam(':inv_number', $inv_number);
            $stmt->bindParam(':pro_id', $pro_id);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Log error
            error_log("Error fetching invoice product: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Getter methods for the invoice product properties
     */
    public function getInvNumber()
    {
        return $this->inv_number;
    }

    public function getProId()
    {
        return $this->pro_id;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getLineTotal()
    {
        return $this->line_total;
    }
}
