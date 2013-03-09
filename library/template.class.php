<?php
class Template {
    protected $variables = array();
    protected $controller;
    protected $action;
    protected $HTML;
    
    function __construct($controller, $action) {
        $this->controller = $controller;
        $this->action = $action;
        $this->HTML = new HTML;
    }
    
    # set variables 
    function set($name, $value) {
        $this->variables[$name] = $value;
    }
    
    # Display template
    
    function render($doNotRenderHeader = 0) {
        extract($this->variables);
        if($doNotRenderHeader == 0) {
            if(file_exists(ROOT.DS.'application'.DS.'views'.DS.$this->controller.DS.'header.php')) {
                include(ROOT.DS.'application'.DS.'views'.DS.$this->controller.DS.'header.php');
            } else {
                include(ROOT.DS.'application'.DS.'views'.DS.'header.php');
            }
        }
        if(file_exists(ROOT.DS.'application'.DS.'views'.DS.$this->controller.DS.$this->action.'.php')) {
            include(ROOT.DS.'application'.DS.'views'.DS.$this->controller.DS.$this->action.'.php');
        }
        
        if($doNotRenderHeader == 0) {
            if(file_exists(ROOT.DS.'application'.DS.'views'.DS.$this->controller.DS.'footer.php')) {
                include(ROOT.DS.'application'.DS.'views'.DS.$this->controller.DS.'footer.php');
            } else {
                include(ROOT.DS.'application'.DS.'views'.DS.'footer.php');
            }
        }
    }
}