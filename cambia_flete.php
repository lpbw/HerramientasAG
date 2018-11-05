<?
include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['supervisor']);

if($_GET['new'] == 'true'){
    unset($_SESSION['cambiaFlete']);
}
 

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
    $nombre= $_POST["nombre"];
   
    $consulta="update origen_prod_especial set flete_cliente='".$_POST['flete_cliente']."', flete_proveedor='".$_POST['flete_proveedor']."' where id={$_GET['id']}";
	$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
    
        unset($_SESSION['cambiaFlete']);
        ?><script>parent.location.reload();</script><?
   
}


if($_GET['id']!=""){
    
	
   	$consulta  = "SELECT * from origen_prod_especial where id={$_GET['id']}";
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
	background-image: url(images/bkg_1.jpg);"><?php echo $res['nombre']; ?></div></td>
      </tr>
    <tr bordercolor="#CCCCCC">
      <th width="63" valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Flete Cliente </div></th>
      <td width="281" class="style5"><input name="flete_cliente" type="text" class="texto_info_negro_forma" id="flete_cliente" value="<?php echo $res['flete_cliente']; ?>" size="45" maxlength="100" /></td>
    </tr>
      <tr bordercolor="#CCCCCC">
      <th width="63" valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Flete Proveedor </div></th>
      <td width="281" class="style5"><input name="flete_proveedor" type="text" class="texto_info_negro_forma" id="flete_proveedor" value="<?php echo $res['flete_proveedor']; ?>" size="45" maxlength="100" /></td>
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
