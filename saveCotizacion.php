<?
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cliente.php';
include_once 'Cotizacion.php';
include_once "coneccion.php";

include_once 'functions_cotizacion.php';
session_start();
$sub=$_GET['sub'];
$total=$_GET['total'];
$iva=$_GET['iva'];
    guardarCotizacion();
    $_SESSION['cotizacion']->subtotal=$sub;
    $_SESSION['cotizacion']->total=$total;
    $_SESSION['cotizacion']->iva=$iva;
    $_SESSION['cotizacion']->id_contacto = $_GET['idcontacto'];
    saveCotizacionOnDB();
?>