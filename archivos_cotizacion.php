<?
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";


include "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
        $_SESSION['accesos']['vendedor']);


if(isset($_POST['idBorrar'])){
    $consulta  = "DELETE FROM Archivos_Cotizaciones WHERE id={$_POST['idBorrar']} AND id_cotizacion = {$_SESSION['cotizacion']->id}";
    $resultado = mysql_query($consulta) or print("Error en operacion $consulta: " . mysql_error());
    unset($_SESSION['cotizacion'] ->archivos[intval($_POST['idBorrar'])-1]);
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
	background-color: #e3e3e3;
	margin-top: -10px;
}
.style51 {font-size: 12}
</style>

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script type="text/javascript">

function eliminarArchivo(id)
{
  if(confirm("Esta seguro de borrar?")){
      document.getElementById('idBorrar').value=id;
      document.getElementById('borrar').submit();
   }
}
</script>
</head>

<body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/carrito_r.jpg')">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div  style="margin-top:10px; width:100%;" align="right">
  <table width="146" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
          <td class="texto_menu_slice"><a href="#" class="texto_menu_slice" 
                        onclick="parent.abrir3 ('agregar_archivo.php');"> AGREGAR ARCHIVO</a></td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>

                <?
if(isset($_SESSION['cotizacion'] )){ ?>
<div id="containerArchivos" style="width:100%; margin:10px">

      <?  foreach ($_SESSION['cotizacion'] ->archivos as $key => $archivo) {
        ?><a href="<? echo $archivo['location'];?>" target="_blank">
          <div id="archivo<? echo $key;?>" style="height:40px; margin:5px; float:left;width: 200px;margin-left: 15px;">
            <span style="float:right; width:84%">
              <? echo $archivo['nombre_real'];?>              </span><img src="images/archivo.png" width="32" border="0" style="float:right" /> </div>
          </a>
          <? if(!$vistaLectura){?>
<a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','images/cerrar_r.jpg',1)" class="iframe" 
   onClick="eliminarArchivo(<? echo intval($key)+1;?>);">
    <img src="images/cerrar.jpg" alt="" name="Image1" width="17" height="16" border="0" id="Image1" style="float:left"/></a>
<? } ?>
        <?
        }
        ?>
      </div>
      <? }//fin if check cotizacionsession ?>
</form>
    <form name="borrar" id="borrar" action="" method="post">
        <input type="hidden" name="idBorrar" id="idBorrar"/>
    </form>
</body>
</html>