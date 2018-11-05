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
<? if($_GET['vista']=='agregar'){?>
.header1{
	background-color: #999999;
	color: #FFF;
}
<? } ?>
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style5 {font-size: 18}
.style6 {color: #FFFFFF}
-->
</style>
</head>

<body >
<form id="form1" name="form1" method="post" action="cambia_catalogo.php">
        <table width="100%" border="0" align="center">
            <tr>
              <td width="100%"><div align="center">
                  <table width="100%" border="0" cellpadding="3">
                    <tr class="texto_mas_eventos">
                      <td width="80%" class="header1" scope="row"><div align="center">Nombre</div></td>
                      <td width="20%" class="header1" scope="row"><div align="right"><a href="cambia_catalogo.php?<? echo "id_proveedor=".$_GET['id_proveedor'];?>" class="texto_mas_eventos">Nuevo Catalogo+</a></div></td>
                    </tr>
                    <?
        $consulta2  = "SELECT CatalogoProductos.id, CatalogoProductos.nombre FROM CatalogoProductos";
        $resultado2 = mysql_query($consulta2) or print("La consulta lista: " . mysql_error().$consulta2);
        if(@mysql_num_rows($resultado2)>=1){
            
            while($res=mysql_fetch_assoc($resultado2)){
		?>
                    <tr>
                      <td colspan="2" class="style5"><div align="center"><a href="javascript:submitt(<? echo "'".$res['id']."','".$res['nombre']."','".$_GET['id_proveedor']."'";?>)" class="texto_contenido">
                          <? echo $res['nombre'];?></a>
                          </div></td>
                    </tr>
                <?
            }
        }
     
		  ?>
                  </table>
              </div></td>
            </tr>
          </table>
        
  <input  type="hidden" name="nombre" id="nombre" value=""/>
  <input  type="hidden" name="id_proveedor" id="id_proveedor" value=""/>
  <input  type="hidden" name="id_catalogo" id="id_catalogo" value=""/>
        </form>
        <script>
		function submitt(id_catalogo,nom,prov){
			document.getElementById('nombre').value=nom;
			document.getElementById('id_proveedor').value=prov;
			document.getElementById('id_catalogo').value=id_catalogo;
			document.getElementById('form1').submit();
		}
		</script>
                       
</body>
</html>
