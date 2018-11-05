<?
include 'coneccion.php';
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
//function __autoload($class_name) {
//    include $class_name . '.php';
//}
session_start();
include "checar_sesion_admin.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['vendedor25']);
/*
 *PERMISOS DE ESCRITURA TIENEN: Administrador, Supervisor y Compras
 */
include "checar_permisos.php";

if($_POST['submit']!=""){
    
    $nombre = $_POST['nombre'];
    $location = $_POST['location'];
    $archivo = $_POST['archivo'];

    
    if( $_FILES['archivo']['name'] != "" ){
        if($_SESSION['cotizacion']->subirArchivo($nombreCampo = 'archivo')){
            ?><script>parent.abrir("archivos_cotizacion.php",true);</script><?
            
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
<script>
	function agregarRol(){
		window.location = 'cambia_tipo_usuario.php?atras=cambia_usuario.php';
	}
	function setContra(obj){
		obj.type='text';
		obj.value='';
		obj.name='contrasenia';
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
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px">
  
  <table width="450" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">AGREGAR</div></td>
    </tr>
    <tr>
      <td width="148" height="50" align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro"><span class="style5">
        <input type="file" name="archivo" id="archivo">
        </span></td>
      <td height="50" align="center" valign="middle" class="texto_info_negro">.<span class="texto_info">
        <input type="submit" name="submit" value="Agregar" />
        </span></td>
    </tr>
    </table>
</div>
</form>
</body>
</html>
