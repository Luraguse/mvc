<?php
class Busqueda extends Database {

	private $_orderBy;
	private $_order;
	private $_hasOne = array();
//	private $_hasMany;
//	private $_tVYPAV; // Tiene varios y pertenece a varios
	private $_limit = PAGINATE_LIMIT;
    private $_limited = null;
	private $_page = 1;
	private $_totalResults;

	private $_mainTable;

	private $_search = array();
	private $_returnFields = array();
	private $_searchType;
	private $_likeMode = "nonStrict";
    
	// Conectarse
	public function __construct($model, $DB_HOST = DB_HOST, $DB_NAME = DB_NAME, $DB_USER = DB_USER, $DB_PASS = DB_PASSWORD, $DB_TYPE = "mysql") {
		$this->_mainTable = $model;
        try {
        	parent::__construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
        } catch (PDOException $e) {
        	echo "ERROR: " . $e->getMessage();
        }
    }
    
    public function setTable($table){
        $this->_mainTable = $table;
    }

	public function where($arg, $field = null) {
		if($field === null && is_numeric($arg)) {
			$this->_search['id'] = $arg;
		} else if ($field != null) {
			if(is_string($field) && is_string($arg)) {
				$this->_search[$field] = $arg;
			} else {
				die("field can only be string");
			}
		} else {
			// Error where;
			die("error con where");
		}
		$this->_searchType = "where";
	}

	public function like($search, $field = null) {
		if(!empty($search) && $field != null && !empty($field) ) {
			$this->_search[$field] = $search;
			$this->_searchType = "like";
		} else {
			die("error like");
		}
	}
    
    public function all($table) {
        $this->_mainTable = $table;
        $this->_searchType = "all";
    }

	public function likeMode($mode) {
		if($mode == "strict") {
			$this->_likeMode = $mode;
		} else {
			$this->_likeMode = "nonStrict";
		}
	}
	
	public function returnFields() {

		if(func_num_args() > 1) {
			foreach(func_get_args() as $arrVal) {
					array_push($this->_returnFields, $arrVal);
				}
		} else if (func_num_args() == 1) {
			$arg = func_get_arg(0);
			if(is_array($arg)) {
				foreach($arg as $arrVal) {
					array_push($this->_returnFields, $arrVal);
				}
			} else if (is_string($arg)) {
				$stringArray = $this->splitWords($arg);
				foreach($stringArray as $val) {
					array_push($this->_returnFields, $val);
				}
			} else {
				die("error en return fields");
			}
		} else {
			die("argumentos de return fields no puede estar en blanco");
		}
	}

	public function hasOne($table, $fields) {
		$this->_hasOne[$table] = array();
		if(is_array($fields)) {
			$this->_hasOne[$table] = $fields;
		} else {
			$stringArray = $this->splitWords($fields);
			foreach($stringArray as $val) {
				array_push($this->_hasOne[$table], $val);
			}
		}
	}
/*
	public function belongsTo() {

	}

	public function hasMany() {

	}
*/
	// Funciones para buscar
	public function setLimit($limit) {
        $this->_limited = true;
		$this->_limit = (int) $limit;
	}

	public function setPage($page) {
		$this->_page = (int) $page;
	}

	public function orderBy($orderBy, $order = "ASC") {
		$this->_orderBy = $orderBy;
		$this->_order = ($order == "ASC")?$order:"DESC";
	}

    public function results() {
    	return $this->_hasOne;
    }

	public function getTotalPages() {
		return floor($this->_totalResults/$this->_limit);
	}

	private function getStatements() {
		$select = $this->getSelect();
		$from = $this->getFrom();
		switch ($this->_searchType) {
            case "where":
                $where = " WHERE ".$this->getWhere();
                break;
            case "like":
                $where = " WHERE ".$this->getLike();
                break;
            case "all":
                $where = "";
                break;
            default:
                $where = "";
                break;
        }
		$order = $this->getOrder();
		$limit = $this->getLimit();
		$this->_resultsQuery 	= "SELECT ".$select." FROM ".$from.$where.$order.$limit;
		$this->_queryTotalPages = "SELECT ".$select." FROM ".$from.$where;
        
		//$this->_bindValues;
	}
    
