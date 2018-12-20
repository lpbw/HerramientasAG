<?php
    include_once 'Usuario.php';
    include_once 'Producto.php';
    include_once 'Cliente.php';
    include_once 'Cotizacion.php';
    include_once "coneccion.php";
    include_once 'functions_cotizacion.php';

    $cotizacion = $_POST['id_cotizacion'];
    $producto = $_POST['id_producto'];
    $partida = $_POST['partida'];

    $consulta = "DELETE FROM Cotizaciones_Productos WHERE id_cotizacion=$cotizacion AND id_producto=$producto AND partida=$partida";
    $result = mysql_query($consulta) or print("$consulta".mysql_error());


    $cotizacion = new Cotizacion();
    /* obtiene exclusivamente los datos de la cotizacion, es decir, no obtiene los productos.*/
    $cotizacion->get($_POST['id_cotizacion'],0);
    /* obtiene los productos.*/
    $cotizacion->setCarritoFromCotizacion();
    $_SESSION['cotizacion'] = $cotizacion;
    echo $consulta;
    
?>