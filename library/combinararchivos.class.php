<?php
/**
 * Clase base para combinar archivos.
 * Versión mínima de PHP: 5.3
 * 
 * Autor: Luis Gutiérrez
 * Hitosoft - 2012
 */
class CombinarArchivos {
    
    protected $_extension;
    protected $_lista_de_archivos;
    protected $_ruta_para_archivo;
    protected $_fecha_de_modificacion_archivo;
    protected $_array_de_valores = array();
    
    function __construct($extension, $directorio_de_archivos) {
        $this->_ruta_para_archivo = $directorio_de_archivos.DS."custom.".$extension;
        $this->_lista_de_archivos = $this->crear_lista_de_archivos_en_directorio($directorio_de_archivos,$extension);
        $this->_extension = $extension;
    }
    
    /**
     * Busca archivos dentro de un directorio y regresa archivos que tienen 
     * extensión especificada.
     * @param string $directorio Dirección donde se van a buscar los archivos.
     * @param string $extension Extensión de los archivos que se desean buscar.
     * @return array Regresa un array con los archivos que se encontraron en el directorio.
     */
    private function crear_lista_de_archivos_en_directorio($directorio, $extension) {
        $dir = opendir($directorio);
        $array_archivos = array();
        while($archivo = readdir($dir)) {
            if(pathinfo($archivo, PATHINFO_EXTENSION) == $extension) {
                if($extension == "js")
                    $array_archivos[] = JS_FULL_PATH.DS.$archivo;
                else if($extension == "css")
                    $array_archivos[] = CSS_FULL_PATH.DS.$archivo;
                else
                    die("error with crear lista");
            }
        }
        closedir($dir);
        return $array_archivos;
    }
    
    public function setVariables($variables) {
        $this->_array_de_valores = $variables;
    }
    
    /**
     * Revisa la existencia de archivos
     * @param array $archivos Lista con la ruta a los archivos que se desean revisar.
     * @return Si alguno de los archivos no existe regresa false, de lo contrario regresa true.
     * TODO: Cambiar false a un error.
     */
    private function comprobar_si_existen_archivos($archivos) {
        if(is_array($archivos)) {
            foreach($archivos as $archivo) {
                if(!file_exists($archivo)) {
                    return false;
                }
            }
            return true;
        }
    }
    
