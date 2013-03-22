<?php
class Model {
    protected $modelName;
    protected $Buscar;
    
    public $Sanitize = array();
    public $Validate = array();
    public $jsFiles;
    public $CombinarJs;
    
    function __construct() {
        $this->modelName = strtolower(get_class($this));
        $this->dataBase = new DataBase($this->modelName);
        //$this->DataBase = new DataBase($this->ModelName);
    }
    
    public function Buscar() {
        return $this->Buscar;
    }
    
    function __destruct() {}
}