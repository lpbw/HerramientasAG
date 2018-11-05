<?
include 'Cartera_clientes.php';
include 'Producto.php';	
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

$debug = FALSE;
if($_GET['id']!=""){
    $id = $_GET['id'];
    $query = "SELECT origen , id_catalogo_productos FROM Productos WHERE id = $id";
    $result = mysql_query($query) or print(mysql_error().$query);
    if($result){
        $datosProducto = mysql_fetch_assoc($result);
        $origen = $datosProducto['origen'];
        $id_catalogo = $datosProducto['id_catalogo_productos'];

        $query = "SELECT * FROM CatalogoProductos WHERE id = $id_catalogo";
        $result = mysql_query($query) or print(mysql_error()).$query;
        if($result){
            $factores = mysql_fetch_assoc($result);
            $factor1 = $factores['factor'];
            $factor2 = $factores['factor2'];
        }
    } else {
    ?><script>parent.cerrarV();</script><?
    }
}

if($_POST["guardar"]=="Guardar"){
    $id = $_POST['id_producto'];
    $origen = $_POST['origen'];
    
    $producto = new Producto();
    $producto -> get($id);
    
    if(!$debug)
        $precio = $producto -> setPrecioAndFactor($origen);    
    
    if($precio){
        ?><script>
            parent.agregarCarrito(<? echo $_REQUEST['objNum'];?>,<? echo $_REQUEST['id'];?>,false);
            alert('producto modificado y agregado a la cotizacion');
            parent.document.formBuscar.submit();
        </script><?
    }
}


if($_POST["guardarFromDirectlyAdd"]=="Guardar"){
    $id = $_POST['id_producto'];
    $origen = $_POST['origen'];
    
    $producto = new Producto();
    $producto -> get($id);
    
    if(!$debug)
        $precio = $producto -> setPrecioAndFactor($origen);    
    
    if($precio){
        ?><script>
            window.parent.location = 'agregar_carrito.php?id=<?echo $id;?>&cantidad=1&backTo=generar_cotizacion.php';
        </script><?
    }
}
if($debug)
    print_r($_REQUEST);
?>
<html>
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Seleccionar Origen</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
-->
</style>
<script>
function validar(){
    var returnn = true;
    if(document.getElementById('origen').value==0){
        alert('selecciona origen');
        document.getElementById('origen').focus();
        returnn= false;
    }
    return returnn;
}</script>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="90%" border="0" align="center" cellpadding="0">
    <tr>
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">Seleccionar Factor de Origen 
                      <input name="id_producto" type="hidden" id="id_producto" value="<? echo $_GET['id'];?>">
      </div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td><table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <th width="29%" valign="top" class="texto_info" scope="row"><div align="right">Factor  </div></th>
          <td width="71%" class="style5"><span style="float:left">
            <select  name="origen" id="origen" style="width:200px">
              <option value="0">-- factor de origen --</option>
              <?
              $enum = array('FACTOR1'=>$factores['factor'].'-'.$factores['origenFactor'],
                  'FACTOR2'=>$factores['factor2'].'-'.$factores['origenFactor2']);
		  
    foreach( $enum as $name => $factorNumber ){
        ?><option value="<? echo $name?>" <? if($origen==$name) echo "selected";?> ><? echo $factorNumber;?></option><?
    }
     
		  ?>
            </select>
          </span></td>
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
        <? if(isset($_REQUEST['guardarFromDirectlyAdd'])){?>
        <input type="submit" name="guardarFromDirectlyAdd" value="Guardar" onClick=" return validar();" />
        <? } else {?>
        <input type="submit" name="guardar" value="Guardar" onClick=" return validar();" />
        <? } ?>
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="id" value="<? echo $_REQUEST['id'];?>">
  <input type="hidden" name="objNum" value="<? echo $_REQUEST['objNum'];?>">
</form>
</body>
</html>
