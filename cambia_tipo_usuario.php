<?
include 'Tipo_usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['administrador']);

if($_GET['id']!=""){
    $tipo_usuario = new Tipo_usuario();
    $tipo_usuario->get($_GET['id']);
	$_SESSION['cambiaTipoUsuario']=$tipo_usuario;
	if($_GET['borrar']=='true'){
		$tipo_usuario->delete();
		unset($_SESSION['cambiaTipoUsuario']);
        ?><script>window.location='adm_tipo_usuario.php';</script><?
	}
}

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
	$nombre= $_POST["nombre"];
	$limite_descuento=intval($_POST["limite_descuento"])/100;
	$tipo_usuario = new Tipo_usuario();
    if( $tipo_usuario->update( $_SESSION['cambiaTipoUsuario']->id, $nombre, $limite_descuento)){
        ?><script>window.location='adm_tipo_usuario.php';</script><?
    }
}

if($_POST['crear']!=""){
    $nombre= $_POST["nombre"];
    $limite_descuento=intval($_POST["limite_descuento"])/100;
    $tipo_usuario = new Tipo_usuario();
    
    if($tipo_usuario->create($nombre,$limite_descuento)){
		if($_REQUEST['atras']!="")
			$link = $_REQUEST['atras'];
		else $link = 'adm_tipo_usuario.php';
        echo "<script>window.location='$link';</script>";
    }
}

?>
<html>
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
-->
</style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="90%" border="0" align="center" cellpadding="0">
    <tr>
                  <td class="style8" scope="row" background="images/bkg_1.jpg"><div align="center" class="style4 style6"><span class="style7 style6">Tipo Usuario </span>
                    <input name="atras" type="hidden" id="atras" value="<? echo $_GET['atras'];?>">
      </div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td><table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <th width="29%" valign="top" class="texto_info_negro" scope="row"><div align="right">Nombre  </div></th>
          <td width="71%" class="style5"><input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?php echo $tipo_usuario->nombre; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">L&iacute;mite de descuento </div></th>
          <td class="style5"><input name="limite_descuento" type="text" class="texto_info_negro" id="limite_descuento" value="<?php echo ($tipo_usuario->limite_descuento)*100; ?>" size="10" maxlength="100" /> 
            %</td>
        </tr>        
		        
      </table>
          <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
      </table>
          <div align="center"></div></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="<? if($_GET['id']!="") echo "guardar"; else echo "crear";?>" type="submit" class="texto_info_negro" value="Guardar" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
