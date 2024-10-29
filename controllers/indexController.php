<?php

namespace dash\controllers;

use dash\models\productModel;

class indexController extends abstractController
{
    public function defaultAction()
    {
        $this->_data['product'] = $this->getAllProducts();
        $this->_data['productCount'] = $this->getProductCount();
        $this->_data['lastProduct'] = $this->getLastAddedProduct();

        $this->_view();
    }

    /**
     * Fetch all products
     */
    private function getAllProducts()
    {
        try {
            return productModel::getAll();
        } catch (\Exception $e) {
            error_log("Error fetching products: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch the count of all products
     */
    private function getProductCount($condition = '1=1')
    {
        try {
            return productModel::countWhere($condition);
        } catch (\Exception $e) {
            error_log("Error fetching product count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Fetch the last added product with customizable ordering column
     */
    private function getLastAddedProduct($orderByColumn = 'created_at', $orderDirection = 'DESC')
    {
        try {
            $lastProduct = productModel::getLastAddedElement($orderByColumn, $orderDirection);

            if (!$lastProduct) {
                error_log("No recent product available in getLastAddedProduct().");
            }

            return $lastProduct;
        } catch (\Exception $e) {
            error_log("Error fetching last added product in controller: " . $e->getMessage());
            return null;
        }
    }
}
