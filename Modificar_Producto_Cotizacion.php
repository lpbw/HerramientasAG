<?php
    include 'coneccion.php';
    session_start();
    $flag=0;
    $Descuento=$_POST['descuento'];
    $Cantidad=$_POST['cantidad'];
    $Precio=$_POST['precio'];
    $Comentario=$_POST['comentario'];
    $ProductoCotizacion=$_POST['productocotizacion'];
    $ConsultaProductoCotizacion="SELECT id FROM Cotizaciones_Productos WHERE id=$ProductoCotizacion";
    $ResultadoConsulta = mysql_query($ConsultaProductoCotizacion) or print("Modificar_Producto_Cotizacion: $ConsultaProductoCotizacion " . mysql_error());
    //Validar que exista registro.
    if(@mysql_num_rows($ResultadoConsulta)>=1){
         $ActualizarProductoCotizacion="UPDATE Cotizaciones_Productos SET descuento='$Descuento',cantidad='$Cantidad',precio='$Precio',comentario='$Comentario' WHERE id=$ProductoCotizacion";
         $ResultadoEliminar = mysql_query($ActualizarProductoCotizacion) or print("Modificar_Producto_Cotizacion: $ActualizarProductoCotizacion " . mysql_error());
        $flag=1;
    }
   
    echo $flag;
?>