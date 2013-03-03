<?php
require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once (ROOT . DS . 'config' . DS . 'routing.php');
require_once (ROOT . DS . 'library' . DS . 'shared.php');

// Cargar automaticamente las clases requeridas
function __autoload($className) {
    if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else {
        die("Error $className not found");
    }
}
$bootstrap = new BootStrap();
$bootstrap->setErrorReporting(false);
$bootstrap->init();