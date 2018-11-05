<?
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";




if($_POST["guardar"]=="Guardar"){
    $query = "UPDATE pin SET pin = ".$_POST['herramiengasAG']." WHERE id = 1";
    $result = mysql_query($query) or print("<script>parent.location.reload();</script>");
  
    echo"<script>alert(\"PIN Cambiado\");</script>";
}
$query = "SELECT pin FROM pin WHERE id=1";
$result = mysql_query($query) or print("<script>parent.location.reload();</script>");
if($result)
    $pin = mysql_fetch_assoc($result);

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
    
  <table width="400" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">PIN actual </div></td>
      </tr>
    <tr bordercolor="#CCCCCC">
      <th width="123" valign="top" class="texto_info_negro" scope="row"><div><strong>HerramiengasAG</strong></div></th>
      <th width="263" align="left" valign="top" class="texto_info_negro" scope="row"><label>
        <input type="text" name="herramiengasAG" id="herramiengasAG" value="<? echo $pin['pin'];?>">
        </label></th>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">
          <input name="guardar" type="submit" class="texto_info_negro" value="Guardar" /></td>
    </tr>
    
    </table>
</div>
</form>
</body>
</html>
