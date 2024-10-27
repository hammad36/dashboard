<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\productModel;
use dash\lib\helper;

class productController extends abstractController
{
    use InputFilter, helper;

    public function defaultAction()
    {
        $this->_data['product'] = productModel::getAll();
        $this->_view();
    }

    public function addAction()
    {
        if (isset($_POST['submit'])) {
            $this->handleProductForm(new productModel(), "Product added successfully.", "add");
        }
        $this->_view();
    }

    public function editAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $product = productModel::getByPK($id);
        if ($product === false) {
            $this->redirect('/product/');
        }
        $this->_data['product'] = $product;

        if (isset($_POST['submit'])) {
            $this->handleProductForm($product, "Product updated successfully.", "edit");
        }
        $this->_view();
    }

    public function deleteAction()
    {
        $id = $this->filterInt($this->_params[0]);
        $product = productModel::getByPK($id);
        if ($product && $product->delete()) {
            $this->redirectWithMessage("Product deleted successfully.", "default");
        } else {
            $this->redirect('/product/');
        }
    }

    private function handleProductForm($product, $successMessage, $redirectAction)
    {
        try {
            // Process input
            list($pro_name, $description, $pro_price, $pro_quantity) = $this->validateProductInputs();

            // Set product properties
            $product->setProName($pro_name);
            $product->setDescription($description);
            $product->setProPrice($pro_price);
            $product->setProQuantity($pro_quantity);

            // Save product and redirect
            if ($product->save()) {
                $this->redirectWithMessage($successMessage, "default");
            }
        } catch (\Exception $e) {
            // Handle errors and redirect with message
            $error = urlencode("Please try again with the correct values.");
            header("Location: {$redirectAction}?error={$error}");
            exit();
        }
    }

    private function validateProductInputs()
    {
        // Filter and validate inputs
        $pro_name = $this->filterString($_POST['pro_name'], 1, 255);
        $description = $this->filterString($_POST['description'], 1, 1000);
        $pro_price = $this->filterFloat($_POST['pro_price']);
        $pro_quantity = $this->filterInt($_POST['pro_quantity']);

        // Check if any required input is invalid
        if (!$pro_name || !$description || !$pro_price || !$pro_quantity) {
            throw new \Exception('Invalid input');
        }

        return [$pro_name, $description, $pro_price, $pro_quantity];
    }

    private function redirectWithMessage($message, $action)
    {
        header("Location: {$action}?msg=" . urlencode($message));
        exit();
    }
}
