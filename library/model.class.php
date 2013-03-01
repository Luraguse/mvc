<?php
class Model {
    protected $_model;
    protected $Buscar;
    
    public $Sanitize = array();
    public $Validate = array();
    public $_jsFiles;
    public $CombinarJs;
    
    function __construct() {
        //$this->buscar = new SQLQuery(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->Buscar = new Busqueda(strtolower(get_class($this)));
        $this->_model = get_class($this);
    }
    
    public function Buscar() {
        return $this->Buscar;
    }
    
    function __destruct() {}
}