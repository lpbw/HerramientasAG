<?
include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Cliente.php';

session_start();

include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['mostrador']);

if(isset($_SESSION['cotizacion'])){
    include 'mailCotizacion.php';
    include_once "getFormatedNumberForMoney.php";
    $cliente = new Cliente();
    $cliente -> get($_SESSION['cotizacion'] -> id_cliente);
    $cotizacion = new Cotizacion();
    $cotizacion = $_SESSION['cotizacion'];
    echo getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, false);
}
?>
