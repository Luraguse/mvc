<?php
/**
 * Clase para combinar archivos de css, sirve para reducir la cantidad de requests
 * que se hacen al servidor al cargar los links para el estilo y facilitar la 
 * forma en que se cambian los valores en los templates.
 * Versión mínima de PHP: 5.3
 * 
 * Autor: Luis Gutiérrez
 * Hitosoft - 2012
 */
class CombinarCss extends CombinarArchivos {

    function __construct() {
        parent::__construct("css",CSS_FULL_PATH);
    }
    
    /**
     * Busca en la BD la lista de archivos de un template.
     * @param string $id_template Id del template que se buscará en la BD.
     * @param string $id_cliente Id del cliente que se buscará en la BD.
     * @return string Regresa un array con la lista de archivos del template.
     * TODO: Cambiar el método para el query.
     */
    /*
    private function buscar_archivos_en_bd($id_template, $id_cliente) {
        $lista_archivos = mysql_result(mysql_query("SELECT lista_archivos FROM css WHERE id_template = '".$id_template."' AND id_cliente = '".$id_cliente."'"), 0);
        return explode(",", $lista_archivos);
    }*/
    
    /**
     * Busca en la BD el array de valores que se cambiarán en el template.
     * @param string $id_template Id del template que se buscará en la BD.
     * @param string $id_cliente Id del cliente que se buscará en la BD.
     * @return array Regresa el array de valores que se cambiarán en el template.
     */
    /*
    private function array_de_variables($id_template, $id_cliente) {
        $json = mysql_result(mysql_query("SELECT variables FROM css WHERE id_template = '".$id_template."' AND id_cliente = '".$id_cliente."'"), 0);
        return json_decode($json, true);
    }
    */
    /**
     * Revisa si existe el archivo de css comprimido.
     *      Si el archivo existe, revisa la fechas de modificacion del archivo y 
     *      la compara con la de modificación del template en la Bd.
     *          Si la fecha de modificación del template es más reciente crea otro archivo
     *          Si la fecha de modificación del archivo es más reciente regresa el link.
     *      Si el archivo no existe, lo crea y regresa el link
     * @return string Regresa el link al css.
     */
    public function regresarCss() {
        return $this->getFile();
    }
}