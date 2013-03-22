<?php

class Conexion{
    
    private $conexion;

	public function __construct($DB_TYPE = "mysql", $DB_HOST = DB_HOST, $DB_NAME = DB_NAME, $DB_USER = DB_USER, $DB_PASS = DB_PASSWORD) {
		$this->conectar($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
        echo "conectado";
        return $this->conexion;
	}
    
    private function conectar($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
        try{
			$this->conexion = new PDO($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
			$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
    }
}