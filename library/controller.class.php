<?php
class Controller {
    protected $_controller;
    protected $_action;
    protected $_template;
    protected $_model;
    
    public $doNotRenderHeader;
    public $render;
    
    function __construct($controller, $action) {
        $this->_controller = ucfirst($controller);
        $this->_action = $action;
        
        $model = ucfirst($controller);
        $this->doNotRenderHeader = 0;
        $this->render = 1;
        $this->_model = new $model();
        $this->_template = new Template($controller, $action);
    }
    
    public function Buscar() {
        return $this->_model->Buscar();
    }
    
    function set($name, $value) {
        $this->_template->set($name,$value);
    }
    
    function __destruct() {
        if($this->render) {
            $this->_template->render($this->doNotRenderHeader);
        }
    }
}