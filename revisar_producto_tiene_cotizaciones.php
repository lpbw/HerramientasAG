<?php

include_once "Usuario.php";
include_once "coneccion.php";
include_once 'Producto.php';

$producto = new Producto();
$producto->get($_REQUEST['idPBorrar']);
echo $producto->hasCotizaciones()?0:1;
?>
