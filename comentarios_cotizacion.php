<?
if($_REQUEST['id']!=""){
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";

$debug = intval($_REQUEST['test']);

include "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
        $_SESSION['accesos']['vendedor']);


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
	background-color: #e3e3e3;
	margin-top: -10px;
}
.style51 {font-size: 12}
</style>

<link href="images/textos.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript">

function borrar(id)
{
  if(confirm("Esta seguro de borrar?")){
	  document.location = "cambia_comentario_cotizacion.php?id="+id+"&borrar=true";
   };
}
</script>
</head>

<body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div  style="margin-top:10px; width:100%;" align="right">
<? if($debug){?><table width="200" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="146" height="22" background="images/boton_submenu_2.jpg">
    <table border="0" align="left" cellpadding="4" cellspacing="0">
      <tr>
        <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
        <td class="texto_menu_slice"><a href="#" class="texto_menu_slice" onClick="parent.abrir3('cambia_comentario_cotizacion.php');" >AGREGAR COMENTARIO</a></td>
      </tr>
    </table></td>
  </tr>
</table><? }?>
</div>
<?  

foreach ($_SESSION['cotizacion']->getComentarios() as $comentario) { 

?>
<div align="center" style="margin: 10px;">
<div class="texto_info" align="right"><? echo $comentario['fecha'];?></div>
<div align="left" class="texto_info_negro" style="background-color: #FFF;-moz-border-radius: 20px;-webkit-border-radius: 10px;border-radius: 10px; padding: 10px;" >
     <a href="#" class="texto_info_negro" onClick="parent.abrir('cambia_comentario_cotizacion.php?id=<? echo $comentario['id'];?>',true);">
      <? echo $comentario['descripcion']; ?></a>
      <span style="float:right;">
   <? if(!$vistaLectura){?>
          <a href="#" onClick="borrar(<? echo $comentario['id'];?>);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<? echo $comentario['id'];?>','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="" name="Image<? echo $comentario['id'];?>" width="17" height="16" border="0" id="Image86" /></a>
      <? }?></span></div>
        
</div>
		<? 
        }?>
</form>
</body>
</html>
<? }?>