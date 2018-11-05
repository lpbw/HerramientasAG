<?
ini_set('display_errors','1');
//ini_set('display_errors', 'On');
include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Cliente.php';
session_start();
include_once 'Contacto.php';
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['vendedor25']);
date_default_timezone_set('America/Chihuahua');

include_once 'guardarCotizacion.php';

/*if($_REQUEST['id_contacto']!=""){
    $enviar_a_temporal = $_SESSION['enviar_a_temporal'];
    unset($_SESSION['enviar_a_temporal']);
}

if($_POST['nuevo_contacto']!=""){
    $_SESSION['enviar_a_temporal'] = array();
    foreach ($_POST as $key => $value) {
        $_SESSION['enviar_a_temporal'][$key]=$value;
    }
    ?><script>window.location = 'cambia_contacto.php?nuevo=true';</script><?
}

if($_POST['modificar_contacto']!=""){
    if($_POST['id_contacto']!=""){
        $_SESSION['enviar_a_temporal'] = array();
        foreach ($_POST as $key => $value) {
            $_SESSION['enviar_a_temporal'][$key]=$value;
        }
        ?><script>window.location = 'cambia_contacto.php?id='+<? echo $_POST['id_contacto'];?>;</script><?
    } else {
        ?><script>alert('Escoge un contacto');</script><?
    }
}*/

/*if($_POST['genera']=="Generar PDF"){
	date_default_timezone_set('America/Chihuahua');
	if(isset($_SESSION['cotizacion'])){
		include_once 'mailCotizacion.php';
		require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
		require_once ("dompdf/include/style.cls.php");
		
        $cliente = new Cliente();
		$cliente -> get($_SESSION['cotizacion'] -> id_cliente);
        $cotizacion = new Cotizacion();
        $cotizacion = $_SESSION['cotizacion'];
		$cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
		$html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false);
			$pdf = new DOMPDF();
			$pdf ->load_html($html);
			$pdf ->render();
			$archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
			file_put_contents($archivo,$pdf->output());
			$pdf ->stream("cotizacion_".$cotizacion->id.".pdf");
	}
}*/

/*if($_POST['enviar']!=""){
//genrar pdf
	date_default_timezone_set('America/Chihuahua');
	if(isset($_SESSION['cotizacion'])){
            include_once 'mailCotizacion.php';
            require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
            require_once ("dompdf/include/style.cls.php");
		
            $cliente = new Cliente();
            $cliente -> get($_SESSION['cotizacion'] -> id_cliente);
            $cotizacion = $_SESSION['cotizacion'];

            //
             // CREANDO LA COTIZACION EN PDF Y SUBIENDOLA AL SERVIDOR
             //
            $cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
            $html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false);
            //
             // CODIGO QUE CREA EL PDF Y LO SUBE
             //
            $pdf = new DOMPDF();
            $pdf ->load_html($html);
            $pdf ->render();
            $archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
            $archivo_name="cotizacion_".$cotizacion->id.".pdf";
            file_put_contents($archivo,$pdf->output());
        }*/

 /*   if(isset($_SESSION['cotizacion'])){
        $cliente = new Cliente();
        $contacto  = new Contacto();
        $cotizacion = $_SESSION['cotizacion'];
        
        switch ( $_POST['enviar_a'] ) {
            case 'cliente':
                if($_SESSION['cotizacion'] -> id_estatus < 2 ){
                    $_SESSION['cotizacion'] -> id_estatus = 2;
                    $_SESSION['cotizacion']->update($_SESSION['cotizacion']);
                }
                
                date_default_timezone_set('America/Chihuahua');
                $cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
                
                $cliente -> get($_SESSION['cotizacion'] -> id_cliente);
                $contacto -> get($_POST['id_contacto'] , $_SESSION['cotizacion']->id_cliente);
                $cotizacion -> notificarCliente( $cliente, $sender = $_SESSION['usuario'],
                        $_POST['subject'], $_POST['mensaje'], $contacto, 
                        $_POST['destinatarios'], $archivo_name , isset($_POST['enviarCotEnCorreo']) );
                break;
                
            case 'compras':
                if($_SESSION['cotizacion']->id_estatus < 4 ){
                    $_SESSION['cotizacion'] -> id_estatus = 4;
                    $_SESSION['cotizacion']->update($_SESSION['cotizacion']);
                }
                
                $vendedor = $_SESSION['usuario'];
                $compras = $vendedor->getCompras();
                $_SESSION['cotizacion']->notificarUsuario( $cliente,
                        $sender = $_SESSION['usuario'], $_POST['subject'],
                        $_POST['mensaje'], $compras );
                break;
                
            case 'supervisor':
                $vendedor = $_SESSION['usuario'];
                $supervisor = $vendedor->getSupervisor();
                $_SESSION['cotizacion']->notificarUsuario( 
                        $cliente, $sender = $_SESSION['usuario'],
                        $_POST['subject'], $_POST['mensaje'],
                        $supervisor, $asuntoSupervisor = "Revisi�n Supervisor" );
                break;
            
            default:
                break;
        }
        
        ?><script>
            alert('Cotizacion enviada');
            parent.location.reload();</script><?
    } else {
        ?><script>
            alert('Primero crea la cotizacion antes de enviarla.');
			parent.location='generar_cotizacion.php';</script><?
    }
}
*/

?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
<script>
function validar(){
	var returnn = true;
	if(parent.validar()){
		if(document.getElementById('enviar_a_1').checked){
			if(!(<? echo $_SESSION['cotizacion']->id_estatus;?> > 2) ){
				if(confirm('Cambiar\u00e1s el estatus a Ganada y crear\u00e1s una versi\u00f3n. �De acuerdo?'))
					returnn = true;
				else
					returnn = false;
			}
		} else 
			returnn = true;
	} else {
		parent.location.reload();
	}
    
    return returnn;
}

function verCorreo(id){
//    window.open("http://www.w3schools.com",  "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        window.open ('ver_correo.php?id='+id,"_blank","toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1000, height=600");
}
</script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.style5 {font-size: 18}
-->
</style>

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: -10px;
	background-color: #FFFFFF;
	margin-top: -10px;
}
.style51 {font-size: 12}
</style>

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
-->
</style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px">
  <table width="600" border="0" cellspacing="2" cellpadding="2">
    <tr bgcolor="#E3E3E3" >
      <th colspan="3" bgcolor="#FFFFFF"><div align="center"  class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">HISTORIAL DE CORREOS</div></th>
    </tr>
    <tr bgcolor="#E3E3E3"  class="texto_info_negro">
      <td width="19%" scope="col">De</td>
      <td width="18%" scope="col">Para</td>
      <td width="20%" scope="col">Fecha</td>
    </tr>
    <? 
		$color = "bgcolor=\"#F4F4F4\"";
		$correosEnviados = $_SESSION['cotizacion'] -> correosEnviados();
 	    foreach ($correosEnviados as $n => $correo) { ?>
    <tr <? echo $color;?> class="texto_info_negro_chico" onClick="verCorreo(<? echo $correo['id']?>)" style="cursor:pointer">
      <th align="center"  scope="row" ><div align="left"><? echo $correo['de'];?></div></th>
      <td align="center" ><div align="left"><? echo $correo['para'];?></div></td>
      <td align="center" ><? echo $correo['fecha'];?></td>
    </tr>
    <? if($color == "bgcolor=\"#F4F4F4\"") $color = ""; 
	   else $color = "bgcolor=\"#F4F4F4\"";
	   } ?>
  </table>
</div>
</form>
</body>
</html>
