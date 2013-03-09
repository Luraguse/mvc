<?php
class Controller {
    protected $controller;
    protected $action;
    protected $template;
    protected $model;
    
    public $doNotRenderHeader;
    public $render;
    
    function __construct($controller, $action) {
        $this->controller = ucfirst($controller);
        $this->action = $action;
        
        $model = ucfirst($controller);
        $this->doNotRenderHeader = 0;
        $this->render = 1;
        $this->model = new $model();
        $this->template = new Template($controller, $action);
    }
    
    public function Buscar() {
        return $this->model->Buscar();
    }
    
    function set($name, $value) {
        $this->template->set($name,$value);
    }
    
    function __destruct() {
        if($this->render) {
            $this->template->render($this->doNotRenderHeader);
        }
    }
}