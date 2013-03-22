<?php
class IndexController extends Controller {
    
    function beforeAction(){
    }
    function index() {
        
    }
    
    function show() {
        // $this->dataBase()->buscar()->donde(1);
    }
    
    function actualizar() {
        // $this->database()->actualizar($var, $valores); // $var = get, $valores = post
    }
    
    function crear() {
        // $this->database()->crear();

    }
    
    function borrar($id) {
        echo "<pre>",var_dump($this),"</pre>";
        // $this->database()->borrar($id);
        
    }
    
    function afterAction(){
    }
}