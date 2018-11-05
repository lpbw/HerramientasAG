<?
include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Cliente.php';
session_start();
include_once 'Contacto.php';

if($_REQUEST['id']!=""){
	$correosEnviados = $_SESSION['cotizacion'] -> correosEnviados();
	$correo = array();
	foreach ($correosEnviados as $n => $c) { 
		if($c['id'] == $_REQUEST['id']){
			$correo = $c;
			break;
		}
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
    if(document.getElementById('enviar_a_1').checked){
        if(!(<? echo $_SESSION['cotizacion']->id_estatus;?> > 2) ){
            if(confirm('Cambiar\u00e1s el estatus a Ganada y crear\u00e1s una versi\u00f3n. �De acuerdo?'))
                returnn = true;
            else
                returnn = false;
        }
    } else 
		returnn = true;
    
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
  
  <table width="462" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">CORREO</div></td>
      </tr>
    <tr>
      <td align="right" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Version:</td>
      <td width="301" align="left" class="texto_info_negro"><span class="style52">
          <?php
		  echo $correo['id_version_cotizacion'];?>
      </span></td>
      </tr>
    <tr>
      <td align="right" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Fecha:</td>
      <td align="left" class="texto_info_negro"><span class="style52">
        <?php
		  echo $correo['fecha'];?>
      </span></td>
      </tr>
    <tr>
      <td align="right" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Para:</td>
      <td align="left" class="texto_info_negro"><span class="style52">
        <?php
		  echo $correo['para'];?>
      </span></td>
      </tr>
    <tr>
      <td width="68" align="right" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">De:</td>
      <td width="301" align="left" valign="middle" class="texto_info_negro"><? echo $correo['de'];?></td>
      </tr>
    <tr>
      <td align="right" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Asunto:</td>
      <td align="left" valign="middle" class="texto_info_negro"><? echo $correo['asunto'];?></td>
      </tr>
    <tr>
      <td align="right" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Cc :</td>
      <td align="left" valign="middle" class="texto_info_negro"><? echo $correo['destinatarios_adicionales'];?></td>
      </tr>
    
        </table>
  <p>&nbsp;</p>
  <table width="100%" border="0" align="center" cellpadding="10" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td align="center" valign="top" bgcolor="#E3E3E3" class="texto_info_negro_forma">CUERPO DEL CORREO</td>
    </tr>
    <tr>
      <td  align="center" valign="top" ><? echo $correo['mensaje'];?></td>
    </tr>
  </table>
</div>
</form>
</body>
</html>
