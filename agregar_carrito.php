<?
    include_once 'Usuario.php';
    include_once 'Producto.php';
    include_once 'Cotizacion.php';
    include_once "coneccion.php";
    include_once 'changeTipoMoneda.php';
    include_once 'functions_agregar_carrito.php';
    session_start();
    $valor_moneda = $_SESSION['dollar'];
    //$cantidad = $_REQUEST['cantidad'];
    $id_p = "";
    $cant = "";

    if ($_GET['id']!="")
    {
        $id_p = $_GET['id'];
    }
    if ($_GET['cantidad']!="") {
        $cant = $_GET['cantidad'];
    }
    if ($_GET['contacto']!="") {
        $Idcontacto = $_GET['contcato'];
    }
    
    /*
    * LOS PRODUCTOS QUE ESTÁN EN LA COTIZACIÓN Y CARRITO GUARDAN EN PRECIO
    * EL VALOR ACTUAL PARA LA COTIZACIÓN, ES DECIR, CONVERTIDO A MXN O 
    * A USD SEGÚN EL CASO
    */
    $_SESSION['buscadorCotizaciones'] = array();

    if($id_p != "" && $cant != "")
    {
        $producto = new Producto();
        $producto->get($id_p);
        $producto->cantidad = $cant;
        
        /**
         * obtiene el nombre del provedor.
         * funcion: getNombreProveedor(id de provedor obtenido del objeto producto);
        */
        $producto->nombre_proveedor = getNombreProveedor($producto->id_proveedor);
        $tipo_moneda = $_SESSION['cotizacion']->tipo_moneda;
        $valor_moneda = $_SESSION['dollar'];
        $producto -> precio_original = $producto->precio;
        
        $producto -> precio = changeTipoMoneda($producto, $tipo_moneda, $valor_moneda);

       
        $producto -> partida = count($_SESSION['carrito'])+1;
                  
        update_SubtotalTotalIva_Of_Cotizacion( $producto->precio , $producto -> cantidad, $producto->recargo,  $producto->tipo_moneda_usa,$valor_moneda);
        $producto -> agregarACarrito();//agregando a SESSION carrito
       
        array_push( $_SESSION['cotizacion']->productos , $producto);//agregando a SESSION Cotizacion
        
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
        function update_SubtotalTotalIva_Of_Cotizacion( $precio , $cantidad, $recargo, $tipo_moneda_usa,$valor_moneda )
        {  

            // cambio de dolar a pesos
            if ($tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0") 
            {
                if ($recargo == 0)
                {
                    $subtotal = ($precio * $cantidad) + $_SESSION['cotizacion'] -> subtotal;
                   
                }
                else
                {
                    $subtotal = ((($precio + ($recargo*$valor_moneda)) * $cantidad) + $_SESSION['cotizacion'] -> subtotal);
                   
                }
            }
            //pesos a dolar
            else if($tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1")
            {
                if ($recargo == 0)
                {
                    $subtotal = (($precio + $valor_moneda ) * $cantidad) + $_SESSION['cotizacion'] -> subtotal;
                   
                }
                else
                {
                    $subtotal = ($precio + ($recargo *  $valor_moneda) ) * $cantidad + $_SESSION['cotizacion'] -> subtotal;
                    
                }
            }
            else if($tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
            {
                if ($recargo == 0)
                {
                    $subtotal = ($precio * $cantidad) + $_SESSION['cotizacion'] -> subtotal;
                    
                }
                else
                {
                    $subtotal = ($precio+($recargo *  $valor_moneda) ) * $cantidad + $_SESSION['cotizacion'] -> subtotal;
                    
                }
            }
            
            $iva = $subtotal * 0.16;
            $total = $subtotal + $iva;
            $_SESSION['cotizacion'] -> subtotal = $subtotal;
            $_SESSION['cotizacion'] -> total = $total;
            $_SESSION['cotizacion'] -> iva = $iva;
        }
?>