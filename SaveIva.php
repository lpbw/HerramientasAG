<?php
    include_once 'Usuario.php';
    include_once 'Producto.php';
    include_once 'Cliente.php';
    include_once 'Cotizacion.php';
    include_once "coneccion.php";
    include_once 'functions_cotizacion.php';
    if($_POST['con_iva']!="" || $_POST['con_iva']!=NULL){
        $_SESSION['cotizacion']->con_iva=$_POST['con_iva'];
        $_SESSION['cotizacion']->subtotal=$_POST['subtotal'];
        $_SESSION['cotizacion']->total=$_POST['total'];
        $_SESSION['cotizacion']->iva=$_POST['iva'];
        //
        $valor = guardarCotizacionIva($_POST['id_prioridad'],$_POST['id_estatus'],$_POST['id_cliente'],$_POST['id_contacto'],$_POST['comentarioCotizacion'],$_POST['tipo_moneda'],$_POST['idioma'],$_POST['terminos_entrega'],$_POST['LAB'],$_POST['vigencia'],$_POST['atencion'],$_POST['referencia'],$_POST['con_iva']);
        //$val = saveCotizacionOnDB();
        echo $valor;
    }
?>