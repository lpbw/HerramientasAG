<?
include 'Cartera_clientes.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

if($_GET['id']!=""){
    $cartera = new Cartera_clientes();
    $cartera->get($_GET['id']);
    $_SESSION['cambiaCartera']=$cartera;
    if($_GET['borrar']=='true'){
            $cartera->delete();
            unset($_SESSION['cambiaCartera']);
    ?><script>window.location='adm_carteras.php';</script><?
    }
}

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
    $nombre= $_POST["nombre"];
    $cartera = new Cartera_clientes();
    if( $cartera->update( $_SESSION['cambiaCartera']->id, $nombre)){
        ?><script>window.location='adm_carteras.php';</script><?
    }
}

if($_POST['crear']!=""){
    $nombre= $_POST["nombre"];
    $cartera = new Cartera_clientes();
    
    if($cartera->create($nombre)){
        if($_REQUEST['atras']!="")
                $link = $_REQUEST['atras'];
        else $link = 'adm_carteras.php';
        echo "<script>window.location='$link';</script>";
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
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="style4 style6"><span class="style7 style6">Cartera Clientes </span>
                      <input name="atras" type="hidden" id="atras" value="<? echo $_GET['atras'];?>">
      </div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td><table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <th width="29%" valign="top" class="texto_info" scope="row"><div align="right">Nombre  </div></th>
          <td width="71%" class="style5"><input name="nombre" type="text" class="texto_verde" id="nombre" value="<?php echo $tipo_usuario->nombre; ?>" size="45" maxlength="100" /></td>
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
        <input type="submit" name="<? if($_GET['id']!="") echo "guardar"; else echo "crear";?>" value="Guardar" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
