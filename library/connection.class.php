<?php

class Connection{
    
    private $connection;

	public function __construct($DB_TYPE = "mysql", $DB_HOST = DB_HOST, $DB_NAME = DB_NAME, $DB_USER = DB_USER, $DB_PASS = DB_PASSWORD) {
		$this->connect($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
	}
    
    private function connect($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
        try{
			$this->connection = new PDO($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
    }
    
    public function connection() {
        return $this->connection;
    }
}