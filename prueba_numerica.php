<?

include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
include "coneccion.php";
include 'changeTipoMoneda.php';

session_start();
    $producto = new Producto();
    $producto->get($_REQUEST['id']);
    print_r($producto);
    echo "<br><br>";
    
    $tipo_moneda = $_SESSION['cotizacion']->tipo_moneda;
//    $valor_moneda = $_SESSION['cotizacion']->valor_moneda;
    $valor_moneda = $_SESSION['dollar'];
    
    $producto -> precio_original = $producto->precio;
    $producto -> precio = changeTipoMoneda($producto, $tipo_moneda, $valor_moneda);
    
    print_r($producto);
    echo "<br><br>";
?>