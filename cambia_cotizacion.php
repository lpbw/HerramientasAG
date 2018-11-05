<?
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);
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
            $descripcion,$codigo_familia))
    {
        unset($_SESSION['cambiaProducto']);
        ?><script>parent.form1.submit();</script><?
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
</head>

<body>
<div style="overflow:scroll;width: 740px;height: 470;">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="100%" border="0" align="center" cellpadding="0">
    <tr>
                  <td colspan="2" bgcolor="#999999" class="style8" scope="row"><div align="center" class="style4 style6"><span class="style7 style6">Cotizaci&oacute;n</span></div></td>
    </tr>
    <tr>
      <td width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="1" width="100%">
        <tr>
          <th width="33%" align="left" scope="col"><strong>Cliente</strong></th>
          <th width="67%" align="left" scope="col"><span class="style5">
            <select name="id_cliente" id="id_cliente" style="width:200px">
              <option value="">- -</option>
              <?php
	    $consulta  = "SELECT * FROM EstatusCotizaciones";
        $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_estatus)>=1){
            while($array=mysql_fetch_assoc($resultado_estatus)) {
                ?>
              <option <? if($proveedor==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
              <?
            }
        }
     
		  ?>
            </select>
          </span></th>
        </tr>
        <tr>
          <td align="left"><strong>Estatus</strong></td>
          <td><span class="style5">
            <select name="id_estatus" id="id_estatus" style="width:200px">
              <option value="">- -</option>
              <?php
	    $consulta  = "SELECT * FROM EstatusCotizaciones";
        $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_estatus)>=1){
            while($array=mysql_fetch_assoc($resultado_estatus)) {
                ?>
              <option <? if($proveedor==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
              <?
            }
        }
     
		  ?>
            </select>
          </span></td>
        </tr>
        <tr>
          <td align="left"><strong>Vendedor</strong></td>
          <td><span class="style5">
            <select name="id_usuario" id="id_usuario" style="">
              <option value="">- -</option>
              <?php
	    $consulta  = "SELECT * FROM Usuarios";
        $resultado_usuarios= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_usuarios)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_usuarios)) {
                ?>
              <option <? if($id_usuario==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
              <?
            }
        }
     
		  ?>
            </select>
          </span></td>
        </tr>
        <tr>
          <td align="left">Prioridad</td>
          <td><span class="style5">
            <select name="id_usuario2" id="id_usuario2" style="">
              <option value="">- -</option>
              <?php
	    $consulta  = "SELECT * FROM Usuarios";
        $resultado_usuarios= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_usuarios)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_usuarios)) {
                ?>
              <option <? if($id_usuario==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
              <?
            }
        }
     
		  ?>
            </select>
          </span></td>
        </tr>
        <tr>
          <td>Archivos</td>
          <td><div id="div_archivos" style="width:100%;height:50px"></div></td>
        </tr>
        <tr>
          <td colspan="2">Notas adicionales</td>
          </tr>
        <tr>
          <td colspan="2"><textarea name="textarea3" id="textarea3" cols="45" rows="5"></textarea></td>
          </tr>
        <tr>
          <td colspan="2">Terminos de pago</td>
          </tr>
        <tr>
          <td colspan="2"><textarea name="textarea2" id="textarea2" cols="45" rows="5"></textarea></td>
          </tr>
        <tr>
          <td colspan="2">Terminos de Entrega</td>
          </tr>
        <tr>
          <td colspan="2"><textarea name="textarea" id="textarea" cols="45" rows="5"></textarea></td>
        </tr>
      </table></td>
      <td width="50%" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <th colspan="2" scope="col">Productos</th>
          </tr>
        <tr>
          <td colspan="2"><table border="0" width="100%">
            <tr  bgcolor="#999999" class="style6" >
              <th width="70%" scope="col">Producto</th>
              <th width="15%" scope="col">Cantidad</th>
              <th width="15%" scope="col">Eliminar</th>
              </tr>
            <?
  $color ="#CCCCCC";
foreach ($_SESSION['carrito'] as $n => $producto) {
		?>
            <tr <? if($color!="#CCCCCC"){$color="#CCCCCC"; echo "bgcolor=\"$color\"";} else $color="";?>>
              <td><? echo $producto->nombre;?></td>
              <td align="center"><span class="style5">
                <input name="cantidad[]" type="text" id="cantidad<? echo $n?>" value="<? echo $producto->cantidad;?>" size="6" maxlength="3" />
                </span></td>
              <td align="center"><a href="#" class="iframe" onClick="eliminarProducto(<? echo $n;?>);"><img src="images/close.gif" alt="close" width="15" height="13" border="0" /></a></td>
              </tr>
            <?
}
?>
            <tr>
              <td>&nbsp;</td>
              <td align="center">&nbsp;</td>
              <td align="center"><input type="submit" name="guardar" id="guardar" value="Guardar"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="27%">Archivos</td>
          <td width="73%"><div id="div_archivos" style="width:100%;height:50px"><div id="archivo1"  style="width:100%;height:20px; background-color:#999; border:medium"><div style=" float:left">Archivo1 </div>
            <div align="center" style="float:right; height:100%;">
            <a href="generar_cotizacion.php?id=<? echo $res['idCotizacion'];?>&borrar=true" class="iframe" onClick="return borrar('<? echo $res['idCotizacion'];?>');"><img src="images/close.gif" alt="close" width="15" height="13" border="0" /></a></div></div>
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" valign="top">&nbsp;</td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input type="submit" name="<? if($producto!="") echo "guardar"; else echo "crear";?>" value="Guardar" />
      </div></td>
    </tr>
  </table>
</form>
</div>
</body>
</html>
