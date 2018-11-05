<?php
include_once 'coneccion.php';

if($_REQUEST['tipo']=='catalogo'){
    echo  WebServiceOf::GET("SELECT id, nombre FROM CatalogoProductos WHERE id_proveedor = ".$_REQUEST['idProveedor']);
    
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebServiceOf
 *
 * @author Miguel L
 */
class WebServiceOf {
    STATIC function GET($query) {
        $result = mysql_query($query) or die(mysql_error());
        $return = array();
        while ($row = mysql_fetch_object($result)) {
            array_push($return, $row);
        }
        return json_encode($return);
    }
}

?>
