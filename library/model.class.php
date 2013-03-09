<?php
class Model {
    protected $ModelName;
    protected $Buscar;
    
    public $Sanitize = array();
    public $Validate = array();
    public $jsFiles;
    public $CombinarJs;
    
    function __construct() {
        $this->ModelName = get_class($this);
        $this->Connection = new Connection();
        $this->Buscar = new Busqueda($this->Connection, strtolower($this->ModelName));
        $this->DataBase = new DataBase($this->Connection, strtolower($this->ModelName));
    }
    
    public function Buscar() {
        return $this->Buscar;
    }
    
    function __destruct() {}
}