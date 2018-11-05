<?
ini_set('display_errors', 'On');
include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
session_start();
include_once 'changeTipoMoneda.php';
include_once 'functions_agregar_carrito.php';
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['vendedor25']);
/*
 *PERMISOS DE ESCRITURA TIENEN: Administrador, Supervisor y Compras
 */
include_once "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
        $_SESSION['accesos']['compras'],
        $_SESSION['accesos']['vendedor']);

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
    $_SESSION['cambiaProducto']=$producto;
	if($_GET['borrar']=='true'){
		$producto->delete();
		unset($_SESSION['cambiaProducto']);
        ?><script>parent.location.reload();</script><?
	}
}

$guardar = $_POST["guardar"];
if($guardar == "Guardar"){
	
	$nombre = $_POST['nombre'];
	$id_catalogo_productos = $_POST['id_catalogo_productos'];
	
	$costo = $_POST['costo'];
	$id_proveedor = "8";
	$origen=$_POST['origen'];
	$pieces = explode("|", $origen);
	$precio = round($pieces[1]*$costo , 2 );
	$no_actualizado_en_microsip = $_POST['no_actualizado_en_microsip'];
	$archivo_ficha_tecnica = $_POST['archivo_ficha_tecnica'];
	$imagen = $_POST['imagen'];
	$descripcion = $_POST['descripcion'];
	$codigo_familia = $_POST['codigo_familia'];
	$unidad_metrica = $_POST['unidad_metrica'];
	$exportar_microsip = $_POST['exportar_microsip'];
	$stock = "0";
	$codigo_descuento = $pieces[0];
	$tipo_moneda_usa = $_POST['tipo_moneda'];
	$unidad_metrica_ingles = $_POST['unidad_metrica'];
	
	$producto = new Producto();
	$producto->get($_SESSION['cambiaProducto']->id);
		
	if($no_actualizado_en_microsip==1)
		$modificado=0;
	else $modificado =1;
	
    if($producto -> update($nombre,'id',
            $id_catalogo_productos,$precio,
            $costo,$id_proveedor,$modificado,
            $_FILES['archivo_ficha_tecnica']['name'],
            $_FILES['imagen']['name'],
            $descripcion, $codigo_familia,
            $unidad_metrica, $exportar_microsip,
            $stock, $codigo_descuento, $tipo_moneda_usa,
			$unidad_metrica_ingles) ){
        
		
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
}

if($_POST['crear']!=""){
    $nombre = $_POST['nombre'];
    $id_catalogo_productos = $_POST['id_catalogo_productos'];
    
    $costo = $_POST['costo'];
    $id_proveedor = "8";
	$origen=$_POST['origen'];
	$pieces = explode("|", $origen);
	$precio = round($pieces[1]*$costo , 2 );
    $actualizado_en_microsip = $_POST['actualizado_en_microsip'];
    $archivo_ficha_tecnica = $_POST['archivo_ficha_tecnica'];
    $imagen = $_POST['imagen'];
    $descripcion = $_POST['descripcion'];
    $codigo_microsip = $_POST['codigo_microsip'];
    $codigo_familia = $_POST['codigo_familia'];
    $exportar_microsip = $_POST['exportar_microsip'];
    $stock = "0";
    $codigo_descuento = $pieces[0];
    $unidad_metrica = $_POST['unidad_metrica'];
	$unidad_metrica_ingles = $_POST['unidad_metrica_ingles'];
    
    /*
     * Creando producto
     */
    $producto = new Producto();
	$_SESSION['carrito5'] =$producto;
    if( $producto->create($nombre,$id_catalogo_productos,
            $precio, $costo, $id_proveedor, $actualizado_en_microsip,
            $_FILES['archivo_ficha_tecnica']['name'],
            $_FILES['imagen']['name'],$descripcion,
            $codigo_microsip,$codigo_familia,
            $exportar_microsip, $stock, 
            $codigo_descuento, $tipo_moneda_usa,
            $unidad_metrica, $unidad_metrica_ingles)
            )
    {
        $_SESSION['carrito6'] =$producto;
        $producto->cantidad = "1";//$_POST['cantidad'];
		$_SESSION['carrito7'] =$producto;
        agregarCarrito($producto);
        unset($_SESSION['cambiaProducto']);
		
        ?><script>parent.location = 'generar_cotizacion.php?addProductoEspecial=<? echo $producto->id;?>';</script><?
    }
}

function agregarCarrito($producto){
	$producto->cantidad = "1";
    $producto->nombre_proveedor = getNombreProveedor( $producto->id_proveedor);
    
    $tipo_moneda = $_SESSION['cotizacion']->tipo_moneda;
    $valor_moneda = $_SESSION['dollar'];
    
    $producto -> precio_original = $producto->precio;
	
    /*
     * Convirtiendo el precio en caso de USA o MXN
     */
    $producto -> precio = changeTipoMoneda($producto, $tipo_moneda, $valor_moneda);
    /*
     * No es necesario actualizar los totales de la cotizcai[on
     * porque la cantidad sera cero.
     * En caso de que se necesite exactamente debajo de estas
     * lineas deberia estar hubicada y se recomienda usar
     * el metodo update_SubtotalTotalIva_Of_Cotizacion($precio, $cantidad)
     * que esta en agregar_carrito.php
     */
    
    $producto -> agregarACarrito();//agregando a SESSION carrito
    array_push( $_SESSION['cotizacion']->productos ,$producto);//agregando a SESSION Cotizacion
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
  
  <table width="684" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="3"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">PRODUCTO ESPECIAL<input name="from" type="hidden" id="from" value="<? echo end(explode('/',$_SERVER['HTTP_REFERER']));?>"></div></td>
    </tr>
    <tr>
      <td width="120" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Descripción</td>
      <td width="292" bordercolor="#CCCCCC" class="style5"><? if($vistaLectura) { 
			echo $producto->nombre; 
			} else { ?>
        <input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?php echo $producto->nombre; ?>" size="45" maxlength="100" <? echo vistaEscritura(false);?> />
        <? 	}?></td>
      <td class="texto_info_negro"><label>
        <input type="checkbox" name="exportar_microsip" id="exportar_microsip" value="1" <? if($producto->exportar_microsip) echo "checked";?> >
        Exportar a microsip </label></td>
      </tr>
    
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Moneda </td>
      <td bordercolor="#CCCCCC" class="style5"><table width="150" border="0" cellspacing="2" cellpadding="0">
        <tr>
          <td width="20"><input name="tipo_moneda" type="radio" id="tipo_moneda_mx" value="0" <? 
		  if($producto!=""){
			 if($producto->tipo_moneda_usa == 0) echo "checked";
		} else if($_SESSION['cotizacion'] ->tipo_moneda == 0) echo "checked";?> <? echo $esLectura;?>/></td>
          <td width="21" class="texto_info_negro">MX</td>
          <td width="21"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
          <td width="20"><input type="radio" name="tipo_moneda" value="1" id="tipo_moneda_usa" <?  if($producto!=""){
			 if($producto->tipo_moneda_usa == 1) echo "checked";
		}else if($_SESSION['cotizacion'] ->tipo_moneda == 1) echo "checked";?> <? echo $esLectura;?> /></td>
          <td width="30" class="texto_info_negro">USA</td>
          <td width="74" class="texto_info_negro">(<? echo $_SESSION['dollar']?>)</td>
        </tr>
      </table></td>
      <td class="texto_info_negro"><input name="cantidad" type="hidden" id="cantidad" value="2"></td>
    </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Costo</div></td>
      <td bordercolor="#CCCCCC" class="style5">$        
        <input type="text" name="costo" id="costo" value="<?php echo $producto->costo; ?>" class="texto_info_negro"maxlength="100"/></td>
      <td width="237" rowspan="7"><table border="0" cellspacing="0" cellpadding="1" bordercolor="#CCCCCC">
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
      <td align="right" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro"><div align="right">Precio de Venta</div></td>
      <td bordercolor="#CCCCCC" class="style5">$
        
        <?php echo $producto->precio; ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Unidad Métrica
        <!--Unidad Métrica Inglés--></td>
      <td bordercolor="#CCCCCC" class="style5"><span class="style51">
        <select name="unidad_metrica" class="texto_info_negro_forma" id="unidad_metrica"  >
          <option value="">Unidad Metrica</option>
          <?php
    $consulta  = "SELECT id, nombre from medidas order by nombre";
	//echo"$consulta";
    $resultado_clientes= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
    while($array=mysql_fetch_assoc($resultado_clientes)) {
        ?>
          <option value="<? echo $array['nombre'];?>" <? if($producto->unidad_metrica==$array['nombre'])echo"selected";?>><? echo $array['nombre'];?></option>
          <?
    }
    ?>
        </select>
        </span></td>
      </tr>
    
    
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Origen</div></td>
      <td bordercolor="#CCCCCC" class="style5">
        <select name="origen" class="texto_info_negro_forma" id="origen" style="width:120px" <? echo vistaEscritura(true);?>>
          
          <?
	    $consulta  = "SELECT * FROM origen_prod_especial order by id";
            $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
           // if(@mysql_num_rows($resultado)>=1){
                while($array=mysql_fetch_assoc($resultado)) {
                ?>
          <option value="<? echo $array['id'];?>|<? echo $array['factor'];?>" <? if($producto->codigo_descuento==$array['id'])echo"selected";?>><? echo $array['nombre'];?></option>
          <?
                }
          //  }
            ?>
        </select>
        <?
      //  }
    ?></td>
    </tr>
    
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Ficha T&eacute;cnica</div></td>
      <td bordercolor="#CCCCCC" class="style5"><?
		if($producto->archivo_ficha_tecnica!=""){?>
        <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank"><? echo end(split('/',$producto->archivo_ficha_tecnica));?></a><br>
        <? }?>
        <input name="archivo_ficha_tecnica" type="file" class="texto_info_negro" id="archivo_ficha_tecnica"></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Proveedor </div></td>
      <td bordercolor="#CCCCCC" class="style5">
        <input name="descripcion" type="text" class="texto_info_negro_forma" id="descripcion" value="<?php echo $producto->descripcion; ?>" size="45" maxlength="100" />
        
        <input name="codigo_familia" type="hidden" id="codigo_familia" value=""></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Codigo Microsip </div></td>
      <td bordercolor="#CCCCCC" class="style5"><? if($vistaLectura) { 
			echo $producto->codigo_microsip; 
			} else {
				?>
        <input name="codigo_microsip" type="text" class="texto_info_negro" id="codigo_microsip" value="<? echo $producto->codigo_microsip;?>" <? if($producto!="") echo "readonly";?> <? echo vistaEscritura(false);?>>
        <? }?></td>
      </tr>
    <tr>
      <td colspan="3" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><input type="submit" name="<? if($producto!="") echo "guardar"; else echo "crear";?>" value="Guardar" /></td>
      </tr>
    </table>
</div>
</form>
</body>
</html>
