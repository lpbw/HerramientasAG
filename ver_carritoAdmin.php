<?
//include 'Usuario.php';
include 'Producto.php';
include 'Cliente.php';
session_start();

include "coneccion.php";


//function guardarCarrito(){
//	 foreach ($_SESSION['carrito'] as $n => $producto) {
//        $producto->cantidad = $_POST['cantidad'][$n];
//        $producto->precio = $producto->precio * $_SESSION['cotizacion']->valor_moneda ;
//        $producto->costo = $producto->costo * $_SESSION['cotizacion']->valor_moneda ;
//    }
//}


if($_POST['posicion_borrar']!=""){
	unset($_SESSION['carrito'][$_POST['posicion_borrar']]);
}

if(count($_SESSION['carrito'])==0){
    ?> <script>
    alert('No hay productos seleccionados');
    parent.jQuery.colorbox.close();</script><?
}

//if($_POST['guardar']!=""){
//    guardarCarrito();
//}

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
	function eliminarProducto(posicion_borrar){
		var element = document.createElement('input');
		element.name='posicion_borrar';
		element.value = posicion_borrar;
		element.type = 'hidden';
		var form = document.getElementById('formBorrarProductoCarrito');
		form.appendChild(element);
		form.submit()
	}
//	function validar(){
//            for(var i=0;i<obj;i++){
//                if(document.getElementById('cantidad'+i).value=="")
//                    alert('fa');
//            }
//		var form_cotizacion = document.getElementById('form1');
//		form_cotizacion.action = "generar_cotizacion.php";
//		return true;
//	}
</script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.style5 {font-size: 18}
-->
</style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<table border="0" width="100%">
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
    <td align="center">
        <!--<input type="submit" name="guardar" id="guardar" value="Guardar">-->
    </td>
  </tr>
</table>
</form>
<form action="" method="post" name="formBorrarProductoCarrito" id="formBorrarProductoCarrito"></form>
</body>
</html>
