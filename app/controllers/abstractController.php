<?php

namespace dashboard\controllers;


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
        echo VIEWS_PATH . $this->_controller, $this->_action;
    }
}