	public function getResults() {
		$this->getStatements();
		$result = $this->prepare($this->_resultsQuery);
		$result->setFetchMode(PDO::FETCH_ASSOC);
		if($this->_searchType == "where") {
			foreach($this->_search as $key => $val) {
				$result->bindValue($key,$val);
			}
		} else if ($this->_searchType == "like") {
			foreach($this->_search as $key => $val) {
				$result->bindValue($key, "%".$val."%");
			}
		}
		try{
            $result->execute();
        } catch (PDOException $e){
            echo "ERROR: " . $e->getMessage();
        }
        
		$page = $this->prepare($this->_queryTotalPages);
		$page->setFetchMode(PDO::FETCH_ASSOC);
		foreach($this->_search as $key => $val) {
			$page->bindValue($key,$val);
		}
		try{
            $page->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

		$qResult = $result->fetchAll();
		$this->_totalResults = $page->rowCount();
		// return array($qResult, $this->getTotalPages());
        $this->restartValues();
        return (count($qResult) == 1)?$qResult[0]:$qResult;
	}

    public function getSelect() {
    	$string = "";
    	$count = count($this->_returnFields);
    	if($count > 0) {
    		$string .= $this->joinFields($this->_returnFields, $this->_mainTable);
    		$stringExtraFields = $this->getExtraFields();
    		if(!empty($stringExtraFields)) {
	    		$string .= ", ".$stringExtraFields;
    		}
    	} else {
    		$string = "*";
    	}
    	return $string;
    }

    private function getExtraFields() {
    	$string = "";
    	if(count($this->_hasOne) > 0) {
    		foreach($this->_hasOne as $key => $val) {
    			if(is_string($val)) {
    				$string .= ", ".$key.".".$val;
    			} else if (is_array($val)) {
    				$string .= $this->joinFields($val, $key);
    			} else {
    				die("error con getExtraFields");
    			}
    		}
    	}
    	return $string;
    }

    public function getFrom() {
    	$string = $this->_mainTable;
    	$string .= $this->getJoinOn();
    	return $string;
    }

    private function getJoinOn() {
    	$string = "";
		if(count($this->_hasOne) > 0) {
			foreach($this->_hasOne as $key => $val) {
				$string .= " LEFT JOIN ".$key." ON ".$this->_mainTable.".id_".$key." = ".$key.".id";
			}
		}
		return $string;
    }

    public function getWhere() {
    	$string = "";
    	$i = 0;
    	$searchCount = count($this->_search);
    	if($searchCount > 0) {
    		foreach($this->_search as $key => $val) {
	    		$string .= $this->_mainTable.".".$key." = :".$key;
	    		if($i < ($searchCount -1)) {
	    			$string .= " AND ";
	    		}
	    		$i++;
	    	}
    	} else {
    		die("getWhere error, no hay parametros");
    	}
    	return $string;
    }

    public function getLike() {
    	$string = "";
    	$i = 0;
    	$searchCount = count($this->_search);
    	if($searchCount > 0) {
    		foreach($this->_search as $key => $val) {
    			$string .= $this->_mainTable.".".$key." LIKE :".$key;
    			if($i < ($searchCount - 1)) {
    				$string .= ($this->_likeMode == "nonStrict")? " OR ": " AND ";
    			}
    			$i++;
    		}
    	} else {
    		die("getLike error");
    	}
    	return $string;
    }

    public function getOrder() {
    	$string = "";
    	if(is_string($this->_orderBy) && is_string($this->_order)) {
    		$string .= " ORDER BY ".$this->_mainTable.".".$this->_orderBy." ".$this->_order;
    	}
    	return $string;
    }

    public function getLimit() {
    	$string = "";
    	if($this->_limit > 0 && $this->_page > 0 && $this->_limited != null) {
    		$string .= " LIMIT ".(($this->_page - 1) * $this->_limit).",".$this->_limit;
    	}

    	return $string;
    }

	private function splitWords($palabras) {
        $palabras = preg_replace('/[,\s]+$/', "", $palabras);
        $palabras = preg_split('/[,\s]+/', $palabras);
        
        return $palabras;
    }

    private function joinFields($val, $table) {
    	$string = "";
    	$count = count($val);
    	if($count > 0) {
    		foreach($val as $i => $field) {
    			$string .= $table.".".$field;
    			if($i < ($count -1)) {
    				$string .= ", ";
    			}
    		}
    	}
    	return $string;
    }
    
    private function restartValues() {
        $this->_orderBy = null;
        $this->_order = null;
        $this->_limit = PAGINATE_LIMIT;
        $this->_page = 1;
        $this->_totalResults = null;
        $this->_limited = null;
        
        $this->_searchType = null;
        $this->_likeMode = "nonStrict";
        $this->_hasOne = array();
        $this->_search = array();
        $this->_returnFields = array();
    }
}