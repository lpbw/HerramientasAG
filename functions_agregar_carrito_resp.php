<?

function setPrecioAndCosto($prod){
    if(isset($_SESSION['cotizacion'])){
        $prod->precio = $prod->precio * floatval($_SESSION['cotizacion']->valor_moneda);
        $prod->costo = $prod->costo * floatval($_SESSION['cotizacion']->valor_moneda);
    }
}

function getNombreProveedor($idProveedorCarrito){
    $result = mysql_query("SELECT nombre FROM Proveedores WHERE id = $idProveedorCarrito");
    if($result)
        $nombreProveedor = mysql_fetch_assoc($result);
    else $nombreProveedor['nombre'] = "<b>SIN PROVEEDOR O PRODUCTO ELIMINADO</b>";
    
    return $nombreProveedor['nombre'];
}
?>