<?

include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';

//session_start();

function reloadCarrito($idProducto){
    if(isset($_SESSION['carrito'])){
        $tipo_moneda = $_SESSION['cotizacion']->tipo_moneda;
    	$valor_moneda = $_SESSION['dollar'];
        
        foreach ($_SESSION['carrito'] as $n => $producto) {
            if($producto->id == $idProducto){
                $producto = new Producto();
                $producto -> get( $idProducto );
                
                        //$tempp_precio=$producto -> precio;
                        $tempp_usa=$producto->tipo_moneda_usa;
                        $tempp_original=$producto->precio;
                        $tempp=changeTipoMoneda1($tempp_usa, $tipo_moneda, $valor_moneda, $tempp_original);/////borrar
                        $producto -> precio = changeTipoMoneda1($tempp_usa, $tipo_moneda, $valor_moneda, $tempp_original);
                        
                $_SESSION['carrito'][$n]->nombre =$producto->nombre;
                $_SESSION['carrito'][$n]->id_catalogo_productos =$producto->id_catalogo_productos;
                $_SESSION['carrito'][$n]->precio =$producto->precio;
                $_SESSION['carrito'][$n]->costo =$producto->costo;
                $_SESSION['carrito'][$n]->id_proveedor =$producto->id_proveedor;
                $_SESSION['carrito'][$n]->modificado=$producto->modificado;
                $_SESSION['carrito'][$n]->archivo_ficha_tecnica =$producto->archivo_ficha_tecnica;
                $_SESSION['carrito'][$n]->imagen=$producto->imagen;
                $_SESSION['carrito'][$n]->descripcion=$producto->descripcion;
                $_SESSION['carrito'][$n]->unidad_metrica =$producto->unidad_metrica;
                $_SESSION['carrito'][$n]->tipo_moneda_usa=$producto->tipo_moneda_usa;
                $_SESSION['carrito'][$n]->origen = $producto->origen;
                $_SESSION['carrito'][$n]->exportar_microsip= $producto->exportar_microsip;
                $_SESSION['carrito'][$n]->stock = $producto->stock;
                $_SESSION['carrito'][$n]->codigo_familia= $producto->codigo_familia;
                $_SESSION['carrito'][$n]->codigo_microsip= $producto->codigo_microsip;
                $_SESSION['carrito'][$n]->codigo_descuento= $producto->codigo_descuento;
                $_SESSION['carrito'][$n]->codigo = $producto->codigo;
				//$_SESSION['carrito'][$n]->comentario = $tempp.",".$tempp_usa.",". $tipo_moneda.",". $valor_moneda.",". $tempp_original;
                break;
				
            }
        }
        $_SESSION['cotizacion']->productos = $_SESSION['carrito'];
    }
}

