<?
include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
session_start();
include_once 'functions_cotizacion.php';
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
setAccesoIndividual($_SESSION['accesos']['vendedor'],$_SESSION['accesos']['vendedor25'],
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
        $_SESSION['accesos']['compras']);

checarAcceso($_SESSION['accesos']['vendedor25']);
/*
 *PERMISOS DE ESCRITURA TIENEN: Administrador, Supervisor y Compras
 */
include_once "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
        $_SESSION['accesos']['compras']);

/*
 * Variable para poner en modo lectura o escritura
 */
function vistaEscritura($esLista){
    global $vistaLectura;
    if($vistaLectura){
        if($esLista)
            return " disabled ";
        else
            return "readonly";
    }		
}


if($_GET['id']!=""){
    $producto = new Producto();
    $producto->get($_GET['id']);
    $_SESSION['cambiaProducto'] = $producto;
	if($_GET['borrar']=='true'){
		$producto->delete();
		unset($_SESSION['cambiaProducto']);
        ?><script>//DEBUG parent.location.reload();</script><?
	}
} 

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
	
	$nombre = $_POST['nombre'];
	$id_catalogo_productos = $_POST['id_catalogo_productos'];
	$precio = $_POST['precio'];
	$costo = $_POST['costo'];
	$id_proveedor = $_POST['id_proveedor'];
	$no_actualizado_en_microsip = $_POST['no_actualizado_en_microsip'];
	$archivo_ficha_tecnica = $_POST['archivo_ficha_tecnica'];
	$imagen = $_POST['imagen'];
	$descripcion = $_POST['descripcion'];
	$codigo_familia = $_POST['codigo_familia'];
	$unidad_metrica = $_POST['unidad_metrica'];
	$stock = $_POST['stock'];
	$codigo_descuento = $_POST['codigo_descuento'];
	
	$producto = new Producto();
        $producto->get($_SESSION['cambiaProducto']->id);
		
	if($no_actualizado_en_microsip==1)
		$modificado=0;
	else $modificado =1;
	
        if($vistaLectura){
            if($producto -> updateFromVistaLectura(
                $_FILES['archivo_ficha_tecnica']['name'],
                $_FILES['imagen']['name'],
                $descripcion,$unidad_metrica ) ){
                unset($_SESSION['cambiaProducto']);

                $mystring = $_POST['from'];
                $findme   = 'generar_cotizacion.php';
                $pos = strpos($mystring, $findme);

                if( $pos === false ){
                    ?><script>parent.location.reload();</script><?
                } else {
                    ?><script>parent.location = 'generar_cotizacion.php?reloadCarritoOnId=<? echo $producto->id;?>';</script><?
                }
            }
	} else {
            if($producto -> update($nombre,'id',
                    $id_catalogo_productos,$precio,
                    $costo,$id_proveedor,$modificado,
                    $_FILES['archivo_ficha_tecnica']['name'],
                    $_FILES['imagen']['name'],
                    $descripcion, $codigo_familia,
                    $unidad_metrica, $exportar_microsip="",$stock, $codigo_descuento ))
            {
                unset($_SESSION['cambiaProducto']);

                $mystring = $_POST['from'];
                $findme   = 'generar_cotizacion.php';
                $pos = strpos($mystring, $findme);
                
                if($_REQUEST['from']=="adm_revision_cambios_productos.php"){
                    ?>
                    <script>
                     //DEBUG    parent.document.getElementById('no_aprobar_<? echo $producto->id.$_REQUEST['atributo']?>').checked = true;
                     //DEBUG    parent.cerrarV();
                    </script>
                    <?
                } else if( $pos === false ){
                    ?><script>//DEBUG parent.location.reload();</script><?
                } else {
                    ?><script>//DEBUG parent.location = 'generar_cotizacion.php?reloadCarritoOnId=<? echo $producto->id;?>';</script><?
                }
            }
        }
}

