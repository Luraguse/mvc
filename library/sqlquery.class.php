<?php
class SQLQuery {
    protected $_dbHandle;
    protected $_result;
    protected $_query;
    public $_table;
    
    protected $_desccribe = array();
    
    protected $_orderBy;
    protected $_order;
    protected $_extraConditions;
    protected $_hO;
    protected $_hM;
    protected $_hMABTM;
    protected $_page;
    public $_limit;
    
    private $_resultado;
    
    // Conexión;
    function __construct($address, $account, $pwd, $name) {
        $this->_dbHandle = @mysql_connect($address, $account, $pwd, $name);
        if($this->_dbHandle != 0) {
            if(mysql_select_db($name, $this->_dbHandle)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    // Desconectarse
    function disconnect() {
        if(@mysql_close($this->_dbHandle) != 0) {
            return 1;
        } else {
            return 0;
        }
    }
    
    // Query 
    
    function where($field, $value) {
        $this->_extraConditions .= '`'.$this->_model.'`.`'.$field.'` = \''.$value.'\' AND';
    }
    
    function like($field, $value) {
        $this->_extraConditions .= '`'.$this->_model.'`.`'.$field.'` LIKE \'%'.$value.'%\' AND';
    }
    
    function showHasOne() {
        $this->_hO = 1;
    }
    
    function showHasMany() {
        $this->_hM = 1;
    }
    
    function showHMABTM() {
        $this->_hMABTM = 1;
    }
    
    function setLimite($limit) {
        $this->_limit = $limit;
    }
    
    function setPage($page) {
        $this->_page = $page;
    }
    
    function orberBy($orderBy, $order = 'ASC') {
        $this->_orderBy = $orderBy;
        $this->_order = $order;
    }
    
    function search() {
        $from = '`'.$this->_table.'` as `'.$this->_model.'` ';
        $conditions = '\'1\'=\'1\' AND ';
        $conditionsChild = '';
        $fromChild = '';
        
        if($this->hO == 1 && isset($this->hasOne)) {
            foreach($this->hasOne as $alias => $model) {
                $table = strtolower($model);
                $singularAlias = strtolower($alias);
                $from .= 'LEFT JOIN `'.$table.'` as `'.$alias.'` ';
                $from .= 'ON `'.$this->_model.'`.`'.$singularAlias.'_id` = `'.$alias.'`.`id` ';
            }
        }
        
        if($this->id) {
            $conditions .='`'.$this->_model.'`.`id` = \''.$this->id.'\' AND ';
        }
        
        if($this->_extraConditions) {
            $conditions .= $this->_extraConditions;
        }
        
        $conditions = substr($conditions, 0, -4);
        
        if(isset($this->_orderBy)) {
            $conditions .= ' ORDER BY `'.$this->_model.'`.`'.$this->_orderBy.'` '.$this->_order;
        }
        
        if(isset($this->_page)) {
            $offset = ($this->_page -1)*$this->_limit;
            $conditions .= ' LIMIT '.$this->_limit.' OFFSET'.$offset;
        }
        
        $this->_query = 'SELECT * FROM '.$from.' WHERE '.$conditions;
        $this->_result = mysql_query($this->_query, $this->_dbHandle);
        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();
        $numOfFields = mysql_num_fields($this->_result);
        for($i = 0; $i < $numOfFields; ++$i) {
            array_push($table, mysql_field_table($this->_result, $i));
            array_push($field, mysql_field_name($this->_result, $i));
        }
        if(mysql_num_rows($this->_result) > 0) {
            while($row = mysql_fetch_row($this->_result)) {
                for($i = 0; $i < $numOfFields; ++$i) {
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
                
                if($this->_hM == 1 && isset($this->hasMany)) {
                    foreach($this->hasMany as $aliasChild => $modelChild) {
                        $queryChild = '';
                        $conditionsChild = '';
                        $fromChild = '';
                        
                        $tableChild = strtolower($modelChild);
                        $pluralAliasChild = strtolower($aliasChild);
                        $singularAliasChild = strtolower($aliasChild);
                        
                        $fromChild .= '`'.$tableChild.'` as `'.$aliasChild.'`';
                        
                        $conditionChild .= '`'.$aliasChild.'`.`'.strtolower($this->_model).'_id` = \''.$tempResults[$this->_model]['id'].'\'';
                        
                        $queryChild = 'SELECT * FROM '.$fromChild.' WHERE '.$conditions;
                        
                        $resultChild = mysql_query($queryChild, $this->_dbHandle);
                        
                        $tableChild = array();
                        $fieldChild = array();
                        $tempResultsChild = array();
                        $resultsChild = array();
                        
                        if(mysql_num_rows($resultChild) > 0) {
                            $numOfFieldsChild = mysql_num_fields($resultChild);
                            for($j = 0; $j < $numOfFieldsChild; ++$j) {
                                array_push($tableChild, mysql_field_table($resultChild, $j));
                                array_push($fieldChild, mysql_field_name($resultChild, $j));
                            }
                            
                            while($rowChild = mysql_fetch_row($resultChild)) {
                                for($j = 0; $j < $numOfFieldsChild; ++$j) {
                                    $tempResultsChild[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
                                }
                                array_push($resultsChild, $tempResultsChild);
                            }
                        }
                        $tempResults[$aliasChild] = $resultsChild;
                    }
                }
                
                if($this->_hMABTM == 1 && isset($this->hasManyAndBelongsToMany)) {
                    foreach($this->hasManyAndBelongsToMany as $aliasChild => $tableChild) {
                        $queryChild = '';
                        $conditionChild = '';
                        $fromChild = '';
                        
                        $tableChild = strtolower($tableChild);
                        $pluralAliasChild = strtolower($aliasChild);
                        $singularAliasChild = strtolower($aliasChild);
                        
                        $sortTables = array($this->_table, $pluralAliasChild);
                        sort($sortTables);
                        $joinTable = implode('_', $sortTables);
                        
                        $fromChild .= '`'.$tableChild.'` as `'.$aliasChild.'`,';
                        $fromChild .= '`'.$joinTable.'`,';
                        
                        $conditionsChild .= '`'.$joinTable.'`.`'.$singularAliasChild.'_id` = `'.$aliasChild.'`.`id` AND ';
                        $conditionsChild .= '`'.$joinTable.'`.`'.strtolower($this->_model).'_id` = \''.$tempResults[$this->_model]['id'].'\'';
                        $fromChild = substr($fromChild, 0, -1);
                        $queryChild = 'SELECT * FROM '.$fromChild.' WHERE '.$conditionsChild;
                        
                        $resultChild = mysql_query($queryChild, $this->_dbHandle);
                        
                        $tableChild = array();
                        $fieldChild = array();
                        $tempResultsChild = array();
                        $resultsChild = array();
                        
                        if(mysql_num_rows($resultChild) > 0) {
                            $numOfFieldsChild = mysql_num_fields($resultChild);
                            for($j = 0; $j < $numOfFieldsChild; ++$j) {
                                array_push($tableChild, mysql_field_table($resultChild, $j));
                                array_push($fieldChild, mysql_field_name($resultChild, $j));
                            }
                            
                            while($rowChild = mysql_fetch_row($resultChild)) {
                                for($j = 0; $j < $numOfFieldsChild; ++$j) {
                                    $tempResultsChild[$tableChild[$j]][$fieldChild[$j]];
                                }
                                array_push($resultsChild, $tempResultsChild);
                            }
                        }
                        $tempResults[$aliasChild] = $resultChild;
                    }
                }
                
                array_push($result,$tempResults);
            }
            
            if(mysql_num_rows($this->_result) == 1 && $this->id != null) {
                mysql_free_result($this->_result);
                $this->clear();
                return($result[0]);
            } else {
                mysql_free_result($this->_result);
                $this->clear();
                return($result);
            }
        } else {
            mysql_free_result($this->_result);
            $this->clear();
            return $result;
        }
    }
    
    // Custom query
    
    function custom($query) {
        $this->_result = mysql_query($query, $this->_dbHandle);
        
        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();
        
        if(substr_count($strtoupper($query), "SELECT")>0) {
            if(mysql_num_rows($this->_result) > 0) {
                $numOfFields = mysql_num_fields($this->_result);
                for($i = 0; $i < $numOfFields; ++$i) {
                    array_push($table, mysql_field_table($this->_result, $i));
                    array_push($field, mysql_field_name($this->_result, $i));
                }
                
                while($row = mysql_fetch_row($this->_result)) {
                    for($i = 0; $i < $numOfFields; ++$i) {
                        $table[$i] = ucfirst($table[$i]);
                        $tempResults[$table[$i]][$field[$i]] = $row[$i];
                    }
                    array_push($result, $tempResults);
                }
            }
            
            mysql_free_result($this->_result);
        }
        $this->clear();
        return $result;
    }
    
    // Describe una tabla
    protected function _describe() {
        global $cache;

		$this->_describe = $cache->get('describe'.$this->_table);

		if (!$this->_describe) {
			$this->_describe = array();
			$query = 'DESCRIBE '.$this->_table;
			$this->_result = mysql_query($query, $this->_dbHandle);
			while ($row = mysql_fetch_row($this->_result)) {
				 array_push($this->_describe,$row[0]);
			}

			mysql_free_result($this->_result);
			$cache->set('describe'.$this->_table,$this->_describe);
		}

		foreach ($this->_describe as $field) {
			$this->$field = null;
		}
    }
    
    // Borrar dato
    function delete() {
        if($this->id) {
            $query = 'DELETE FROM '.$this->_table.' WHERE `id`=\''.$this->id.'\'';
            $this->_result = mysql_query($query, $this->_dbHandle);
            $this->clear();
            if($this->_result == 0) {
                # new error;
                return -1;
            }
        } else {
            # new error
            return -1;
        }
    }
    
    // guardar
    function save() {
        $query = '';
        if(isset($this->id)) {
            $updates = '';
            foreach($this->_describe as $field) {
                if($this->$field) {
                    $updates .= '`'.$field.'` = \''.$this->field.'\',';
                }
            }
            
            $updates = substr($updates, 0,-1);
            
            $query = 'UPDATE '.$this->_table.' SET '.$updates.' WHERE `id`=\''.$this->id.'\'';
        } else {
            $fields = '';
            $values = '';
            
            foreach($this->_describe as $field) {
                if($this->$field) {
                    $fields .= '`'.$field.'`,';
                    $values .= '\''.$this->$field.'\',';
                }
            }
            $values = substr($values,0,-1);
            $fields = substr($fields,0,-1);
            
            $query = 'INSERT INTO '.$this->_table.' ('.$fields.') VALUES ('.$values.')';
        }
        $this->_result = mysql_query($query, $this->_dbHandle);
        $this->clear();
        if($this->_result == 0) {
            # new error
            return -1;
        }
    }
    
    # Limpiar las variables
    function clear() {
        foreach($this->_describe as $field) {
            $this->$field = null;
        }
        
        $this->_orderby = null;
		$this->_extraConditions = null;
		$this->_hO = null;
		$this->_hM = null;
		$this->_hMABTM = null;
		$this->_page = null;
		$this->_order = null;
    }
    
    # Cuenta de páginas
    function totalPages() {
        if($this->_query && $this->_limit) {
            $pattern = '/SELECT (.*?) FROM (.*)LIMIT(.*)/i';
            $replacement = 'SELECT COUNT(*) FROM $2';
            $countQuery = preg_replace($patter, $replacement, $this->_query);
            $this->_result = mysql_query($countQuery, $this->_dbHandle);
            $count = mysql_fetch_row($this->_result);
            $totalPages = ceil($count[0]/$this->_limit);
            return $totalPages;
        } else {
            # new error;
            return -1;
        }
    }
    
    # Error
    function getError() {
        return mysql_error($this->_dbHandle);
    }
    
    public function buscar($string) {
        $resultado = mysql_query($string);
        $resultados = array();
        
        while($row = mysql_fetch_assoc($resultado)) {
            $resultados[] = $row;
        }
        return (count($resultados) > 1)?$resultados:$resultados[0];
    }
}