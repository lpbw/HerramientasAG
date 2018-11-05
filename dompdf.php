<?
session_start();

error_reporting(E_ALL);
ini_set('display_errors','1');

include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Cliente.php';
include_once 'Contacto.php';
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['vendedor25']);

//require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
$cliente=new Cliente();
$cotizacion=new Cotizacion();
$cotizacion = $_SESSION['cotizacion'];
print_r($_SESSION['cotizacion']);
$cliente = $cotizacion -> id_cliente;
print_r($cliente);
if($html=$cotizacion->getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, true)!="")
	echo "html generado";
//$pdf = new DOMPDF();
//$pdf ->load_html(utf8_decode($html));
//$pdf ->render();
//$pdf ->stream("cotizacion.pdf");
?>