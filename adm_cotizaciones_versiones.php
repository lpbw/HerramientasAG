<?
include "Usuario.php";
include 'Producto.php';
include 'Cliente.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['mostrador']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
.style511 {font-size: 18}
.style511 {font-size: 18}
-->
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cotizaciones </title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: -10px;
	background-color: #FFFFFF;
	margin-top: -10px;
}
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<script>

function editarCotizacion( id , id_version ){
    var form = document.getElementById('formEditarCotizacion');
	var x = parent;
	document.getElementById('idCotizacionEditar').value = id;
	document.getElementById('idVersion').value = id_version;
	document.getElementById('esVersionActual').value = 'FALSE';
	form.submit();
}

function borrar(nombre){
    if(confirm("¿Esta seguro de borrar cotizacion?"))
        return true;
    else return false;
}
//<--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
//-->
</script>

<style type="text/css">
<!--
.style5 {font-size: 12}
.style51 {font-size: 18}
-->
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
            <td width="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
                  <tr>
                    <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">#</div></td>
                    <td align="center" bgcolor="#DD1A22" class="texto_info_blanco">Fecha</td>
                    <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Cliente</div></td>
                    <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Costo Total</div></td>
                    <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Estatus</div></td>
                    <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Usuario</div></td>
                    <td class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/cerrar_r.jpg',1)"></a></td>
                    </tr>
                    
                    <?
                    $cot = new Cotizacion();
                    $cot->get($_SESSION['cotizacion']->id , $_SESSION['cotizacion']->id_version);
                    $cot->getChildren();
	?>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          
        </table>
</form>
        
<form action="generar_cotizacion_version.php" method="post" name="formEditarCotizacion" id="formEditarCotizacion" style="width:0px">
	<input id="idCotizacionEditar" name="idCotizacionEditar" value="" type="hidden"/>
	<input id="idVersion" name="idVersion" value="" type="hidden"/>
	<input id="esVersionActual" name="esVersionActual" value="" type="hidden"/>
</form>
</body>
</html>