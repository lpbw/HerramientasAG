<?
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

if($_REQUEST['id']!=""){
	$producto = new Producto();
        $producto->get($_REQUEST['id']);
        $producto->attrName = $_REQUEST['attrName'];
        
        foreach ($producto as $key => $value) {
            if($key == $producto->attrName)
                $producto->actualValue = $value;
        }
        
        $_SESSION['cambia_atributo_producto'] = $producto;
}

if($_POST["guardar"]=="Guardar"){
    $producto = $_SESSION['cambia_atributo_producto'];
    
    if($_SESSION['cotizacion']->id!=""){
        $id_cotizacion = $_SESSION['cotizacion']->id;
        $id_version_cotizacion = $_SESSION['cotizacion']->id_version;
    } else {
        $id_cotizacion != "";
        $id_version_cotizacion !="";
    }    
    
    if($producto->updateOneAttr($producto->attrName, $_REQUEST['attrValue'], $_SESSION['usuario']->id, 
            $requireRevision = TRUE, $id_cotizacion, $id_version_cotizacion)){
		
        if($_SESSION['cotizacion']->id!=""){
            ?><script>
			parent.location = 'generar_cotizacion.php?reloadCarritoOnId=<? echo $producto->id;?>';</script><?
            
        } else {
            ?><script>parent.location.reload();</script><?
        }
        
    } else {
        ?>
        <script>
            alert('Error actualizando el comentario');
            parent.location.reload();
        </script>
        <?
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
    
  <table border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td width="60" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro"></td>
      <td width="252" bordercolor="#CCCCCC" class="texto_info_negro"></td>
    </tr>
    <tr>
      <td colspan="2" align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="center"><? echo $producto->codigo; ?></div>        </td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Nombre Español</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? echo $producto->nombre; ?></td>
    </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Nombre Inglés</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><?
	  
        echo $producto->descripcion;
		
		    ?></td>
    </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Unidad Métrica</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><?
		  echo $producto->unidad_metrica;?></td>
    </tr>
    <tr>
    
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Unidad Métrica Inglés</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><?
		  echo $producto->unidad_metrica_ingles;?></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">Cambiar
        <?
        if($producto->attrName=='nombre')
			$nombre_producto = 'nombre en español';
		else if($producto->attrName=='descripcion')
			$nombre_producto  = 'nombre en inglés';
		else {
			foreach (split('_',  $producto->attrName) as $value) {
				$nombre_producto .= "$value ";
			}
		}
        echo ucwords($nombre_producto);
        ?>
      </div></td>
    </tr>
    <tr bordercolor="#CCCCCC">
      <th colspan="2" valign="top" class="texto_info_negro" scope="row"><span class="style51">
        <select name="attrValue" class="texto_info_negro_forma" id="attrValue" style="width:200px"  >
          <option value="">Unidad Metrica</option>
          <?php
    $consulta  = "SELECT id, nombre from medidas order by nombre";
	echo"$consulta";
    $resultado_clientes= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
    while($array=mysql_fetch_assoc($resultado_clientes)) {
        ?>
          <option value="<? echo $array['nombre'];?>"><? echo $array['nombre'];?></option>
          <?
    }
    ?>
        </select>
        </span></th>
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
