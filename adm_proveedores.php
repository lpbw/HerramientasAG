<?
include "Usuario.php";
include "Producto.php";
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['administrador']);


include "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador']);

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
iframe {
	height: 100%;
	width: 100%;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	border-bottom-color: #CCC;
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
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script src="colorbox/jquery.colorbox-min.js"></script>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				
				//$(".iframe").colorbox({iframe:true,width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
				$(".iframe").colorbox({iframe:true,width:"600", height:"400",transition:"fade", scrolling:false, opacity:0.5});
				
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
function borrar(url,nombre)
{
  if(confirm("Î’ÎEsta seguro de borrar a "+nombre+"?")){
	  abrir(url);
  }
}
function abrir(ir)
{
$.colorbox({iframe:true,href:""+ir+"",width:"600", height:"353",transition:"fade", scrolling:false, opacity:0.5});
}

  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
<style type="text/css">
<!--
.style5 {font-size: 18}
.style6 {color: #FFFFFF}
-->
</style>
</head>

<body onload="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<form id="form1" name="form1" method="post" action="myadmin.php">
  <table width="680" border="0" align="center">
            <tr>
              <td width="28%"><table width="146" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                      <td class="texto_menu_slice"><a href="cambia_proveedor.php" class="titulo_tabla iframe" style="color: white;">NUEVA MARCA</a></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              <td width="45%" class="style6">&nbsp;</td>
              <td width="27%" align="right" class="style9">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3"><div align="center">
                  <table width="100%" border="0" cellpadding="0">
                    <tr>
                      <!--<td width="18%" bgcolor="#999999" class="style6" scope="row"><div align="center">Marca</div></td>
                      <td width="76%" bgcolor="#999999" class="style8" scope="row"><div align="center" class="style5 style6">Cat&aacute;logos</div></td>-->
                      <!--<td width="6%" bgcolor="#999999" class="style8"><div align="center">&nbsp;</div></td>-->
                    </tr>
                    <?	  

        $consulta  = "SELECT id,nombre FROM Proveedores WHERE id != 8";
	
	if($_SESSION['usuario']->id_rol>1){
            $consulta .=" INNER JOIN Proveedores_Supervisor AS PS ON PS.id_proveedor = Proveedores.id
            WHERE PS.id_supervisor = '".$_SESSION['usuario']->id."'";
        }
        
	$resultado = mysql_query($consulta) or print("La consulta lista fallo : " . mysql_error());
	$count=1;
	while(@mysql_num_rows($resultado)>=$count)
	{
		$res=mysql_fetch_assoc($resultado);
		
		?>
                    <tr style="border-bottom:1pt solid black;">
                      <td class="style5">&nbsp;</td>
                      <td class="style5">&nbsp;</td>
                    </tr>
                    <tr style="border-bottom:1pt solid black;">
                      <td width="150" valign="middle" bgcolor="#E3E3E3" class="style5"><div align="center" style="
margin-left: 15px;
margin-right: 15px;">
                      <div style="margin-right: 15px;
float: left;">
                          <a href="#" onclick="borrar('cambia_proveedor.php?<? echo "borrar=true&id=".$res['id'];?>','<? echo $res['nombre'];?>')" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image<? echo $res['id'];?>','','images/cerrar_r.jpg',1)">
                          <img src="images/cerrar.jpg" alt="" name="Image22" width="17" height="16" border="0" id="Image<? echo $res['id'];?>" />
                          </a>
                      </div>
                      <a href="cambia_proveedor.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"  style="float:left;width: 88px;"><? echo $res['nombre'];?></a></div>
                      
                      </td>
                      <td class="style5"><div align="left">
                          <!--<div align="right"><a href="adm_catalogos.php?id=<? echo $res['id'];?>" class="texto_mas_eventos iframe">Ver Cat&aacute;logos</a></div>-->
                          <table width="510px" border="0" align="center">
                            <tr>
                              <td width="29%"><table width="146" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                                      <td class="texto_menu_slice"><a  href="javascript:abrir('cambia_catalogo.php?<? echo "id_proveedor=".$res['id'];?>')"  class="titulo_tabla">NUEVA LINEA</a></td>
                                    </tr>
                                  </table></td>
                                </tr>
                              </table></td>
                              <td width="35%" class="style6">&nbsp;</td>
                              <!--- PARA REGRESARLO A QUE ELIJA DE TODOS LOS CATALOGS ES LA LIGA adm_agregar_catalogo.php? < ? echo "id_proveedor=".$res['id'];?>-->
                              <td width="36%" height="5" align="center" class="style9">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="3"><div align="center" style="border: solid 1px #808080;">
                                <table width="100%" border="0" cellpadding="3" bordercolor="#000000">
                                  <tr class="texto_mas_eventos">
                                    <td width="80%" bgcolor="#E3E3E3" style="background-image: url(images/bkg_1.jpg);"><div align="center" class="titulo_tabla">L&iacute;nea de descuento</div></td>
                                    <td width="15%" align="center" bgcolor="#E3E3E3" class="titulo_tabla" style="background-image: url(images/bkg_1.jpg);">Factor</td>
                                    <td width="5%"  class="" scope="row"><div align="center" class="style5 style6"></div></td>
                                  </tr>
                                  <?
        $Cconsulta2  = "SELECT id, nombre FROM CatalogoProductos
		WHERE id_proveedor = ".$res['id'];
        $Cresultado2 = mysql_query($Cconsulta2) or print("La consulta lista: " . mysql_error());
        if(@mysql_num_rows($Cresultado2)>=1){
            
			$Ccolor = '#FFFFFF';
            while($Cres=mysql_fetch_assoc($Cresultado2)){
		?>
                                  <tr>
                                    <td style="background-color:<? echo $Ccolor;?>"><div align="center" class="texto_info_negro"><a href="#" onclick="abrir('cambia_catalogo.php?<? echo "id=".$Cres['id']."&id_proveedor=".$res['id'];?>')" class="texto_info_negro"> <? echo $Cres['nombre'];?></a></div></td>
                                    <td align="center" style="background-color:<? echo $Ccolor;?>"><span class="texto_info_negro">
                                      <?
					  
	    $Cconsulta  = "SELECT Tipo_importacion.nombre_tipo_importacion, factor, factor2, CatalogoProductos.id AS id
		FROM Tipo_importacion
		INNER JOIN CatalogoProductos ON CatalogoProductos.id_tipo_importacion = Tipo_importacion.id_tipo_importacion
		WHERE id = ".$Cres['id'];
		//echo $Cconsulta;
        $Cresultado = mysql_query($Cconsulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($Cresultado)>=1){
            $Cresult = mysql_fetch_array($Cresultado, MYSQL_BOTH);
			$Cfactor = $Cresult['factor'];
			$Cfactor2 = $Cresult['factor2'];
		}
		echo $Cfactor; $Cfactor="";  if($Cfactor2!=0) echo "<br>$Cfactor2"; $Cfactor2=""; ?>
                                    </span></td>
                                    <td class="style5"><div align="center"><a   href="#" onclick="borrar('cambia_catalogo.php?<? echo "borrar=true&id=".$Cres['id']."&id_proveedor=".$res['id'];?>','<? echo $Cres['nombre'];?>')" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image1','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="" name="Image1" width="17" height="16" border="0" id="Image1" /></a></div></td>
                                  </tr>
                                  <?
				if($Ccolor =='#FFFFFF')
					$Ccolor = '#E3E3E3';
					else $Ccolor = '#FFFFFF';
            }
        }
     
		  ?>
                                </table>
                              </div></td>
                            </tr>
                          </table>
                      </div></td>
                      <!--<td class="style5"><div align="center"><a href="javascript:borrar(<? echo $res[''];?>);"><img src="images/close.gif" alt="close" width="15" height="13" border="0" /></a></div></td>-->
                    </tr>
                    <?
			   $count=$count+1;
	}
	
?>
                  </table>
              </div></td>
            </tr>
  </table>
          <p>&nbsp;</p>
</form>
</body>
</html>
