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
        $this->Connection = new Connection( $DB_TYPE = "mysql", $DB_HOST = DB_HOST, $DB_NAME = DB_NAME, $DB_USER = DB_USER, $DB_PASS = DB_PASSWORD);
        $this->Buscar = new Busqueda($this->Connection, strtolower(get_class($this)));
        $this->_model = get_class($this);
    }
    
    public function Buscar() {
        return $this->Buscar;
    }
    
    function __destruct() {}
}