function guardarCarrito(){// solo contiene variables del carrito
    /*
     * Cuando entre a este metodo porque se borr� un producto de la cotizaci�n
     * los �ndices de $_SESSION['cotizacion'] y de $_POST coincidir�n con $pos
     * de otra manera habr� errores
     */
    $subtotal = 0;
    if( isset ($_SESSION['carrito'] ) ){
        foreach ($_SESSION['carrito'] as $pos => $producto) {
            $producto->cantidad = $_POST['cantidad'.$pos];
            $producto->comentario = $_POST['comentarioProducto'.$pos];
            $producto->descuento = intval($_POST['descuento'.$pos])/100;
            
            
            
//			//$producto->recargo = $_POST['recargo'.$pos];
//			-----------------
//			DEBUG
//			------------------
//            if($_SESSION['usuario']->email == 'mario.garcia@bluewolf.com.mx'){
//			$consulta  = "insert into debug(dato) values('[$pos] id_producto = $producto->id cantidad $producto->cantidad')";
//			mysql_query($consulta);
//                        if(mysql_error()!=""){
//                            $consulta  = "insert into debug(dato) values('guardaCarrito() ERROR DE ID ".  mysql_insert_id()." ".
//                                mysql_real_escape_string(mysql_error())."')";
//                        }
//            }
            
             
             $subtotal += (($producto -> precio * ( 1 - floatval( $producto -> descuento ) ))+ $producto->recargo*1) * $producto -> cantidad;
        }
    } else if( isset ($_SESSION['cotizacion']->productos ) ){
        foreach ($_SESSION['cotizacion']->productos as $pos => $producto) {
            $producto->cantidad   = $_POST['cantidad'.$pos];
            $producto->comentario = $_POST['comentarioProducto'.$pos];
			$producto->recargo = $_POST['recargo'.$pos];
			$consulta  = "insert into debug(dato) values('guardaCarrito functions2 ".$producto->recargo."')";
			$resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de </h1><br>" . mysql_error());
            $producto->descuento  = intval($_POST['descuento'.$pos])/100;
            $subtotal += round( (($producto -> precio * ( 1 - floatval( $producto -> descuento ) ))+ $producto->recargo*1) * $producto -> cantidad,0);
            
            $_SESSION['carrito'][$pos]->cantidad   = $_POST['cantidad'.$pos];
            $_SESSION['carrito'][$pos]->comentario = $_POST['comentarioProducto'.$pos];
            $_SESSION['carrito'][$pos]->descuento  = intval($_POST['descuento'.$pos])/100;
			$_SESSION['carrito'][$pos]->recargo  = $producto->recargo;
			$consulta  = "insert into debug(dato) values('guardaCarrito functions3 ".$_SESSION['carrito'][$pos]->recargo."')";
			$resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de </h1><br>" . mysql_error());
			$subtotal += (($producto -> precio * ( 1 - floatval( $producto -> descuento ) ))+ $producto->recargo*1) * $producto -> cantidad;
        }
    }
    return floatval($subtotal);
}

function saveCotizacionOnDB(){
    if(!isset($_SESSION['usuario']) || $_SESSION['usuario']->id=="" 
            || $_SESSION['usuario']->id==0  || $_SESSION['usuario']->id=="0" ){
            ?><script>alert('Tu sesion ha expirado');
                window.location = 'logout.php';</script><?
    }
    $cotizacion = new Cotizacion();
    
    if( isset($_SESSION['cotizacion']) && $_SESSION['cotizacion']->id == "" ){
		
        if( $cotizacion->create( $_SESSION['cotizacion'] )){
            $_SESSION['cotizacion'] = $cotizacion;
            //THE PRODUCTS NEVER WILL BE INSERTED OR UPDATED IN THIS SECCION
            //CUZ $_SESSION['carrito'] WILL BE EMPTY
            
        } else {
            ?><script>alert('ERROR: Cotizacion crear NO guardada');</script><?
        }
        
    } else {
        $cotizacion->get( $_SESSION['cotizacion'] -> id , $_SESSION['cotizacion']->id_version );
        if($cotizacion->update( $_SESSION['cotizacion'] )){
            //if( isset( $_SESSION['carrito'] ) )
                //$cotizacion->updateProductos( $_SESSION['carrito'] );
            return "aqui";
        } else {
            ?><script>alert('ERROR: Cotizacion NO guardada');</script><?
        }
    }
}

function previsualizarCotizacion(){
    if($_SESSION['cotizacion']->id == ""){
        echo"<script>window.open(\"precotizacion.php\");</script>";
     }
}

function changeTipoMoneda1($tipo_moneda_usa, $moneda_a_cambiar_es_usa, $valor_moneda, $precio_original){
   // $producto = $prod;
    
    if($tipo_moneda_usa == 0 && $moneda_a_cambiar_es_usa == 1)
        $precio =  $precio_original / floatval($valor_moneda);
    
    else if($tipo_moneda_usa == 1 && $moneda_a_cambiar_es_usa==0)
        $precio = floatval($precio_original) * floatval($valor_moneda);
    
    else if($tipo_moneda_usa == $moneda_a_cambiar_es_usa)
        $precio = floatval($precio_original);
    
    return floatval($precio);
}



