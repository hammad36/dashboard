<?php

namespace dash\controllers;

use dash\models\productModel;
use dash\models\invoiceModel;

class indexController extends abstractController
{
    public function defaultAction()
    {
        $this->_data['products'] = $this->fetchAllProducts();
        $this->_data['productCount'] = $this->fetchProductCount();
        $this->_data['lastProduct'] = $this->fetchLastProduct();
        $this->_data['invoices'] = $this->fetchAllInvoices();
        $this->_data['invoiceCount'] = $this->fetchInvoiceCount();
        $this->_data['lastInvoice'] = $this->fetchLastInvoice();

        $this->_view();
    }

    /**
     * Fetch all products
     */
    private function fetchAllProducts()
    {
        return $this->handleRequest(function () {
            return productModel::getAll();
        }, "Error fetching all products");
    }

    /**
     * Fetch the count of all products based on a condition
     */
    private function fetchProductCount($condition = '1=1')
    {
        return $this->handleRequest(function () use ($condition) {
            return productModel::countWhere($condition);
        }, "Error fetching product count");
    }

    /**
     * Fetch the last added product with ordering options
     */
    private function fetchLastProduct($orderByColumn = 'created_at', $orderDirection = 'DESC')
    {
        return $this->handleRequest(function () use ($orderByColumn, $orderDirection) {
            $lastProduct = productModel::getLastAddedElement($orderByColumn, $orderDirection);
            if (!$lastProduct) {
                error_log("No recent product found in fetchLastProduct().");
            }
            return $lastProduct;
        }, "Error fetching last added product");
    }

    /**
     * Fetch all invoices
     */
    private function fetchAllInvoices()
    {
        return $this->handleRequest(function () {
            return invoiceModel::getAll();
        }, "Error fetching all invoices");
    }

    /**
     * Fetch the count of all invoices based on a condition
     */
    private function fetchInvoiceCount($condition = '1=1')
    {
        return $this->handleRequest(function () use ($condition) {
            return invoiceModel::countWhere($condition);
        }, "Error fetching invoice count");
    }

    /**
     * Fetch the last added invoice with ordering options
     */
    private function fetchLastInvoice($orderByColumn = 'inv_date', $orderDirection = 'DESC')
    {
        return $this->handleRequest(function () use ($orderByColumn, $orderDirection) {
            $lastInvoice = invoiceModel::getLastAddedElement($orderByColumn, $orderDirection);
            if (!$lastInvoice) {
                error_log("No recent invoice found in fetchLastInvoice().");
            }
            return $lastInvoice;
        }, "Error fetching last added invoice");
    }

    /**
     * Centralized error handling and logging
     * 
     * @param callable $callback  The callback to execute.
     * @param string   $errorMsg  The error message to log.
     * @return mixed              The result of the callback or a default fallback.
     */
    private function handleRequest(callable $callback, $errorMsg = "An error occurred")
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            error_log("$errorMsg: " . $e->getMessage());
            return null;  // Return a default value to avoid breaking flow
        }
    }
}
