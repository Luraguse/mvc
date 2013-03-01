<?php
// Revisar reporte de errores, mostrar si se encuentra en desarrollo
function setReporting() {
    if(DEVELOPMENT_ENVIRONMENT === true) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
    }
}

// Seguridad para variables
function clean_string($value) {
    if(is_array($value)) {
        return array_map("clean_string", $value);
    } else {
        $value = preg_replace("/(<meta\s*([-a-zA-Z0-9_<>\/\\!@#$%*()\.,]*)>)+/i", "", $value);
        $value = str_replace(array("\\n", "\\r", chr(0x0A), chr(0x0D), "%0d", "%0a"), "", $value);
        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        return $value;
    }
}

function clean_globals() {
    $_GET    = clean_string($_GET);
	$_POST   = clean_string($_POST);
	$_COOKIE = clean_string($_COOKIE);
}
// Revisar globales registradas y removerlas
function unregisterGlobals() {
    if(ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach($array as $value) {
            foreach($GLOBALS[$value] as $k => $v) {
                if($v === $GLOBALS[$k]){
                    unset($GLOBALS[$k]);
                }
            }
        }
    }
}

// Llamar función secundaria

function performAction($controller, $action, $queryString = null, $render = 0) {
    $controllerName = ucfirst($controller).'Controller';
    $dispatch = new $controllerName($controller, $action);
    $dispatch->render = $render;
    return call_user_func_array(array($dispatch,$action), $queryString);
}

// Enrutamiento
function routeUrl($url) {
    global $routing;
    
    foreach($routing as $pattern => $result) {
        if(preg_match($pattern, $url)) {
            return preg_replace($pattern, $result, $url);
        }
    }
    
    return $url;
}

// Llamar funcion principal
function callHook() {
    global $url;
    global $default;
    
    $queryString = array();
    
    if(!isset($url)) {
        $controller = $default['controller'];
        $action = $default['action'];
    } else {
        $url = rtrim(routeUrl($url), "/");
        $urlArray = array();
        $urlArray = explode("/", $url);
        $controller = $urlArray[0];
        array_shift($urlArray);
        if(isset($urlArray[0])) {
            $action = $urlArray[0];
            array_shift($urlArray);
        } else {
            $action = 'index';
        }
        $queryString = $urlArray;
    }
    
    $controllerName = ucfirst($controller).'Controller';
    $dispatch = new $controllerName($controller,$action);
    
    if((int)method_exists($controllerName,$action)) {
        call_user_func_array(array($dispatch, "beforeAction"), $queryString);
        call_user_func_array(array($dispatch, $action), $queryString);
        call_user_func_array(array($dispatch, "afterAction"), $queryString);
    } else {
        # new Error();
    }
}

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

setReporting();
clean_globals();
unregisterGlobals();
callHook();