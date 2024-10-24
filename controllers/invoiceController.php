<?php

namespace dash\controllers;

use dash\controllers\abstractController;

class invoiceController extends abstractController
{
    public function defaultAction()
    {
        $this->_view();
    }
}
