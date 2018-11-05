<?
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once "coneccion.php";
include_once 'changeTipoMoneda.php';

session_start();

include_once 'functions_agregar_carrito.php';
$valor_moneda = $_SESSION['dollar'];

$cantidad = $_REQUEST['cantidad'];

/*
 * LOS PRODUCTOS QUE ESTÁN EN LA COTIZACIÓN Y CARRITO GUARDAN EN PRECIO
 * EL VALOR ACTUAL PARA LA COTIZACIÓN, ES DECIR, CONVERTIDO A MXN O 
 * A USD SEGÚN EL CASO
 */
$_SESSION['buscadorCotizaciones'] = array();

if(isset($_REQUEST['id']) && isset($_REQUEST['cantidad']) ){
    $producto = new Producto();
    $producto->get($_REQUEST['id']);
    $producto->cantidad = $cantidad;
    $producto->nombre_proveedor = getNombreProveedor($producto->id_proveedor);

    $tipo_moneda = $_SESSION['cotizacion']->tipo_moneda;
    $valor_moneda = $_SESSION['dollar'];

    $producto -> precio_original = $producto->precio;
    $producto -> precio = changeTipoMoneda($producto, $tipo_moneda, $valor_moneda);
    $producto -> partida = count($_SESSION['carrito'])+1;

                    /*DEBUG*/
                    if(($producto->tipo_moneda_usa != $_SESSION['cotizacion']->tipo_moneda 
                            && $producto->precio == $producto->precio_original) || intval($producto->precio) == 0 ){
//                        include_once 'mailDebug.php';
//                        $subject = "ERROR en cotizacion {$_SESSION['cotizacion']->id}";
//                        $message = "El producto ID $producto->id
//                            ESP: $producto->nombre 
//                            ING: $producto->descripcion 
//                            CODIGO INTERNO: $producto->codigo_interno
//                            se insertado con moneda inadecuada.
//                            MONEDA COT {$_SESSION['cotizacion']->tipo_moneda}
//                            MONEDA PROD {$producto->tipo_moneda_usa}
//                            PRECIO $producto->precio
//                            PRECIO ORIG $producto->precio_original
//                            VALOR DOLLAR {$_SESSION['dollar']}
//
//                            DEL USUARIO {$_SESSION['usuario']['nombre']}
//                            mail {$_SESSION['usuario']['email']}
//                            id {$_SESSION['usuario']['id']}
//                            rol {$_SESSION['usuario']['rol_nombre']}";
//
//                        mailDebug($subject, $message);
                    }
                    /*DEBUG*/

    update_SubtotalTotalIva_Of_Cotizacion( $producto->precio , $producto -> cantidad, $producto->recargo,  $producto->tipo_moneda_usa);
    // $consulta  = "insert into debug(dato) values('".$producto->recargo."')";
        //$resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->recargo."</h1><br>" . mysql_error());
    $producto -> agregarACarrito();//agregando a SESSION carrito
    array_push( $_SESSION['cotizacion']->productos , $producto);//agregando a SESSION Cotizacion

//    $_SESSION['cotizacion'] -> updateProductos( $_SESSION['carrito'] );


    /*
     * THE responseText
     */
    $result = array();
    array_push($result, $producto->cantidad);
    if( doubleval( $producto->precio ) == 0 )
        array_push ( $result, intval ($producto->id) );
    
    if(isset($_REQUEST['backTo'])){
        ?><script>window.location = '<? echo $_REQUEST['backTo'];?>';</script><?    
    } else 
    echo json_encode($result);
}

$_SESSION['cotizacion']->updateProductos($_SESSION['carrito']);

/**
 * 
 * @param type $precio
 * @param type $cantidad
 * @param type $recargo
 */
function update_SubtotalTotalIva_Of_Cotizacion( $precio , $cantidad, $recargo, $tipo_moneda_usa ){  
	
	if($tipo_moneda_usa != $_SESSION['cotizacion']->tipo_moneda ){
	 $subtotal = ($precio+ ($recargo *  $_SESSION['dollar']) ) * $cantidad + $_SESSION['cotizacion'] -> subtotal;
	}else{
	 $subtotal = ($precio+$recargo ) * $cantidad + $_SESSION['cotizacion'] -> subtotal;
	}				
    //$subtotal = ($precio+$recargo) * $cantidad + $_SESSION['cotizacion'] -> subtotal;
//	$consulta  = "insert into debug(dato) values('en updateSubtotal".$recargo."')";
//	$resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de </h1><br>" . mysql_error());
    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;
    
    $_SESSION['cotizacion'] -> subtotal = $subtotal;
    $_SESSION['cotizacion'] -> total = $total;
    $_SESSION['cotizacion'] -> iva = $iva;
}
?>