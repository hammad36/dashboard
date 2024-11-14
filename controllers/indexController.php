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

    private function fetchAllProducts()
    {
        return $this->handleRequest(function () {
            return productModel::getAll();
        }, "Error fetching all products");
    }

    private function fetchProductCount($condition = '1=1')
    {
        return $this->handleRequest(function () use ($condition) {
            return productModel::countWhere($condition);
        }, "Error fetching product count");
    }

    private function fetchLastProduct()
    {
        return $this->handleRequest(function () {
            $lastProduct = productModel::getLastAddedElement('pro_id', 'DESC');
            if (!$lastProduct) {
                error_log("No recent product found in fetchLastProduct().");
            }
            return $lastProduct;
        }, "Error fetching last added product");
    }

    private function fetchAllInvoices()
    {
        return $this->handleRequest(function () {
            return invoiceModel::getAll();
        }, "Error fetching all invoices");
    }

    private function fetchInvoiceCount($condition = '1=1')
    {
        return $this->handleRequest(function () use ($condition) {
            return invoiceModel::countWhere($condition);
        }, "Error fetching invoice count");
    }

    private function fetchLastInvoice()
    {
        return $this->handleRequest(function () {
            $lastInvoice = invoiceModel::getLastAddedElement('inv_number', 'DESC');
            if (!$lastInvoice) {
                error_log("No recent invoice found in fetchLastInvoice().");
            }
            return $lastInvoice;
        }, "Error fetching last added invoice");
    }

    private function handleRequest(callable $callback, $errorMsg = "An error occurred")
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            error_log("$errorMsg: " . $e->getMessage());
            return null;
        }
    }
}
