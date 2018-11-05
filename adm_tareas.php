<?
ini_set('display_errors', '1');
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
date_default_timezone_set('America/Chihuahua');

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

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

function borrar(id)
{
  if(confirm("Esta seguro de borrar?")){
	  document.location = "cambia_tarea.php?id="+id+"&borrar=true";
   };
}
</script>
</head>

<body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div  style="margin-top:10px; width:100%;" align="right">
  <table width="200" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="146" height="22" background="images/boton_submenu_2.jpg"><table border="0" align="left" cellpadding="4" cellspacing="0">
        <tr>
          <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
          <td class="texto_menu_slice"><a href="#" class="texto_menu_slice" onClick="parent.abrir('cambia_tarea.php<? if(!intval($_REQUEST['fromCotizacion']) ) echo "?new=true"; ?>',false);" >AGREGAR TAREA</a></td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
<? 
if( isset($_REQUEST['fromCotizacion']))
{ 
if( $_REQUEST['fromCotizacion'] == "1" ){
    $tareas = $_SESSION['cotizacion']->getTareas();
    
foreach ($tareas as $tarea) { ?>
    <div align="center" style="margin: 10px;">
    <div class="texto_info" style="height:20px;">
    <div style="float:left">inicio: <? echo $tarea['fecha_inicio_user_friendly'];?></div>
    <span stylez="float:right;margin:0px 5px 0px 5px; width:17px; height:16px; border:0">
       <? if(!$vistaLectura && $tarea['fecha_recordatorio']!=""){?>
              <a href="#" onClick="parent.abrir2('cambia_tarea.php?id=<? echo $tarea['id'];?>',true);" >
              <img src="images/reminder.png" alt="" name="Image86" width="17" height="16" border="0" title="<? echo $tarea['fecha_recordatorio_user_friendly'];?>" /></a>
          <? }?></span>
    <div style="float:right">vence: <? echo $tarea['fecha_vencimiento']==""? " -sin fecha- ":$tarea['fecha_vencimiento_user_friendly'];?></div>

    </div>
    <div align="left" class="texto_info_negro" style="background-color: #FFF;-moz-border-radius: 20px;-webkit-border-radius: 10px;border-radius: 10px; padding: 10px;" >
         <a href="#" class="texto_info_negro" onClick="parent.abrir('cambia_tarea.php?id=<? echo $tarea['id'];?>',false);">
          <? echo $tarea['asunto']; ?></a>
          <span style="float:right;">
       <? if(!$vistaLectura){?>
              <a href="#" onClick="borrar(<? echo $tarea['id'];?>);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<? echo $tarea['id'];?>','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="" name="Image<? echo $tarea['id'];?>" width="17" height="16" border="0" id="Image86" /></a>
          <? }?></span>
    </div>
        </div><?
    }
    
} 
}
else {
    
    $tareas = $_SESSION['usuario']->getTareas();
	?>
    <div id="containerTareas" style="overflow:auto; padding:0px 30px 0px 30px">
    <?

    foreach ($tareas as $tarea) { 

    ?>
    <div align="center" style="margin: 10px;">
    <div class="texto_info" style="height:20px;">
    <div style="float:left;">inicio: <? echo $tarea['fecha_inicio_user_friendly'];?></div>
    
    <span style="float:right;margin:0px 5px 0px 5px; width:17px; height:16px; border:0">
       <? if(!$vistaLectura && $tarea['fecha_recordatorio']!=""){?>
              <a href="#" onClick="parent.abrir2('cambia_tarea.php?id=<? echo $tarea['id'];?>',true);" >
              <img src="images/reminder.png" alt="" name="Image86" width="17" height="16" border="0" title="<? echo $tarea['fecha_recordatorio_user_friendly'];?>" /></a>
          <? }?></span>
    <div style="float:right">vence: <? echo $tarea['fecha_vencimiento']==""? " -sin fecha- ":$tarea['fecha_vencimiento_user_friendly'];?></div>

    </div><div style="float:left; width:100%"> <span class="texto_chico_gris"><? echo $tarea['id_cotizacion']!="" ? "Cotizacion # ".$tarea['id_cotizacion']:""; ?></span></div>
    <div align="left" class="texto_info_negro" style="background-color: #FFF;-moz-border-radius: 20px;-webkit-border-radius: 10px;border-radius: 10px; padding: 10px;" >
         <a href="#" class="texto_info_negro" onClick="parent.abrir('cambia_tarea.php?id=<? echo $tarea['id'];?>',false);">
          <? echo $tarea['asunto']; ?></a>
          <span style="float:right;">
       <? if(!$vistaLectura){?>
              <a href="#" onClick="borrar(<? echo $tarea['id'];?>);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<? echo $tarea['id'];?>','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="" name="Image86" width="17" height="16" border="0" id="Image<? echo $tarea['id'];?>" /></a>
          <? }?></span>
    </div>
        </div>
    <? }
}?>
</div>
</form>
</body>
</html>