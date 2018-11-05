<?
include 'Familia_cotizador.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

if($_GET['id']!=""){
    $familia = new Familia_cotizador();
    $familia->get($_GET['id']);
	$_SESSION['cambiaFamilia']=$familia;
	if($_GET['borrar']=='true'){
		$familia->delete();
		unset($_SESSION['cambiaFamilia']);
        ?><script>window.location='adm_familias_cotizador.php';</script><?
	}
}

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
	$nombre= $_POST["nombre"];
	$codigo=$_POST["codigo"];
	$factor=$_POST["factor"];
	$familia = new Familia_cotizador();
    if( $familia->update( $_SESSION['cambiaFamilia']->id, $nombre, $codigo, $factor)){
        ?><script>window.location='adm_familias_cotizador.php';</script><?
    }
}

if($_POST['crear']!=""){
    $nombre= $_POST["nombre"];
	$codigo=$_POST["codigo"];
	$factor=$_POST["factor"];
    $familia = new Familia_cotizador();
    
    if($familia->create($nombre,$codigo,$factor)){
		if($_REQUEST['atras']!="")
			$link = $_REQUEST['atras'];
		else $link = 'adm_familias_cotizador.php';
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
  <table width="350" border="0" align="center" cellpadding="0">
    <tr>
                  <td background="images/bkg_1.jpg" class="style8" scope="row"><div align="center" class="style4 style6"><span class="titulo_tabla">Familia Cotizador </span>
                      <input name="atras" type="hidden" id="atras" value="<? echo $_GET['atras'];?>">
      </div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <th width="29%" valign="top" class="texto_info" scope="row"><div align="right">Nombre  </div></th>
          <td width="71%" class="style5"><input name="nombre" type="text" class="texto_verde" id="nombre" value="<?php echo $familia->nombre; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Codigo</div></th>
          <td class="style5"><input name="codigo" type="text" class="texto_info_negro" id="codigo" value="<?php echo $familia->codigo; ?>" size="10" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row">Factor On line </th>
          <td class="style5"><input name="factor" type="text" class="texto_info_negro" id="factor" value="<?php echo $familia->factor; ?>" size="10" maxlength="100" />
            <span class="texto_info_negro_chico">(en decimales ej .10 ) </span></td>
          </tr>        
		        
      </table></td>
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
