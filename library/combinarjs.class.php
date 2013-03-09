<?php
/**
 * Clase para combinar varios archivos de js en uno solo, sirve para reducir
 * la cantidad de requests que se hacen al servidor al cargar los archivos de js.
 * Versión mínima de PHP: 5.3
 * 
 * Autor: Luis Gutiérrez
 * Hitosoft - 2012
 */
class CombinarJs extends CombinarArchivos {
    
    function __construct() {
        parent::__construct("js",JS_FULL_PATH);
    }
    
    public function regresarJs() {
        return $this->getFile();
    }
}