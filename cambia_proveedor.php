<?
//ini_set('display_errors', 'On');
include 'Usuario.php';
include 'Proveedor.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);

function compareArray($a, $b) {if ($a == $b) return 0; return ($a < $b) ? -1 : 1; }

if($_GET['id']!=""){
    $proveedor = new proveedor();
    $proveedor->get($_GET['id']);
    $_SESSION['cambiaProveedor'] = $proveedor;
	if($_GET['borrar']=='true'){
		unset($_SESSION['cambiaProveedor']);
		if(!$proveedor->delete())
        ?><script>parent.location.reload();</script><?
	}
}

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
	
	$nombre = $_POST['nombre'];
	$prefijo = $_POST['prefijo'];
	
	$proveedor = new Proveedor();
        $proveedor->get($_SESSION['cambiaProveedor']->id);
        
    if( $proveedor -> update($nombre, $prefijo)){
        unset($_SESSION['cambiaProveedor']);
        ?><script>parent.location.reload();</script><?
    }
}

if($_POST['crear']!=""){
    $nombre = $_POST['nombre'];
	$prefijo = $_POST['prefijo'];
    $proveedor = new Proveedor();
    
    if($proveedor->create($nombre, $prefijo)){
        unset($_SESSION['cambiaProveedor']);
        ?><script>parent.location.reload();</script><?
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
<script>
	function agregarRol(){
		window.location = 'cambia_tipo_usuario.php?atras=cambia_usuario.php';
	}
	function setContra(obj){
		obj.type='text';
		obj.value='';
		obj.name='contrasenia';
	}
	
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
		var val =selObj.options[selObj.selectedIndex].value;
		document.getElementById('orden').innerHTML = viewOrden(parseInt(val)-1);
	}
	function viewOrden(id){
		var orden = Array();
		<?
	    $consulta  = "SELECT * FROM Tipo_importacion";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
			$j=0;
            while($array=mysql_fetch_assoc($resultado)) {
			@uasort($array,'compareArray');
			$i=0;
			foreach ($array as $key => $value) {
				if($value!=0)
					$orden .= "$i)$key ";
					$i++;
			}
			echo "orden[$j]='$orden';";
			$j++;
		}
        }
		?>
		return orden[id];
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
  <table width="100%" border="0" align="center" cellpadding="0">
    <tr style="background-image: url(images/bkg_1.jpg);">
                  <td class="style8" scope="row"><div align="center" class="style4 style6"><span class="style7 style6">MARCA </span></div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="1" bordercolor="#CCCCCC">
        <tr>
          <td width="174" valign="top" class="texto_info_negro" scope="row"><div align="right">Nombre </div></td>
          <td width="11" class="style5">&nbsp;</td>
          <td width="1080" class="style5"><input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?php echo $proveedor->nombre; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <td valign="top" class="texto_info_negro" scope="row"><div align="right">Prefijo </div></td>
          <td class="style5">&nbsp;</td>
          <td class="style5"><input name="prefijo" type="text" class="texto_info_negro_forma" id="prefijo" value="<?php echo $proveedor->prefijo; ?>" size="45" maxlength="100" /></td>
        </tr>
        <!--<tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Tipo Importaci&oacute;n</div></th>
          <td class="style5"><select name="id_tipo_importacion" id="id_tipo_importacion" style="width:200px" onChange="MM_jumpMenu('importar_productos.php',this,0)">
            <option value="0">-- tipo --</option>
            <?php
	    $consulta  = "SELECT * FROM Tipo_importacion";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {?>
                <option <? if($proveedor->formato_importacion==$array['id_tipo_importacion']) echo 'selected';
                        ?> value="<? echo $array['id_tipo_importacion'];?>"> <?
                echo "Tipo: ".$array['id_tipo_importacion'];
                ?></option><?
            }
        }
     
		  ?>
          </select></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Orden</div></th>
          <td class="style5"><div id="orden"></div></td>
        </tr>-->
      </table></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="<? if($proveedor!="") echo "guardar"; else echo "crear";?>" type="submit" class="texto_info_negro" value="Guardar" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
