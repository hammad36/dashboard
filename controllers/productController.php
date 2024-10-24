<?php

namespace dash\controllers;

use dash\controllers\abstractController;
use dash\lib\InputFilter;
use dash\models\productModel;

class productController extends abstractController
{
    use InputFilter;

    public function defaultAction()
    {
        $this->_data['product'] = productModel::getAll();
        $this->_view();
    }

    public function addAction()
    {
        if (isset($_POST['submit'])) {
            try {
                $prod = new productModel();

                // Filter and validate inputs
                $pro_name = $this->filterString($_POST['pro_name'], 1, 255);
                $description = $this->filterString($_POST['description'], 1, 1000);
                $pro_price = $this->filterFloat($_POST['pro_price']);
                $pro_quantity = $this->filterInt($_POST['pro_quantity']);

                // Check if any required input is invalid
                if (!$pro_name || !$description || !$pro_price || !$pro_quantity) {
                    throw new \Exception('Invalid input');
                }

                $prod->setProName($pro_name);
                $prod->setDescription($description);
                $prod->setProPrice($pro_price);
                $prod->setProQuantity($pro_quantity);

                // If valid, proceed (e.g., save to database, etc.)
                echo '<pre>';
                var_dump($prod);
                echo '</pre>';
            } catch (\Exception $e) {
                // Redirect with error in URL
                $error = urlencode("Please try again with the correct values.");
                header("Location: add.view.php?error={$error}");
                exit();
            }
        }
        $this->_view();
    }
}
