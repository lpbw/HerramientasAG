<html>
<head>
<?
include 'Usuario.php';
include 'Producto.php';
//session_start();
//include "checar_sesion_admin.php";
//include "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['supervisor']);
include "coneccion.php";

if($_GET['id']!=""){
    $producto = new Producto();
    $producto->get($_GET['id']);
    $_SESSION['cambiaProducto']=$producto;
	if($_GET['borrar']=='true'){
		$producto->delete();
		unset($_SESSION['cambiaProducto']);
        ?><script>parent.location.reload();</script><?
	}
}

?>
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
-->
</style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="500" border="0" align="center" cellpadding="0">
    <tr>
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="style4 style6"><strong><span class="style5"><?php echo $producto->nombre; ?></span></strong></div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td align="center" valign="top">
      <table border="0" cellspacing="1" cellpadding="1" width="100%">
  <tr>
    <th colspan="2" scope="col">&nbsp;</th>
    </tr>
  <tr>
    <th valign="top" scope="row"><table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
      <tr>
        <th width="171" align="left" valign="top" class="texto_info" scope="row">Proveedor</th>
        <td width="151" class="style5">
          <?php
	    $consulta  = "SELECT * FROM Proveedores WHERE id = $producto->id_proveedor";
            $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
            $nombre_proveedor=mysql_fetch_assoc($resultado);
            echo $nombre_proveedor['nombre'];
            ?></td>
      </tr>
      <!--<tr>
        <th valign="top" class="texto_info" scope="row"><div align="right">Familia</div></th>
        <td colspan="3" class="style5"><select name="id_familiaCotizador" id="id_proveedor3" style="width:200px">
          <?
            echo $producto->id_rol;
            ?>
          <option value="0">- -</option>
          <?php
	    $consulta  = "SELECT * FROM FamiliaCotizador";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
          <option <? if($producto->id_proveedor==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
          <?
            }
        }
     
		  ?>
        </select>
          <input type="button" onClick="agregarRol();" name="btnAgregaRol"  id="btnAgregaRol" value="nuevo??"></td>
      </tr>-->
      <tr>
        <th align="left" valign="top" class="texto_info" scope="row">Ficha T&eacute;cnica</th>
        <td class="style5"><?
		if($producto->archivo_ficha_tecnica!=""){?>
          <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank"><? echo end(split('/',$producto->archivo_ficha_tecnica));?></a>
          <? }?></td>
      </tr>
      <tr>
        <th align="left" valign="top" class="texto_info" scope="row">Descripci&oacute;n</th>
        <td class="style5"><? echo $producto->descripcion?></td>
      </tr>
    </table></th>
    <td><div align="center" id="imagen">
          <? if($producto->imagen!=""){?>
          <a href="<? echo $producto->imagen?>" target="_blank"><img src="<?php echo $producto->imagen;?>" alt="" name="imagenMostrar" width="150" height="150" id="imagenMostrar"></a>
          <? } else { echo "Sin imagen";}?></div></td>
  </tr>
</table>
        <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        </table>
      <div align="center"></div></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"></td>
    </tr>
  </table>
</form>
</body>
</html>
