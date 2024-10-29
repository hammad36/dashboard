<?php

namespace dash;

use dash\lib\frontController;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once  'config' . DS . 'config.php';
require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';

$frontController = new frontController();
$frontController->dispatch();
