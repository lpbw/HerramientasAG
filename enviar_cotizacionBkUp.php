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

include_once 'guardarCotizacion.php';

if($_REQUEST['id_contacto']!=""){
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
}

if($_POST['genera']=="Generar PDF"){
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
}

if($_POST['enviar']!=""){
//genrar pdf
	date_default_timezone_set('America/Chihuahua');
	if(isset($_SESSION['cotizacion'])){
            include_once 'mailCotizacion.php';
            require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
            require_once ("dompdf/include/style.cls.php");
		
            $cliente = new Cliente();
            $cliente -> get($_SESSION['cotizacion'] -> id_cliente);
            $cotizacion = $_SESSION['cotizacion'];

            /*
             * CREANDO LA COTIZACION EN PDF Y SUBIENDOLA AL SERVIDOR
             */
            $cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
            $html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false);
            /*
             * CODIGO QUE CREA EL PDF Y LO SUBE
             */
            $pdf = new DOMPDF();
            $pdf ->load_html($html);
            $pdf ->render();
            $archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
            $archivo_name="cotizacion_".$cotizacion->id.".pdf";
            file_put_contents($archivo,$pdf->output());
        }

    if(isset($_SESSION['cotizacion'])){
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
	window.location = 'enviar_cotizacion.php?id'+id;
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
  
  <table width="700" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="3"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">ENVIAR</div></td>
    </tr>
    <tr>
      <td rowspan="2" align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Para</td>
      <td width="275" align="left" class="texto_info_negro"><label>
        <input type="radio" name="enviar_a" checked value="cliente" id="enviar_a_0">
        Contacto
        
        <input name="nuevo_contacto" type="submit" class="texto_info_negro" id="nuevo_contacto" value="Nuevo" />
      </label></td>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Enviados a</td>
      </tr>
    <tr>
      <td align="left" class="texto_info_negro"><span class="style52">
        <select name="id_contacto" class="texto_info_negro_forma" id="id_contacto" style="width:275px" >
          <?php
	    $consulta  = "SELECT * FROM Contactos WHERE id_cliente = ".$_SESSION['cotizacion']->id_cliente;
        $resultado_estatus = mysql_query($consulta) or print(mysql_error()." EN SELECT CONTACTO");
        if(@mysql_num_rows($resultado_estatus)>=1){
            while($array=mysql_fetch_assoc($resultado_estatus)) {
                ?>
          <option <? if($id_contacto == $array['id'] ) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre_contacto']." (".$array['email_contacto'].")";?></option>
          <?
            }
        }
     
		  ?>
          </select>
        <input name="modificar_contacto" type="submit" class="texto_info_negro" id="modificar_contacto" value="Modificar" />
      </span></td>
      <td width="100%" rowspan="8" align="center" valign="top" class="texto_info_negro">
      <div style="overflow:auto; border:solid 1px; width:100%; height:380px;"><table width="100%" border="0" cellspacing="1" cellpadding="2">
        <tr  class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">
          <th width="19%" scope="col">De</th>
          <th width="18%" scope="col">Para</th>
          <th width="20%" scope="col">Fecha</th>
          </tr>
        <? 
		$correosEnviados = $_SESSION['cotizacion'] -> correosEnviados();
 	    foreach ($correosEnviados as $n => $correo) { ?>

        <tr class="texto_info_negro_chico" onClick="window.location = 'ver_correo.php?id=<? echo $correo['id'];?>'" style="cursor:pointer">
          <th align="center" scope="row" ><? echo $correo['de'];?></th>
          <td align="center"><? echo $correo['para'];?></td>
          <td align="center"><? echo $correo['fecha'];?></td>
          </tr>
          <? } ?>
      </table>
      </div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">De</td>
      <td width="275" align="left" valign="middle" class="texto_info_negro"><? echo $_SESSION['usuario']->email;?></td>
    </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Asunto</td>
      <td align="left" valign="middle" class="texto_info_negro"><input name="subject" type="text" class="texto_info_negro_forma" id="subject" value="<? echo $enviar_a_temporal['subject'];?>" maxlength="100"/></td>
    </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Destinatarios adicionales</td>
      <td align="left" valign="middle" class="texto_info_negro"><textarea name="destinatarios" rows="3" class="texto_info_negro_forma" id="destinatarios" style="width:275px"><? echo $enviar_a_temporal['destinatarios'];?></textarea>
        <em><span class="texto_info_negro_chico">Separar direcciones con coma </span></em></td>
      </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Mensaje</td>
      <td align="left" valign="middle" class="texto_info_negro"><textarea name="mensaje" rows="6" class="texto_info_negro_forma" id="mensaje" style="width:275px"><? echo $enviar_a_temporal['mensaje'];?></textarea></td>
      </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="texto_info_negro"><input type="submit" name="cambiar" id="cambiar" class="texto_info_negro" value="Generar PDF" /></td>
      </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="texto_info_negro"><label>
        <input type="checkbox" name="enviarCotEnCorreo" checked="checked" value="1">
        Cotizacion en cuerpo del mensaje</label></td>
      </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="texto_info_negro"><input name="enviar" type="submit" class="texto_info_negro" onClick="return validar()" value="Enviar" />
	  	<input type="submit" name="genera" id="genera" class="texto_info_negro" value="Generar PDF" /></td>
    </tr>
    </table>
</div>
</form>
</body>
</html>