function guardarCotizacion(){
    
    $id_usuario=$_SESSION['usuario']->id;
    $prioridad=$_POST['id_prioridad'];
    $id_estatus=$_POST['id_estatus'];
    $id_cliente=$_POST['id_cliente'];
	$id_contacto=$_POST['id_contacto'];
    $notas_adicionales=$_POST['comentarioCotizacion'];
    $tipo_moneda = $_POST['tipo_moneda'];
    $valor_moneda = $_SESSION['dollar'];
    $idioma = $_POST['idioma'];
    $terminos_entrega = $_POST['terminos_entrega'];
    $LAB = $_POST['LAB'];
    $vigencia = $_POST['vigencia'];
    $atencion = $_POST['atencion'];
    $referencia = $_POST['referencia'];
    $con_iva = $_POST['conIva'];
    /*
     * GUARDAR EL CARRITO
     */
    $subtotal = guardarCarrito();
    $iva = $subtotal * 0.16;
    $total = $subtotal + $iva;
    
    if($tipo_moneda!=1)
        $valor_moneda = 1;
    
    if(isset($_SESSION['cotizacion'])){
        $_SESSION['cotizacion']->id_usuario = $id_usuario;
        $_SESSION['cotizacion']->prioridad = $prioridad;
        $_SESSION['cotizacion']->id_cliente = $id_cliente;
		$_SESSION['cotizacion']->id_contacto = $id_contacto;
		
        $_SESSION['cotizacion']->id_estatus = $id_estatus;
        $_SESSION['cotizacion']->notas_adicionales = $notas_adicionales;
        $_SESSION['cotizacion']->tipo_moneda = $tipo_moneda;
        $_SESSION['cotizacion']->valor_moneda = floatval($valor_moneda);
        $_SESSION['cotizacion']->iva = floatval($iva);
        $_SESSION['cotizacion']->subtotal = floatval($subtotal);
        $_SESSION['cotizacion']->total = floatval($total);
        $_SESSION['cotizacion']->idioma = $idioma;
        $_SESSION['cotizacion']->LAB = $LAB ;
        $_SESSION['cotizacion']->terminos_entrega = $terminos_entrega;
        $_SESSION['cotizacion']->idioma = $idioma;
        $_SESSION['cotizacion']->vigencia = $vigencia;
        $_SESSION['cotizacion']->atencion = $atencion;
        $_SESSION['cotizacion']->referencia = $referencia;
		$_SESSION['cotizacion']->con_iva = $con_iva;
        $_SESSION['cotizacion']->productos = $_SESSION['carrito'];
        
    } else {
        $cotizacion = new Cotizacion();
        $cotizacion->id_usuario = $id_usuario;
        $cotizacion->prioridad = $prioridad;
        $cotizacion->id_cliente = $id_cliente;
		$cotizacion->id_contacto = $id_contacto;
		
        $cotizacion->id_estatus = $id_estatus;
        $cotizacion->notas_adicionales = $notas_adicionales;
        $cotizacion->tipo_moneda = $tipo_moneda;
        $cotizacion->valor_moneda = $valor_moneda;
        $cotizacion->iva = floatval($iva);
        $cotizacion->subtotal = floatval($subtotal);
        $cotizacion->total = floatval($total);
        $cotizacion->idioma = $idioma;
        $cotizacion->LAB = $LAB;
        $cotizacion->terminos_entrega = $terminos_entrega;
        $cotizacion->idioma = $idioma;
        $cotizacion->vigencia = $vigencia;
        $cotizacion->atencion = $atencion;
        $cotizacion->referencia = $referencia;
		$cotizacion->con_iva = $con_iva;
        $cotizacion->productos = $_SESSION['carrito'];
        $_SESSION['cotizacion'] =  $cotizacion;
        
    }
}

