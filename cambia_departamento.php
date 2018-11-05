<?
include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['supervisor']);

if($_GET['new'] == 'true'){
    unset($_SESSION['cambiaDepartamento']);
}
 if($_GET['borrar']=='true'){
       
	    $consulta="delete from departamentos where id={$_GET['id']}";
	$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
	   
        unset($_SESSION['cambiaDepartamento']);
        ?><script>parent.location.reload();</script><?
    }

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
    $nombre= $_POST["nombre"];
   
    $consulta="update departamentos set nombre='".$_POST['nombre']."' where id={$_GET['id']}";
	$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
    
        unset($_SESSION['cambiaDepartamento']);
        ?><script>parent.location.reload();</script><?
   
}

if($_POST['crear']!=""){
    $nombre= $_POST["nombre"];
   
   $consulta="insert into departamentos(nombre)values('".$_POST['nombre']."')";
	$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
   
		unset($_SESSION['cambiaDepartamento']);
        ?><script>parent.location.reload();</script><?
    
}
if($_GET['id']!=""){
    
	
   	$consulta  = "SELECT * from departamentos where id={$_GET['id']}";
	$resultado = mysql_query($consulta) or print("La consulta fallo lista depas: $consulta <bR> " . mysql_error());
    $res=mysql_fetch_assoc($resultado);
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

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px; overflow:auto">
  
  <table border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">DEPARTAMENTO</div></td>
      </tr>
    <tr bordercolor="#CCCCCC">
      <th width="63" valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Nombre </div></th>
      <td width="281" class="style5"><input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?php echo $res['nombre']; ?>" size="45" maxlength="100" /></td>
    </tr>
    
 
    <tr bordercolor="#CCCCCC">
      <th colspan="2" align="center" valign="top" class="texto_info" scope="row">&nbsp;</th>
    </tr>
   
    
    <tr>
      <td colspan="2" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><input name="<? if($_GET['id']!="") echo "guardar"; else echo "crear";?>" type="submit" class="texto_info_negro" value="Guardar" /></td>
    </tr>
    
    </table>
</div>
</form>
</body>
</html>
