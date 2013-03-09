<?php
class BootStrap {
	private $_error_reporting = true;

	private $controller = null;
	private $action = null;
	private $param = array();
    
	function __construct(){
		$this->cleanGlobals();
		$this->setUrl();
	}

	private function setUrl() {
		$url = isset($_GET['url'])?rtrim($_GET['url'], "/"):null;
		$urlArray = explode("/", $url);
		$this->controller 		= (!empty($urlArray[0]) && $urlArray !== "")?$urlArray[0]:'home';
		$this->action 	 		= isset($urlArray[1])?$urlArray[1]:'index';
		$this->param['param1']	= isset($urlArray[2])?$urlArray[2]:null;
		$this->param['param2']	= isset($urlArray[3])?$urlArray[3]:null;
		$this->param['param3']	= isset($urlArray[4])?$urlArray[4]:null;
	}

	public function setErrorReporting($reporting) {
		$this->_error_reporting = (bool) $reporting;
	}

	public function init() {
		$this->getErrorReporting();
		$this->unregisterGlobals();
		$this->dispatch();
	}

	private function cleanString($value) {
		if(is_array($value)) {
	        return array_map(array($this,'cleanString'), $value);
	    } else {
	        $value = preg_replace("/(<meta\s*([-a-zA-Z0-9_<>\/\\!@#$%*()\.,]*)>)+/i", "", $value);
	        $value = str_replace(array("\\n", "\\r", chr(0x0A), chr(0x0D), "%0d", "%0a"), "", $value);
	        $value = htmlspecialchars($value, ENT_QUOTES);
	        $value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	        return $value;
	    }
	}

	private function cleanGlobals() {
		$_GET    = $this->cleanString($_GET);
		$_COOKIE = $this->cleanString($_COOKIE);
	}

	private function getErrorReporting() {
		if($this->_error_reporting === true) {
	        error_reporting(E_ALL);
	        ini_set('display_errors', 'On');
	    } else {
	        error_reporting(E_ALL);
	        ini_set('display_errors', 'Off');
	        ini_set('log_errors', 'On');
	        ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	    }
	}

	private function unregisterGlobals() {
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

	private function dispatch() {
		$controllerName = ucfirst($this->controller).'Controller';
        $controller = new $controllerName($this->controller,$this->action);
	    if((int)method_exists($controllerName,$this->action)) {
	        call_user_func_array(array($controller, "beforeAction"), $this->param);
	        call_user_func_array(array($controller, $this->action), $this->param);
	        call_user_func_array(array($controller, "afterAction"), $this->param);
	    } else {
	        die("error con bootstrap init");
	    }
	}
}
