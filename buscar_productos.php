<?
//include "Usuario.php";
session_start();
//include "checar_sesion_admin.php";
include "coneccion.php";
//include "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['todos']);


if($_POST['buscar']!=''){
    $nombre = $_POST['nombre'];
    $familia = $_POST['familia'];
    $proveedor = $_POST['proveedor'];
    $where  =" Proveedores.id<>7 ";
    if($nombre!=""){
        $where.= " Productos.nombre LIKE '%$nombre%' AND ";
    }
    if($familia!= ""){
        $where.= " FamiliaCotizador.id = '$familia' AND ";
    }
    if($proveedor!=""){
        $where .= " Proveedores.id = $proveedor AND ";
    }

    if($nombre!="" || $familia!= "" || $proveedor!=""){
//        $where = " WHERE $where";
        $consulta  = "SELECT Productos.nombre, Productos.id, CatalogoProductos.nombre AS catalogo, precio, archivo_ficha_tecnica,
                Proveedores.nombre AS proveedor, FamiliaCotizador.nombre AS familia
            FROM Productos
            LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor
            LEFT OUTER JOIN CatalogoProductos ON CatalogoProductos.id = Productos.id_catalogo_productos
            LEFT OUTER JOIN FamiliaCotizador ON FamiliaCotizador.codigo = Productos.codigo_familia
            WHERE $where 1
            ORDER BY Productos.nombre";
//        echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error());    
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #FFFFFF;
	background-image: url(images/bkg_1.jpg);
}
.agregados {
	font-family: sans-serif;
	font-size: x-small;
	background-color: #7FFF00;
}
-->
</style>
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>


<script>
//        function agregarCarrito(objCantidad,id_prod){
//            var cantidad = document.getElementById(objCantidad).value;
//            $.colorbox({iframe:true,opacity:0.5,href:"agregar_carrito.php?id="+id_prod+"&cantidad="+cantidad,width:"800", height:"553"});
//				
//        }
var obj=null;
function agregarCarrito(objCantidad,id_prod){
    obj = objCantidad;
    var cantidad = document.getElementById('cantidad'+obj).value;
    var xmlhttp;
    if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else  {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var cantidad = xmlhttp.responseText;
//            document.getElementById("myDiv").innerHTML=x;
            document.getElementById('cantidad'+obj).value="";
            document.getElementById('agregados'+obj).innerHTML=""+cantidad+" agregados";
        }
    }
    xmlhttp.open("GET","agregar_carrito.php?id="+id_prod+"&cantidad="+cantidad,true);
    xmlhttp.send();
}
</script>


