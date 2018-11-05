<?
include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);

if(isset($_REQUEST['idCotizacion'])){
    $link = "ws_AsignaUsuarioACotizacion.php";//este debe recibirá el id del usuario como parámetro.
} else $link = "cambia_usuario.php";

if($_SESSION['usuario']->id_rol == 2){
    $where = "
    Usuarios.id_supervisor = ".$_SESSION['usuario']->id;
} else $where = 1;

$consulta  = "SELECT Usuarios.nombre, email, Usuarios.id, Roles.nombre AS rol_nombre
    FROM Usuarios
    LEFT OUTER JOIN Roles ON Roles.id = Usuarios.id_rol 
	WHERE Usuarios.id!=1 AND $where
    ORDER BY nombre";
	
$resultado = mysql_query($consulta) or print("La consulta fallo lista usuarios: $consulta <bR> " . mysql_error());

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
/*	background-image: url(images/bkg_1.jpg);*/
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
				
				$(".iframe").colorbox({iframe:true,width:"600", height:"553",transition:"fade", scrolling:true, opacity:0.5});
				$(".iframe2").colorbox({iframe:true,width:"650", height:"503",transition:"fade", scrolling:true, opacity:0.5});
				
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

function borrar(nombre,variables){
    if(confirm("Esta seguro de borrar a "+nombre+"?")){
        abrir('cambia_usuario.php?' + variables);
    }
}
function abrir(ir)
{
$.colorbox({iframe:true,href:""+ir+"",width:"600", height:"353",transition:"fade", scrolling:false, opacity:0.5});
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
<form id="form1" name="form1" method="post" action="myadmin.php">
  <table width="680" border="0" align="center">
<tr>
            <td colspan="3"><div align="center">
              <table width="100%" border="0" cellpadding="0">
                <tr></tr>
              </table>
              <table width="680" border="0" align="center">
                <tr>
                  <td width="29%">&nbsp;</td>
                  <td width="35%" class="style6">&nbsp;</td>
                  <td width="36%" align="right" class="style9"><table width="146" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                          <td class="texto_menu_slice"><a href="cambia_usuario.php?new=true" class="texto_menu_slice iframe">AGREGAR USUARIO</a></td>
                        </tr>
                      </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="3"><div align="center">
                    <table width="100%" border="0" cellpadding="0">
                      <tr class="texto_info_blanco" style="background-image: url(images/bkg_1.jpg);">
                        <td width="23%" class="style6" scope="row"><div align="center">TIPO</div></td>
                        <td width="64%" class="style8" scope="row"><div align="center" class="style5 style6">USUARIO</div></td>
                        <td width="13%" class="style8"><div align="center">&nbsp;</div></td>
                      </tr>
                      <?	  

	$count=1;
	while(@mysql_num_rows($resultado)>=$count)
	{
		$res=mysql_fetch_assoc($resultado);
		
		?>
                      <tr class="texto_info_negro">
                        <td class="style5"><div align="center"><a href="<? echo $link;?>?id=<? echo $res['id'];?>" 
                                                                  class="texto_info_negro iframe"><? echo $res['rol_nombre'];?></a></div></td>
                        <td class="style5"><div align="left"> <span class="texto_info_negro"><a href="<? echo $link;?>?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['nombre'];?></a></span></div></td>
                        <td class="style5"><div align="center">
                        <a href="#" onclick="borrar('<? echo $res['nombre'];?>','id=<? echo $res['id'];?>&borrar=true');" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image<? echo $res['id'];?>','','images/cerrar_r.jpg',1)">
                            <img src="images/cerrar.jpg" alt="" name="Image22" width="17" height="16" border="0" id="Image<? echo $res['id'];?>" /></a></div></td>
                      </tr>
                      <?
			   $count=$count+1;
	}
	
?>
                    </table>
                  </div></td>
                </tr>
              </table>
              <table width="100%" border="0" cellpadding="0">
              </table>
              </div></td>
          </tr>
          </table>
          <p>&nbsp;</p>
        </form>
<form action="" name="form_borrar" target="_self" id="form_borrar"  method="post"></form>
</body>
</html>
