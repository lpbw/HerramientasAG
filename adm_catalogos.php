<?
//include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);
function compareArray($a, $b) {if ($a == $b) return 0; return ($a < $b) ? -1 : 1; }
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
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style5 {font-size: 18}
.style6 {color: #FFFFFF}
-->
</style>
<script language="text/javascript">
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
</head>

<body onload="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<form id="form1" name="form1" method="post" action="myadmin.php">
        <table width="510px" border="0" align="center">
            <tr>
              <td width="29%"><table width="146" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                      <td class="texto_menu_slice"><a  href="javascript:parent.abrir('cambia_catalogo.php?<? echo "id_proveedor=".$_GET['id'];?>')"  class="titulo_tabla">NUEVA LINEA</a></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
              <td width="35%" class="style6">&nbsp;</td>
              <!--- PARA REGRESARLO A QUE ELIJA DE TODOS LOS CATALOGS ES LA LIGA adm_agregar_catalogo.php? < ? echo "id_proveedor=".$_GET['id'];?>-->
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
        $consulta2  = "SELECT id, nombre FROM CatalogoProductos
		WHERE id_proveedor = ".$_GET['id'];
        $resultado2 = mysql_query($consulta2) or print("La consulta lista: " . mysql_error());
        if(@mysql_num_rows($resultado2)>=1){
            
			$color = '#FFFFFF';
            while($res=mysql_fetch_assoc($resultado2)){
		?>
                    <tr>
                      <td style="background-color:<? echo $color;?>"><div align="center" class="texto_info_negro"><a href="#" onclick="parent.abrir('cambia_catalogo.php?<? echo "id=".$res['id']."&id_proveedor=".$_GET['id'];?>')" class="texto_info_negro">
                          <? echo $res['nombre'];?></a></div></td>
                      <td align="center" style="background-color:<? echo $color;?>">
                        <span class="texto_info_negro">
                        <?
					  
	    $consulta  = "SELECT Tipo_importacion.nombre_tipo_importacion, factor, factor2, CatalogoProductos.id AS id
		FROM Tipo_importacion
		INNER JOIN CatalogoProductos ON CatalogoProductos.id_tipo_importacion = Tipo_importacion.id_tipo_importacion
		WHERE id = ".$res['id'];
		//echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            $result = mysql_fetch_array($resultado, MYSQL_BOTH);
			$factor = $result['factor'];
			$factor2 = $result['factor2'];
		}
		echo $factor; $factor="";  if($factor2!=0) echo "<br>$factor2"; $factor2=""; ?></span></td>
                      <td class="style5"><div align="center"><a   href="#" onclick="borrar('cambia_catalogo.php?<? echo "borrar=true&id=".$res['id']."&id_proveedor=".$_GET['id'];?>','<? echo $res['nombre'];?>')" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image<? echo $res['id'];?>','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="" name="Image<? echo $res['id'];?>" width="17" height="16" border="0" id="Image22" /></a></div></td>
                    </tr>
                <?
				if($color =='#FFFFFF')
					$color = '#E3E3E3';
					else $color = '#FFFFFF';
            }
        }
     
		  ?>
                  </table>
              </div></td>
            </tr>
</table>
        </form>
</body>
</html>