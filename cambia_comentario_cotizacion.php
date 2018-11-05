<?
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

if($_GET['id']!=""){
	$comentario = $_SESSION['cotizacion']->getComentario($_GET['id']);
	if($_GET['borrar']=='true'){
            $cot = new Cotizacion();
		$_SESSION['cotizacion']->deleteComentario($_GET['id']);
		?><script>parent.location.reload();</script><?
	} 
}

if($_POST["guardar"]=="Guardar"){
	if($_SESSION['cotizacion']->updateComentario( $_POST['comentario'] , $_GET['id'] ) ) { 
		?><script>parent.location.reload();</script><?
	} else {
		?><script>
		alert('Error actualizando el comentario');
        parent.location.reload();</script><?
	}
}

if($_POST['crear']!=""){
	if ( $_SESSION['cotizacion']->createComentario($_POST['comentario'], $_SESSION['usuario']->id ) ) { 
		?><script>parent.location.reload();</script><?
	} else {
		?><script>
		alert('Error creando el comentario');parent.location.reload();</script><?
	}
}

?>
<html>
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

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
	background-image: url(images/bkg_1.jpg);">AGREGAR COMENTARIO</div></td>
      </tr>
    <tr bordercolor="#CCCCCC">
      <th valign="top" class="texto_info_negro" scope="row"><textarea name="comentario" id="comentario" cols="45" rows="5"><? echo $comentario['descripcion']; ?></textarea></th>
      </tr>
    <tr>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><input name="<? if($_GET['id']!="") echo "guardar"; else echo "crear";?>" type="submit" class="texto_info_negro" value="Guardar" /></td>
    </tr>
    
    </table>
</div>
</form>
</body>
</html>
