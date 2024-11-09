<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\productModel;
use dash\lib\alertHandler;

class productController extends abstractController
{
    use InputFilter;

    private $alertHandler;

    public function __construct()
    {
        $this->alertHandler = alertHandler::getInstance();
    }

    public function defaultAction()
    {
        $this->_data['product'] = productModel::getAll();
        $this->_view();
    }

    public function addAction()
    {
        if (isset($_POST['submit'])) {
            $this->handleProductForm(new productModel(), "Product added successfully.", "add", "add");
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $product = productModel::getByPK($id);

        if ($product === false) {
            $this->alertHandler->redirectWithMessage("/product", "error", "Please re-enter valid values and try again.");
        }

        // Show the product data for editing
        $this->_data['product'] = $product;

        if (isset($_POST['submit'])) {
            // Call the handleProductForm method to update the product
            $this->handleProductForm($product, "Product updated successfully.", "edit", "edit");
        }

        $this->_view(); // Render the edit view with the product data
    }



    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $product = productModel::getByPK($id);
        if ($product && $product->delete()) {
            $this->alertHandler->redirectWithMessage("/product", "remove", "Product deleted successfully.");
        } else {
            $this->alertHandler->redirectWithMessage("/product", "error", "Product deletion failed.");
        }
    }

    private function handleProductForm($product, $successMessage, $redirectPath, $alertType)
    {
        try {
            list($pro_name, $pro_description, $pro_price, $pro_quantity) = $this->validateProductInputs();

            // Update product fields
            $product->setProName($pro_name);
            $product->setDescription($pro_description);
            $product->setProPrice($pro_price);
            $product->setProQuantity($pro_quantity);

            // Save the updated product to the database
            if ($product->save()) {
                $this->alertHandler->redirectWithMessage("/product", $alertType, $successMessage);
            }
        } catch (\Exception $e) {
            $this->alertHandler->redirectWithMessage($redirectPath, "error", "Please re-enter valid values and try again.");
        }
    }


    private function validateProductInputs()
    {
        $pro_name = $this->filterString($_POST['pro_name'], 1, 255);
        $pro_description = $this->filterString($_POST['pro_description'], 1, 1000);
        $pro_price = $this->filterFloat($_POST['pro_price']);
        $pro_quantity = $this->filterInt($_POST['pro_quantity']);

        if (!$pro_name || !$pro_description || !$pro_price || !$pro_quantity) {
            throw new \Exception('Invalid input');
        }

        return [$pro_name, $pro_description, $pro_price, $pro_quantity];
    }
}