<link rel="stylesheet" href="colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="colorbox/jquery.colorbox-min.js"></script>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				
				$(".iframe").colorbox({iframe:true,width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
			
			function cerrarV()
{

$.fn.colorbox.close();
}
function borrar(nombre){
	if(confirm("�Esta seguro de borrar a "+nombre+"?"))
		return true;
	else
		return false;
}
function abrir(ir)
{
	$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
}
		</script>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style5 {font-size: 18}
.style6 {color: #FFFFFF}
-->
</style>
</head>

<body >
<table width="1024" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="1024" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="276" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="35" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="78" bgcolor="#FFFFFF"></td>
        <td width="167" bgcolor="#FFFFFF"></td>
        <td width="161" bgcolor="#FFFFFF"></td>
        <td width="191" bgcolor="#FFFFFF"></td>
        <td bgcolor="#FFFFFF" class="texto_tit_nuestra"><div align="center"><a href="menu.php" class="texto_tit_nuestra">Men&uacute;</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
        
        <table width="1024" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="307" valign="top" bgcolor="#FFFFFF">
          <p align="center" class="texto_tit_nuestra">Carrito de Compras</p>
          <form id="formBuscar" name="formBuscar" method="post" action="">
          <table width="680" border="0" align="center">
            <tr   bgcolor="#999999" class="style6">
              <td colspan="6">Buscar por:</td>
              </tr>
            <tr>
              <td width="9%">Nombre del producto</td>
              <td width="23%" class="style9"><input type="text" name="nombre" value="<? echo $nombre;?>" /></td>
              <td width="14%" class="style9">Codigo de Familia</td>
              <td width="22%" class="style9"><span class="style5">
                <select name="familia" id="familia" style="width:200px">
                  <option value="">- -</option>
                  <?php
	    $consulta  = "SELECT * FROM FamiliaCotizador";
        $resultado_familia= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_familia)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_familia)) {
                ?>
                  <option <? if($familia==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre']." (".$array['codigo'].")";?></option>
                  <?
            }
        }
     
		  ?>
                </select>
              </span>                <!--<input type="text" name="familia" value="<? echo $familia ;?>" />--></td>
              <td width="10%" class="style9">Proveedor</td>
              <td width="22%" class="style9"><!--<input type="text" name="proveedor" value="<? echo $proveedor;?>" />-->
                <span class="style5">
                <select name="proveedor" id="proveedor" style="width:200px">
                  <option value="">- -</option>
                  <?php
	    $consulta  = "SELECT * FROM Proveedores where  Proveedores.id<>7";
        $resultado_proveedor = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_proveedor)>=1){
            while($array=mysql_fetch_assoc($resultado_proveedor)) {
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
              <td>&nbsp;</td>
              <td class="style9">&nbsp;</td>
              <td colspan="2" class="style9">&nbsp;</td>
              <td colspan="2" align="right" class="style9"><input type="submit" name="buscar" id="buscar" value="Buscar" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td class="style9">&nbsp;</td>
              <td colspan="2" class="style9">&nbsp;</td>
              <td colspan="2" class="style9">&nbsp;</td>
            </tr>
            <tr>
              <td><input type="button" onclick="abrir('ver_carrito.php')" name="comprar" id="comprar" value="Comprar" /></td>
              <td colspan="3" class="style6">&nbsp;</td>
              <td colspan="2" align="right" class="style9"><a href="ver_carrito.php" class="iframe">Ver carrito</a></td>
            </tr>
          </table>
          </form>
          
          <form method="POST" name="formAgregarProducto" id="formAgregarProducto">
              <table width="680" border="0" align="center">
            <tr>
              <td width="38%" bgcolor="#999999" class="style6" scope="row"><div align="center">Nombre</div></td>
              
              <td width="22%" bgcolor="#999999" class="style8" scope="row"><div align="center" class="style5 style6">Familia</div></td>
              <td width="16%" bgcolor="#999999" class="style8" scope="row"><div align="center" class="style5 style6">Proveedor</div></td>
              <td width="11%" bgcolor="#999999" class="style8" scope="row"><div align="center" class="style5 style6">Ficha T&eacute;cnica</div></td>
              <td width="11%" bgcolor="#999999" class="style8" scope="row"><div align="center" class="style5 style6">Cantidad</div></td>
              <td width="2%" bgcolor="#999999" class="style8">&nbsp;</td>
            </tr>
            <?	  

	$count=1;
  $color ="#CCCCCC";
	while(@mysql_num_rows($resultado)>=$count)
	{
		$res=mysql_fetch_assoc($resultado);
		
		?>
            <tr <? if($color!="#CCCCCC"){$color="#CCCCCC"; echo "bgcolor=\"$color\"";} else $color="";?>>
              <td class="style5"><div align="center"><a href="ver_producto.php?id=<? echo $res['id'];?>" class="texto_contenido iframe"><? echo $res['nombre'];?></a></div></td>
              
              <td class="style5"><div align="left"><a href="ver_producto.php?id=<? echo $res['id'];?>" class="texto_contenido iframe"><? echo $res['familia'];?></a></div></td>
              <td class="style5"><div align="left"> <span class="texto_contenido"><a href="ver_producto.php.php?id=<? echo $res['id'];?>" class="texto_contenido iframe"><? echo $res['proveedor'];?></a></span></div></td>
              <td class="style5"><div align="left">
                <? if($res['archivo_ficha_tecnica']!=""){?>
                <a href="<? echo $res['archivo_ficha_tecnica'];?>" target="_blank"><img src="images/pdf.ico" alt="pdf" width="20" /></a>
                <? }?>
              </div></td>
              <td class="style5"><!--<input name="producto[]" type="checkbox" id="producto" value="<? echo $res['id'];?>" />-->
                <input name="cantidad<? echo $count?>" type="text" id="cantidad<? echo $count?>" size="6" maxlength="3" />
                <div class="agregados" id="agregados<? echo $count?>"></div></td>
              <td class="style5">
              <a href="#" onclick="agregarCarrito('<? echo $count?>',<? echo $res['id'];?>)" ><img name="" src="images/carrito.ico" width="32" height="32" alt="" /></a></td>
            </tr>
            <?
			   $count=$count+1;
	}
	if($count==1&&($nombre!="" || $familia!= "" || $proveedor!="")){
		?><tr>
              <td colspan="7" class="style5"><a href="pedido_especial.php" class="iframe">No encontraste el producto?. Haz un pedido especial . . .</a></td>
              </tr><?
	}
	
?>
          </table>
<p>&nbsp;</p>
        </form></td>
        </tr>
    </table>
    </td>
  </tr>
  <tr>
    <td height="63" valign="top">
    <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#3E1C58"><div align="center" class="texto_direccion">Add HR | Cormor&aacute;n 3, Lomas de las &Aacute;guilas  | M&eacute;xico, D.F. 01730  | T. 2591.8330  | contacto@addhr.com.mx | &copy; Copyright 2013 Add HR</div></td>
      </tr>
    </table>
          </form>          
          
 </td>
 </tr>
</table>
    <div id="myDiv"></div>
</body>
</html>
