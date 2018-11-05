<?
include_once 'Usuario.php';
include_once 'Cotizacion.php';
session_start();
include_once "checar_sesion_admin.php";

include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['administrador']);
include_once "checar_permisos.php";

if($_POST["guardar"]=="Guardar"){
    $query = "UPDATE LeyendaCotizaciones SET  leyenda_cotizaciones = '".mysql_escape_string($_POST['comentario'])."'";
    $result = mysql_query($query) or print($query);
	?><script>parent.location.reload();</script><?
}

?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>

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


</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px">
    <input type="hidden" name="id" value="<? echo $comentario['id']; ?>"/>  
  <table border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">LEYENDA CONDICIONES DE COTIZACIÓN</a></div></td>
      </tr>
    <tr bordercolor="#CCCCCC">
      <th valign="top" class="texto_info_negro" scope="row"><textarea name="comentario" id="comentario" cols="60" rows="5"><? 
	  echo Cotizacion::getLeyenda();?></textarea></th>
      </tr>
    <tr>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><input name="guardar" type="submit" class="texto_info_negro" value="Guardar" /></td>
    </tr>
    
    </table>
</div>
</form>
</body>
</html>
