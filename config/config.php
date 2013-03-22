<?php
// Definicion de constantes

define ('DB_NAME',          'test');
define ('DB_USER',          'root');
define ('DB_PASSWORD',      'd34d50ul7r1b3');
define ('DB_HOST',          'localhost');

define ('BASE_PATH',        "http://localhost/mvc/public");
define ('BASE_FILE_PATH',   $_SERVER['DOCUMENT_ROOT'].DS."mvc".DS."public");
define ('JS_FULL_PATH',     BASE_FILE_PATH.DS.'js');
define ('CSS_FULL_PATH',    BASE_FILE_PATH.DS.'css');
define ('JS_PUBLIC_PATH',   BASE_PATH.DS.'js');
define ('CSS_PUBLIC_PATH',  BASE_PATH.DS.'css');

define ('PAGINATE_LIMIT',   '5');

// Defaults
define('DEFAULT_CONTROLLER','index');
define('DEFAULT_ACTION',    'index');