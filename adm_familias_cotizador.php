<?
//include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['administrador']);
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
<!--	background-color: #FFFFFF;-->
	background-image: url(images/bkg_1.jpg);
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
<link rel="stylesheet" href="colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="colorbox/jquery.colorbox-min.js"></script>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				
				$(".iframe").colorbox({iframe:true,width:"600", height:"553",transition:"fade", scrolling:false, opacity:0.5});
				$(".iframe2").colorbox({iframe:true,width:"650", height:"503",transition:"fade", scrolling:false, opacity:0.5});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});

function borrar(nombre , id)
{
  if(confirm("Esta seguro de borrar "+nombre+"?")){
	  document.location = "cambia_familia_cotizador.php?id="+id+"&borrar=true";
   };
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

<body onload="MM_preloadImages('images/cerrar_r.jpg')" >
<form id="form1" name="form1" method="post" action="myadmin.php">
        <table width="517" border="0" align="center">
            <tr>
              <td width="33%">&nbsp;</td>
              <td width="33%" align="center" class="texto_info_negro">FAMILIAS</td>
              <td width="33%" class="style9"><div align="right"><a href="cambia_familia_cotizador.php" class="texto_info_negro">Nuevo +</a> </div></td>
            </tr>
            <tr>
              <td colspan="3"><div align="center">
                  <table width="100%" border="0" cellpadding="0">
                    <tr class="titulo_tabla" style="background-image:url(images/bkg_1.jpg)">
                      <td width="58%" class="style6" scope="row"><div align="center">Nombre</div></td>
                      <td width="20%" class="style8" scope="row"><div align="center" class="style5 style6">Codigo de la Familia</div></td>
                      <td width="17%" class="texto_info_blanco"><div align="center" class="texto_info_blanco_forma">Descuento Online </div></td>
                      <td width="5%" class="style8"><div align="center">&nbsp;</div></td>
                    </tr>
                    <?	  

	
	
	$consulta  = "SELECT * FROM FamiliaCotizador
			ORDER BY nombre";
	$resultado = mysql_query($consulta) or print("La consulta fallo en lista: " . mysql_error());
	$count=1;
	while(@mysql_num_rows($resultado)>=$count)
	{
		$res=mysql_fetch_assoc($resultado);
		
		?>
                    <tr>
                      <td class="style5"><div align="center"><a href="cambia_familia_cotizador.php?id=<? echo $res['id'];?>" class="texto_info_negro"><? echo $res['nombre'];?></a></div></td>
                      <td align="center" class="style5"><span class="texto_info_negro"><a href="cambia_familia_cotizador.php?id=<? echo $res['id'];?>" class="texto_info_negro"><? echo $res['codigo'];?></a></span></td>
                      <td class="style5"><div align="center"><a href="cambia_familia_cotizador.php?id=<? echo $res['id'];?>" class="texto_info_negro"><? echo $res['factor'];?></a></div></td>
                      <td class="style5"><div align="center">
  <a href="#" onclick="borrar('<? echo $res['nombre'];?>',<? echo $res['id'];?>);" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image<? echo $res['id'];?>','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="close" width="17" height="16" border="0" id="Image<? echo $res['id'];?>" /></a></div></td>
                    </tr>
                    <?
			   $count=$count+1;
	}
	
?>
                  </table>
              </div></td>
            </tr>
</table>
</form>
        
<form action="" name="form_borrar" target="_self" id="form_borrar"  method="post"></form>
</body>
</html>