if($_POST['crear']!=""){
	$nombre = $_POST['nombre'];
	$id_catalogo_productos = $_POST['id_catalogo_productos'];
	$precio = $_POST['precio'];
	$costo = $_POST['costo'];
	$id_proveedor = $_POST['id_proveedor'];
	$actualizado_en_microsip = $_POST['actualizado_en_microsip'];
	$archivo_ficha_tecnica = $_POST['archivo_ficha_tecnica'];
	$imagen = $_POST['imagen'];
	$descripcion = $_POST['descripcion'];
	$codigo_microsip = $_POST['codigo_microsip'];
	$codigo_familia = $_POST['codigo_familia'];
    
	//print_r( $_POST);
    $producto = new Producto();
    
    if($producto->create($nombre,$id_catalogo_productos,
            $precio, $costo, $id_proveedor, $actualizado_en_microsip,
            $_FILES['archivo_ficha_tecnica']['name'],
            $_FILES['imagen']['name'],$descripcion,$codigo_microsip,
            $codigo_familia))
    {
        unset($_SESSION['cambiaProducto']);
        ?><script>parent.location.reload();</script><?
    }
}

?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

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
  
  <table width="650px" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="3"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">PRODUCTO
              <input name="from" type="hidden" id="from" value="<? echo end(explode('/',$_SERVER['HTTP_REFERER']));?>">
              <input name="atributo" type="hidden" id="atributo" value="<? echo $_REQUEST['atributo'];?>">
          </div></td>
    </tr>
    <tr>
      <td width="14%" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Nombre Espa&ntilde;ol</td>
      <td width="43" bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) { 
			echo $producto->nombre; 
			} else { ?>
        <input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?php echo htmlspecialchars($producto->nombre); ?>" maxlength="900" />
        <? 	}?></td>
      <td width="244" rowspan="8" class="texto_info_negro"><table border="0" cellspacing="0" cellpadding="1" bordercolor="#CCCCCC">
        <tr>
          <td>Imagen</td>
          <td><span class="style5">
            <input name="imagen" type="file" class="texto_info_negro" id="imagen">
            </span></td>
          </tr>
        <tr>
          <td colspan="2"><div align="center" id="imagen">
            <? if($producto->imagen!=""){?>
            <a href="<? echo $producto->imagen?>" target="_blank"><img src="<?php echo $producto->imagen;?>" alt="" name="imagenMostrar" width="150" height="150" id="imagenMostrar"></a>
            <? } else { echo "Sin imagen";}?>
            </div></td>
          </tr>
        <tr>
          <td colspan="2" class="texto_info_negro">(Tama&ntilde;o 202px x 180px JPG)</td>
          </tr>
      </table></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Nombre Ingles</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><?
    if($vistaLectura) {
        echo $producto->descripcion;
    } else {
		    ?>
        <input name="descripcion" type="text" class="texto_info_negro_forma" id="descripcion" value="<? echo htmlspecialchars($producto->descripcion);?>" maxlength="900" />
        <?
    }
    ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Precio de Venta</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro">$<? if($vistaLectura) echo $producto->precio; ?>
        <input type="<? echo $vistaLectura ? "hidden" : "text";?>" name="precio" id="precio" value="<?php echo $producto->precio; ?>" class="texto_info_negro"/>
        <!--<input name="precio" type="text" class="texto_verde" id="precio" value="<?php echo $producto->precio; ?>" size="10" maxlength="100" />--></td>
      </tr>
    <tr>
      <td align="right" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro"><div align="right">Precio de Lista</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro">$
        <? if($vistaLectura) echo $producto->costo; else { ?>
        <input type="<? echo $vistaLectura ? "hidden" : "text";?>" name="costo" id="costo" value="<?php echo $producto->costo; ?>" class="texto_info_negro"/> <? } ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Unidad M�trica</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) {
		  echo $producto->unidad_metrica;
		  } else {?>
      <input name="unidad_metrica" type="text" class="texto_info_negro_forma" id="unidad_metrica" value="<?php echo $producto->unidad_metrica; ?>" maxlength="50" />
      <?
		  } ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Stock</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) echo $producto->stock; ?>        <input name="stock" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro_forma" id="stock" value="<?php echo $producto->stock; ?>" maxlength="30" <? echo vistaEscritura(false);?> /></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Codigo Descuento</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) echo $producto->codigo_descuento; ?>        <input name="codigo_descuento" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro_forma" id="codigo_descuento" value="<?php echo $producto->codigo_descuento;?>" maxlength="30" <? echo vistaEscritura(false);?> /></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Proveedor</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) {
        $consulta  = "SELECT * FROM Proveedores WHERE id = ".$producto->id_proveedor;
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        $array = @mysql_fetch_assoc($resultado);
        echo $array['nombre'];

        } else {
            ?>
        <select name="id_proveedor" class="texto_info_negro_forma" id="id_proveedor" style="width:200px" <? echo vistaEscritura(true);?>>
          <?
            echo $producto->id_rol;
            ?>
          <option value="0">- -</option>
          <?
	    $consulta  = "SELECT * FROM Proveedores WHERE id != 8";
            $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
            if(@mysql_num_rows($resultado)>=1){
                while($array=mysql_fetch_assoc($resultado)) {
                ?>
          <option value="<? echo $array['id'];?>" <? if($array['id']==$producto->id_proveedor) echo "selected";?>><? echo $array['nombre'];?></option>
          <?
                }
            }
            ?>
          </select>
        <?
        }
    ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Ficha T&eacute;cnica</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro">
        <input name="archivo_ficha_tecnica" type="file" class="texto_info_negro" id="archivo_ficha_tecnica"></td>
      <td width="244" rowspan="3" align="left" class="texto_info_negro">
	  
      
      <? if($producto->archivo_ficha_tecnica!=""){?>
      <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank"><? echo $producto->archivo_ficha_tecnica;?><img src="images/pdf.ico" alt="pdf" width="40" height="40" border="0" /></a>
      <? }?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Familia </div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) {
			if($producto->codigo_familia!=""){
        $consulta  = "SELECT * FROM FamiliaCotizador WHERE id = ".$producto->codigo_familia;
        $resultado = mysql_query($consulta) or print("La consulta lista familias: " . mysql_error());
        $array=mysql_fetch_assoc($resultado);
        echo $array['nombre']." (".$array['codigo'].")";
			} else echo "Sin Familia";

        } else {
            ?>
        <select name="codigo_familia" class="texto_info_negro_forma" id="codigo_familia" style="width:200px" <? echo vistaEscritura(true);?>>
          <option value="">- -</option>
          <?php
	    $consulta  = "SELECT * FROM FamiliaCotizador ORDER BY nombre";
        $resultado_familia= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_familia)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_familia)) {
                ?>
          <option <? if($producto->codigo_familia==$array['codigo']) echo 'selected';?> value="<? echo $array['codigo'];?>"><? echo $array['nombre']." (".$array['codigo'].")";?></option>
          <?
            }
        }
     
	  ?>
          </select>
        <?
	  }
	  ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Codigo Microsip </div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) { 
			echo $producto->codigo_microsip; 
			} else {
				?>
        <? if($vistaLectura) echo $producto->codigo_microsip; ?>
        <input name="codigo_microsip" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro" id="codigo_microsip" value="<? echo $producto->codigo_microsip;?>" <? if($producto!="") echo "readonly";?> <? echo vistaEscritura(false);?>>
        <? }?></td>
      </tr>
    <tr>
      <td colspan="3" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">
      <? if( !$vistaLectura ){ ?>
            <input type="submit" name="<? if($producto!="") echo "guardar"; else echo "crear";?>" value="Guardar" />
            <? } ?></td>
      </tr>
    
    </table>
</div>
</form>
</body>
</html>
