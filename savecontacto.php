<?php
    include 'coneccion.php';
    session_start();
    $flag=0;
    $contacto=$_POST['contacto'];
    $cotizacion=$_POST['cotizacion'];
    $ConsultaCotizacion="SELECT id FROM Cotizaciones WHERE id=$cotizacion";
    $ResultadoConsulta = mysql_query($ConsultaCotizacion) or print("savecontacto: $ConsultaCotizacion " . mysql_error());
    //Validar que exista registro.
    if(@mysql_num_rows($ResultadoConsulta)>=1){
         $ActualizarCotizacion="UPDATE Cotizaciones SET id_contacto='$contacto' WHERE id=$cotizacion";
         $ResultadoActualizar = mysql_query($ActualizarCotizacion) or print("savecontacto: $ActualizarCotizacion " . mysql_error());
        $flag=1;
    }
   
    echo $flag;
    //echo $contacto;
?>