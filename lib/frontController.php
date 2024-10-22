<?php

namespace dash\lib;

class frontController
{
    const NOT_FOUND_ACTION = 'notFoundAction';
    const NOT_FOUND_CONTROLLER = 'dash\controllers\\notFoundController';
    private $_controller = 'index';
    private $_action = 'default';
    private $_params = array();

    public function __construct()
    {
        $this->_parseUrl();
    }

    private function _parseUrl()
    {
        $url = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 3);
        $this->_controller = $url[0] ?? null;
        $this->_action = $url[1] ?? null;
        if (isset($url[2])) {
            $url[2] = explode('/', $url[2]);
        }
    }

    public function dispatch()
    {
        $controllerClassName = 'dash\controllers\\' . $this->_controller . 'Controller';
        $actionName = $this->_action . 'Action';
        if (!class_exists($controllerClassName)) {
            $controllerClassName = self::NOT_FOUND_CONTROLLER;
        }
        $controller = new $controllerClassName();
        if (!method_exists($controller, $actionName)) {
            $this->_action = $actionName = self::NOT_FOUND_ACTION;
        }
        $controller->setController($this->_controller);
        $controller->setAction($this->_action);
        $controller->setParams($this->_params);
        $controller->$actionName();
    }
}
