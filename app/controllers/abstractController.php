<?php

namespace dashboard\controllers;

use dashboard\lib\frontController;

class abstractController
{
    protected $_controller;
    protected $_action;
    protected $_params;

    public function notFoundAction()
    {
        echo 'Sorry this page doesn\'t exists';
    }

    public function setController($controllerName)
    {
        $this->_controller = $controllerName;
    }

    public function setAction($actionName)
    {
        $this->_action = $actionName;
    }

    public function setParams($paramsName)
    {
        $this->_params = $paramsName;
    }

    public function _view()
    {
        if ($this->_action == frontController::NOT_FOUND_ACTION) {
            require_once VIEWS_PATH . 'notFound' . DS . 'notFound.view.php';
        } else {
            $view = VIEWS_PATH . $this->_controller . DS . $this->_action . '.view.php';
            if (file_exists($view)) {
                require_once $view;
            } else {
                require_once VIEWS_PATH . 'notFound' . DS . 'noView.view.php';
            }
        }
    }
}
