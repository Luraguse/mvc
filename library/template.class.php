<?php
class Template {
    protected $_variables = array();
    protected $_controller;
    protected $_action;
    protected $_html;
    
    function __construct($controller, $action) {
        $this->_controller = $controller;
        $this->_action = $action;
        $this->HTML = new HTML;
    }
    
    # set variables 
    function set($name, $value) {
        $this->_variables[$name] = $value;
    }
    
    # Display template
    
    function render($doNotRenderHeader = 0) {
        extract($this->_variables);
        if($doNotRenderHeader == 0) {
            if(file_exists(ROOT.DS.'application'.DS.'views'.DS.$this->_controller.DS.'header.php')) {
                include(ROOT.DS.'application'.DS.'views'.DS.$this->_controller.DS.'header.php');
            } else {
                include(ROOT.DS.'application'.DS.'views'.DS.'header.php');
            }
        }
        if(file_exists(ROOT.DS.'application'.DS.'views'.DS.$this->_controller.DS.$this->_action.'.php')) {
            include(ROOT.DS.'application'.DS.'views'.DS.$this->_controller.DS.$this->_action.'.php');
        }
        
        if($doNotRenderHeader == 0) {
            if(file_exists(ROOT.DS.'application'.DS.'views'.DS.$this->_controller.DS.'footer.php')) {
                include(ROOT.DS.'application'.DS.'views'.DS.$this->_controller.DS.'footer.php');
            } else {
                include(ROOT.DS.'application'.DS.'views'.DS.'footer.php');
            }
        }
    }
}