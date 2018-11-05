<?
include_once 'Catalogo.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);

if($_GET['id']!=""){
    $catalogo = new Catalogo();
    $catalogo->get($_GET['id'],$_GET['id_proveedor']);
	$_SESSION['cambiaCatalogo']=$catalogo;
	if($_GET['borrar']=='true'){
		$catalogo->delete();
		unset($_SESSION['cambiaCatalogo']);
        ?><script>parent.location.reload();</script><?
	}
}

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
	$nombre= $_POST["nombre"];
	$factor = $_POST["factor"];
	$factor2 = $_POST["factor2"];
	$origenFactor = $_POST["origenFactor"];
	$origenFactor2 = $_POST["origenFactor2"];
	$id_tipo_importacion = $_POST['id_tipo_importacion'];
	$catalogo = new Catalogo();
    if( $catalogo->update( $_SESSION['cambiaCatalogo']->id, $nombre, $factor, $factor2, $id_tipo_importacion, $origenFactor, $origenFactor2)){
        unset($_SESSION['cambiaCatalogo']);
        ?><script>parent.location.reload();</script><?
    }
}

if($_POST['crear']!=""){
    $nombre= $_POST["nombre"];
    $factor = $_POST["factor"];
    $factor2 = $_POST["factor2"];
    $id_proveedor = $_POST['id_proveedor'];
    $id_tipo_importacion = $_POST['id_tipo_importacion'];
    $catalogo = new Catalogo();
    
    if($catalogo->create($nombre,$factor, $factor2, $id_proveedor,$id_tipo_importacion)){
        if($_REQUEST['atras']!="")
            $link = $_REQUEST['atras'];
        else 
            $link = 'adm_catalogos.php';
        
        unset($_SESSION['cambiaCatalogo']);
        ?><script>parent.location.reload();</script><?
    }
}

if($_POST['asignar']!=""){
    $catalogo = new Catalogo();
	
	if($catalogo->asignar($_POST['id_catalogo'],$_POST['id_proveedor'],$_POST['factor'],$_POST['id_tipo_importacion'])){
		
        unset($_SESSION['cambiaCatalogo']);
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
        <script>
		
	function MM_jumpMenu(selObj){ //v3.0
		var val =selObj.options[selObj.selectedIndex].value;
		document.getElementById('orden').innerHTML = viewOrden(parseInt(val)-1);
	}
	
	function viewOrden(id){
		var orden = Array();
		<?
		function compareArray($a, $b) {if ($a == $b) return 0; return ($a < $b) ? -1 : 1; }
		
	    $consulta  = "SELECT * FROM Tipo_importacion";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
			$j=0;
            while($array=mysql_fetch_assoc($resultado)) {
			@uasort($array,'compareArray');
			$i=0;
			foreach ($array as $key => $value) {
				if($value!="")
					if($key!='id_tipo_importacion')
						if($key!='nombre_tipo_importacion')
							$orden .= "$value)$key ";
				$i++;
			}
			echo "orden[$j]='$orden';";
			$orden="";
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
<script>
function validar(){
	if( isNaN( $('#factor').val() )){
		alert('Factor debe ser numero');
		 $('#factor').val('');
		 $('#factor').focus();
	} else if( isNaN( $('#factor2').val() )){
		alert('Factor debe ser numero');
		 $('#factor2').val('');
		 $('#factor2').focus();
}
</script>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="90%" border="0" align="center" cellpadding="0">
    <tr class="titulo_tabla" style="">
                  <td class="style8" scope="row" background="images/bkg_1.jpg"><div align="center" class="titulo_tabla">CATALOGO
                      <input name="atras" type="hidden" id="atras" value="<? echo $_REQUEST['atras'];?>">
                    <input name="id_proveedor" type="hidden" id="id_proveedor" value="<? echo $_REQUEST['id_proveedor'];?>">
                    <input name="id_catalogo" type="hidden" id="id_catalogo" value="<? echo $_REQUEST['id_catalogo'];?>">
                  </div>                    
      <div align="center"></div></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="0" bordercolor="#CCCCCC">
        <tr>
          <td width="19%" valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Nombre  </div></td>
          <td width="2%" class="style5">&nbsp;</td>
          <td width="79%" colspan="4" class="style5"><input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?
          echo $catalogo->nombre; 
		  echo $_POST['nombre'];
		  ?>" size="45" maxlength="100" <? if($_POST['nombre']!="") echo 'readonly'?> />
            <input type="hidden" name="id_tipo_importacion" class="texto_info_negro" id="id_tipo_importacion" style="width:200px" value="1"/></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Factor de multiplicaci&oacute;n </div></td>
          <td align="left" valign="middle" class="style5">&nbsp;</td>
          <td align="left" valign="middle" class="style5"><input name="factor" type="text" class="texto_info_negro" id="factor" value="<?php echo $catalogo->factor; ?>" size="10" maxlength="100" /></td>
          <td align="right" valign="middle" class="texto_info_negro" scope="row"><span class="style5">
            <? if( $_SESSION['cambiaCatalogo']->id_proveedor == 7){//SNAP ON ?>
          </span>Origen factor<span class="style5">
          <? } ?>
          </span>:</td>
          <td align="right" valign="middle" class="texto_info_negro" scope="row">&nbsp;</td>
          <td align="left" valign="middle" class="style5">
            
            <? if( $_SESSION['cambiaCatalogo']->id_proveedor == 7){//SNAP ON ?><input name="origenFactor" type="text" class="texto_info_negro" id="origenFactor" value="<?php echo $catalogo->origenFactor; ?>" maxlength="100" /><? } ?></td>
          </tr>
          <? if( $_SESSION['cambiaCatalogo']->id_proveedor == 7){//SNAP ON ?>
        <tr>
          <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Factor de multiplicaci&oacute;n 2</td>
          <td align="left" valign="middle" class="style5">&nbsp;</td>
          <td align="left" valign="middle" class="style5"><span class="texto_info">
            <input name="factor2" type="text" class="texto_info_negro" id="factor2" value="<?php echo $catalogo->factor2; ?>" size="10" maxlength="100" />
          </span></td>
          <td align="right" valign="middle" class="texto_info_negro" scope="row">Origen factor 2:</td>
          <td align="right" valign="middle" class="texto_info_negro" scope="row">&nbsp;</td>
          <td align="left" valign="middle" class="style5"><span class="texto_info">
            <input name="origenFactor2" type="text" class="texto_info_negro" id="origenFactor2" value="<?php echo $catalogo->origenFactor2; ?>" maxlength="100" />
          </span></td>
          </tr>
          <? }// end if ?>
      </table>
          <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        </table>
      <div align="center"></div></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="<? 
		if($_GET['id']!="") 
			echo "guardar"; 
		else if($_POST['nombre']!="")
			echo 'asignar';
		else 
			echo "crear";?>" type="submit" class="texto_info_negro" value="Guardar" onClick="return validar();" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
