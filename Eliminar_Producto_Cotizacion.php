<?php
    include 'coneccion.php';
    session_start();
    $flag=0;
    $IdBorrar=$_POST['IdBorrar'];
    $IdCotizacion=$_POST['IdCotizacion'];
    $ConsultaProductoCotizacion="SELECT id FROM Cotizaciones_Productos WHERE id=$IdBorrar";
    $ResultadoConsulta = mysql_query($ConsultaProductoCotizacion) or print("Eliminar_Producto_Cotizacion: $ConsultaProductoCotizacion " . mysql_error());
    //Validar que exista registro.
    if(@mysql_num_rows($ResultadoConsulta)>=1){
         $EliminarProductoCotizacion="DELETE FROM Cotizaciones_Productos WHERE id=$IdBorrar";
         $ResultadoEliminar = mysql_query($EliminarProductoCotizacion) or print("Eliminar_Producto_Cotizacion: $EliminarProductoCotizacion " . mysql_error());
        $flag=1;
        //Actualizar partidas
        $ConsultaProductoCotizacion1="SELECT id FROM Cotizaciones_Productos WHERE id_cotizacion=$IdCotizacion";
        $ResultadoConsulta1 = mysql_query($ConsultaProductoCotizacion1) or print("Eliminar_Producto_Cotizacion: $ConsultaProductoCotizacion1 " . mysql_error());
            $count=1;
            while($filas = mysql_fetch_assoc($ResultadoConsulta1)){
                 $UpdateProductoCotizacion="UPDATE Cotizaciones_Productos SET partida=$count WHERE id_cotizacion=$IdCotizacion AND id=".$filas['id'];
                 $Resultadoupdate = mysql_query($UpdateProductoCotizacion) or print("Eliminar_Producto_Cotizacion: $UpdateProductoCotizacion " . mysql_error());
                 $count++;
            }
    }
   
    echo $flag;
?>