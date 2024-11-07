<?php

namespace dash;

use dash\lib\frontController;
use dash\lib\template;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

require_once  'config' . DS . 'config.php';
require_once APP_PATH . DS . 'lib' . DS . 'autoload.php';
$templateParts = require_once  'config' . DS . 'templateConfig.php';

$template = new template($templateParts);
$frontController = new frontController($template);
$frontController->dispatch();