    /**
     * Compara una lista de archivos con un archivo en específico.
     * @param array $archivos Lista de archivos.
     * @param string $comparar_con Archivo con el que se comparará la lista.
     * @return boolean Si alguno de los archivos de la lista es más nuevo que
     *         el que se está comparando regresa false. Si el archivo con el que
     *         compara la lista es más nuevo regresa true.
     * TODO: Ver otras opciones para este método.
     */
    private function comparar_fechas_de_modificacion() {
        if(is_array($this->_lista_de_archivos)) {   
            foreach($this->_lista_de_archivos as $archivo) {
                if(filemtime($archivo) > filemtime($this->_ruta_para_archivo)) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Combina el contenido de una lista de archivos.
     * @param array $archivos Lista de archivos que se revisarán
     * @return string Regresa un string con el contenido de los archivos
     *         que se revisaron.
     * TODO: Poner error si no encontró los archivos, si hubo un problema al
     *       tratar de combinarlos...
     */
    private function combinar_contenido_de_archivos() {
        $archivos_existen = $this->comprobar_si_existen_archivos($this->_lista_de_archivos);
        if ($archivos_existen === true){
            $contenido = "";
            foreach($this->_lista_de_archivos as $archivo) {
                $contenido .= file_get_contents($archivo);
            }
            return $contenido;
        } else {
            // error
            die("error combinar archivos");
        }
    }
    
    /**
     * Revisa los archivos de una lista y ejecuta la funcion parse_lineas en cada uno.
     * @param array $archivos Lista de archivos que se revisará.
     * @param array $array_de_valores Array de valores que serán sustituidos en los archivos.
     * @return string Regresa un string con los archivos combinados en uno solo
     *         y los valores del array cambiados.
     * TODO: Agregar error cuando no encuentre un archivo o si no lo puede usar.
     */
    private function parse_archivos($archivos, $array_de_valores) {
        $archivos_existen = $this->comprobar_si_existen_archivos($archivos);
        if($archivos_existen === true) {
            $contenido = "";
            foreach($archivos as $archivo) {
                $contenido .= $this->parse_lineas($archivo, $array_de_valores);
            }
            return $contenido;
        }
    }
 
    /**
     * Revisa cada linea de un archivo, busca y reemplaza los valores de un array.
     * @param string $archivo Ruta del archivo que se va a revisar.
     * @param array $array_de_valores array de los valores que se desean reemplazar.
     * @return string Regresa un string del contenido del archivo.
     */
    private function parse_lineas($archivo, $array_de_valores) {
        $contenido_archivo = "";
            $lineas = file($archivo);
            foreach($lineas as $linea) {
                $contenido_archivo .= $this->encuentra_y_reemplaza_variables($linea, $array_de_valores);
            }
        return $contenido_archivo;
    }
    
    /**
     * Busca y reemplaza los valores de php con valores del array.
     * @param string $linea una cadena de un archivo css.
     * @return string Si encuentra una variable de php revisa si el valor se encuentra
     *                en el array de valores y hace el cambio al valor asignado en el array.
     */
    /**
     * Busca y reemplaza los valores del string con los de un array.
     * @param string $linea String que se revisará si hay valores que se pueden sustituir.
     * @param array $array_de_valores Array de valores que se sustituirán en el string.
     * @return string Regresa la linea con los valores sustituidos.
     * TODO: Ver como se puede usar con js.
     */
    private function encuentra_y_reemplaza_variables($linea, $array_de_valores) {
        preg_match_all('/\$([-a-zA-Z0-9_]*)/', $linea, $variable);
        $encontrado = $variable[0];
        $cuenta = count($encontrado);
        for($i = 0; $i < $cuenta; $i++) {
            $temp = str_replace("$", "", $variable[0][$i]);
            if(array_key_exists($temp, $array_de_valores)) {
                $linea = preg_replace('/(\$'.$temp.')/', $array_de_valores[$temp], $linea);
            } else {
                // error.
            }
        }
        return $linea;
    }
    
    /**
     * Guarda un archivo.
     * @param string $ruta_para_el_archivo Ruta donde se va a guardar el archivo.
     * @param string $datos Contenido que se va a guardar en el archivo.
     * TODO: Ver como mejorar este método.
     */
    private function guardar_archivo($datos){
        file_put_contents($this->_ruta_para_archivo, $datos);
    }
    
    private function getRutaArchivo() {
        if($this->_extension == "js") {
            $ruta = JS_PUBLIC_PATH.DS.pathinfo($this->_ruta_para_archivo,PATHINFO_BASENAME);
        } else {
            $ruta = CSS_PUBLIC_PATH.DS.pathinfo($this->_ruta_para_archivo,PATHINFO_BASENAME);
        }
        return $ruta;
    }
    
    protected function getFile() {
        if(file_exists($this->_ruta_para_archivo)) {
            if($this->comparar_fechas_de_modificacion() && !empty($this->_array_de_valores)) {
                // $this->guardar_archivo($this->_ruta_para_archivo, $this->parse_archivos($this->_lista_de_archivos, $this->_array_de_valores));
                die("you should not be here");
            } else if(!$this->comparar_fechas_de_modificacion() && empty($this->_array_de_valores)){
                $this->guardar_archivo($this->combinar_contenido_de_archivos());
            } else if(!$this->comparar_fechas_de_modificacion() && !empty($this->_array_de_valores)) {
                // $this->guardar_archivo($this->_ruta_para_archivo, $this->parse_archivos($this->_lista_de_archivos, $this->_array_de_valores));
                die("you should not be here either");
            }
        } else if(!file_exists($this->_ruta_para_archivo) && empty($this->_array_de_valores)) {
            $this->guardar_archivo($this->combinar_contenido_de_archivos());
        } else if(!file_exists($this->_ruta_para_archivo) && !empty($this->_array_de_valores)) {
            die("file doesn't exists and there is an array for the values");
        } else {
            die("something went wrong");
        }
        return $this->getRutaArchivo();
    }
}