function guardarCotizacionIva($id_prioridad,$id_estatus,$id_cliente,$id_contacto,$notas_adicionales,$tipo_moneda,$idioma,$terminos_entrega,$LAB,$vigencia,$atencion,$referencia,$con_iva){   
    $id_usuario=$_SESSION['usuario']->id;
    $valor_moneda = $_SESSION['dollar'];
    /*
     * GUARDAR EL CARRITO
     */
    $subtotal = $_SESSION['cotizacion']->subtotal;
    $iva = $_SESSION['cotizacion']->iva;
    $total = $_SESSION['cotizacion']->total;
    
    if($tipo_moneda!=1)
        $valor_moneda = 1;
    
    if(isset($_SESSION['cotizacion'])){
        $_SESSION['cotizacion']->id_usuario = $id_usuario;
        $_SESSION['cotizacion']->prioridad = $prioridad;
        $_SESSION['cotizacion']->id_cliente = $id_cliente;
		$_SESSION['cotizacion']->id_contacto = $id_contacto;
		
        $_SESSION['cotizacion']->id_estatus = $id_estatus;
        $_SESSION['cotizacion']->notas_adicionales = $notas_adicionales;
        $_SESSION['cotizacion']->tipo_moneda = $tipo_moneda;
        $_SESSION['cotizacion']->valor_moneda = floatval($valor_moneda);
        $_SESSION['cotizacion']->iva = floatval($iva);
        $_SESSION['cotizacion']->subtotal = floatval($subtotal);
        $_SESSION['cotizacion']->total = floatval($total);
        $_SESSION['cotizacion']->idioma = $idioma;
        $_SESSION['cotizacion']->LAB = $LAB ;
        $_SESSION['cotizacion']->terminos_entrega = $terminos_entrega;
        $_SESSION['cotizacion']->idioma = $idioma;
        $_SESSION['cotizacion']->vigencia = $vigencia;
        $_SESSION['cotizacion']->atencion = $atencion;
        $_SESSION['cotizacion']->referencia = $referencia;
		$_SESSION['cotizacion']->con_iva = $con_iva;
        $_SESSION['cotizacion']->productos = $_SESSION['carrito'];
        //return "sesion";
    } else {
        $cotizacion = new Cotizacion();
        $cotizacion->id_usuario = $id_usuario;
        $cotizacion->prioridad = $prioridad;
        $cotizacion->id_cliente = $id_cliente;
		$cotizacion->id_contacto = $id_contacto;
		
        $cotizacion->id_estatus = $id_estatus;
        $cotizacion->notas_adicionales = $notas_adicionales;
        $cotizacion->tipo_moneda = $tipo_moneda;
        $cotizacion->valor_moneda = $valor_moneda;
        $cotizacion->iva = floatval($iva);
        $cotizacion->subtotal = floatval($subtotal);
        $cotizacion->total = floatval($total);
        $cotizacion->idioma = $idioma;
        $cotizacion->LAB = $LAB;
        $cotizacion->terminos_entrega = $terminos_entrega;
        $cotizacion->idioma = $idioma;
        $cotizacion->vigencia = $vigencia;
        $cotizacion->atencion = $atencion;
        $cotizacion->referencia = $referencia;
		$cotizacion->con_iva = $con_iva;
        $cotizacion->productos = $_SESSION['carrito'];
        $_SESSION['cotizacion'] =  $cotizacion;
        //return "no sesion";
    }

    //si la session tiene la cotizacion
    if (isset($_SESSION['cotizacion'])){
        $consulta = "UPDATE Cotizaciones SET iva=$iva,subtotal=".$_SESSION['cotizacion']->subtotal.",total=".$_SESSION['cotizacion']->total.",con_iva=".$_SESSION['cotizacion']->con_iva." WHERE id=".$_SESSION['cotizacion']->id;
        $resultado = mysql_query($consulta) or print("$consulta" . mysql_error());
        return $consulta;
    }
}
?>