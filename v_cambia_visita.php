<?
include_once "Usuario.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";

$idU=$_SESSION['usuario']->id;

$id_visita=$_GET['id_visita'];
$id_usuario=$_GET['id_usuario'];


$consulta="select id_cliente, Clientes.nombre_empresa, visitas.fecha, visitas.comentarios from visitas inner join Clientes on Clientes.id=visitas.id_cliente where visitas.id=$id_visita";
$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
$res=mysql_fetch_assoc($resultado);

$consulta2 = "SELECT * FROM visitas_usuarios inner join Contactos on Contactos.id=visitas_usuarios.id_usuario where id_visita=$id_visita and id_usuario=$id_usuario";
$resultado2 = mysql_query($consulta2) or print("$consulta2");
$res2=mysql_fetch_assoc($resultado2);

if($_POST['guardar']=="Guardar"){

	$consulta="update visitas set comentarios='".$_POST['comentarios']."' where id=$id_visita";
	$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
	
	$datos=$_POST['datos'];
	$info=$_POST['info'];
	$prioridad=$_POST['prioridad'];
	$estatus=$_POST['estatus'];
	$fecha_fin=$_POST['fecha_fin'];
	$comentarios_finales=$_POST['comentarios_finales'];
	
	$consulta2="update visitas_usuarios set datos='$datos', info='$info', prioridad='$prioridad', estatus='$estatus', fecha_fin='$fecha_fin', comentarios='$comentarios_finales'  where id_visita=$id_visita and id_usuario=$id_usuario";
	$resultado2= mysql_query($consulta2) or print("$consulta2" . mysql_error());

	echo"<script>window.location=\"v_menu.php\"</script>";
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Visitas</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(images/bkg_1.jpg);
	margin-top: 10px;
}
-->
</style>


  

<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<SCRIPT>
	$(function() {
		$( "#fecha_fin" ).datepicker({ dateFormat: 'yy-mm-dd' });
		
		
	});
	</SCRIPT>
<script type="text/javascript">
<!--
<!--

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

</script>
<script>

function cerrarV(){
	$.fn.colorbox.close();
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
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
</script>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
</head>

<body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_industrias_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg','images/b_cotizaciones_r.jpg','images/b_clientes_r.jpg','images/b_productos_r.jpg','images/b_salir_r.jpg')">
<form id="form1" name="form1" method="post" action="">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="14" valign="top"><img src="images/sombra_izq.png" width="14" height="805" /></td>
    <td valign="top" bgcolor="#FFFFFF"><table width="977" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="106"><a href="v_menu.php"><img src="images/logo_AG.jpg" height="56" /></a></td>
            <td width="867" style="background-image: url('images/bkg_header_1.jpg');background-size: 7px;background-repeat: repeat-x;">
            <div style="float:left; padding:5px">
              <!--<div align="center" class="texto_chico_gris" style="margin:0px 5px 0px 5px; float:left">
                <!--<input name="busca_cotizacion" type="text" id="busca_cotizacion" style="width:100px" size="20" maxlength="15" list="buscarList"/>-->
                <!--<datalist id="buscarList">
                  <?
//$query = "SELECT id FROM Cotizaciones WHERE es_version!=0 group by id";
//$result = mysql_query($query) or die();
//while($row = mysql_fetch_assoc($result) ){?>
                  <option value="<? //echo $row['id']?>"> </option>
                  <? //}?>
                </datalist>
                <input name="buscar" type="button" class="style1" id="buscar" value="Buscar" onClick="resizeIframe(document.getElementById('iframe_main'));"/>
              </div>-->
            </div>
            <div style=" padding:0px 5px; float:right; width:630px">
            <div>
              <div align="right" style="float:right; margin:5px 5px 0px 5px"> <a href="main.php" > <img src="images/control_panel.png" alt="" width="28" height="28" border="0" /></a></div>
                  <div align="center" style="float: right;margin:5px 5px 0px 5px"><a href="main.php" class="texto_info">
                          <img src="images/tarea.png" alt="" name="Image86" width="25" height="25" border="0" id="Image86" />
                          <span id="totalTareas"><? //echo count($_SESSION['usuario']->getTareas());?></span>
                  </a></div>
                </div>
            <div>
            <a href="main.php" 
               onMouseOut="MM_swapImgRestore()" 
               onMouseOver="MM_swapImage('Image1','','images/b_cotizaciones_r.jpg',1)" 
              >
            <img src="images/b_cotizaciones.jpg" alt="" name="Image1" height="50" border="0" id="Image1" /></a>
            
            <a href="main.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image21','','images/b_clientes_r.jpg',1)"
             >
             <img src="images/b_clientes.jpg" alt="" name="Image21" height="50" border="0" id="Image21" /></a>
             
             <a href="main.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image31','','images/b_productos_r.jpg',1)"
           >
             <img src="images/b_productos.jpg" alt="" name="Image31" height="50" border="0" id="Image31" /></a>
             
             <a href="logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image51','','images/b_salir_r.jpg',1)">
             <img src="images/b_salir.jpg" alt="" name="Image51" height="50"  border="0" id="Image51" /></a>
                     
                     
              <div align="right" id="atrasReporteEjecutivo" style="visibility: hidden; margin: -10px 0px 0px 0px; float:right">
              
              <a href="main.php"  class="texto_chico_gris">REGRESAR</a> </div></div>
            </div>
            
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr >
                  
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#F2F2F2">&nbsp;
          </td>
      </tr>
      <tr>
        <td bgcolor="#F2F2F2"><table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
 
  <tr>
    <td colspan="2" class="texto_chico_gris_bold" align="center">Cliente: <? echo $res['nombre_empresa']?></td>
    <td colspan="2" class="texto_chico_gris_bold" align="center">Fecha: <? echo $res['fecha']?></td>
    </tr>
	 <tr>
    <td colspan="2" class="texto_chico_gris_bold" align="center">Estatus: 
					<select name="estatus" id="estatus" class="texto_chico_gris_bold" style="width:150px">
                        <option value="">--Selecciona--</option> 
                        <option value="0" <? if($res2['estatus']==0){echo"selected";} ?>>Creada</option>
						<option value="1" <? if($res2['estatus']==1){echo"selected";} ?>>Fin</option
               		 ></select></td>
    <td colspan="2" class="texto_chico_gris_bold" align="center">Fecha Fin: <input type="text" readonly name="fecha_fin" id="fecha_fin" class="texto_chico_gris" <? echo $res2['fecha_fin']?>></td>
  </tr>
  <tr>
    <td class="texto_chico_gris_bold">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" class="texto_chico_gris_bold"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td>Nombre</td>
        <td class="texto_chico_gris_bold">Acción requerida</td>
        <td class="texto_chico_gris_bold">Información relevante </td>
        <td class="texto_chico_gris_bold" align="center">Prioridad</td>
      </tr>
      <tr>
        <td width="5%" align="center">&nbsp;</td>
        <td width="25%" class="texto_chico_gris_bold"><? echo $res2['nombre_contacto']?></td>
        <td width="25%"><textarea name="datos" cols="30" rows="3"><? echo $res2['datos']?></textarea></td>
        <td width="25%"><textarea name="info" cols="30" rows="3"><? echo $res2['info']?></textarea></td>
        <td width="20%" class="texto_chico_gris_bold" align="center">Si<input type="radio" name="prioridad" value="1"  <? if($res2['prioridad']=="1"){echo"checked";}?>> No<input type="radio" name="prioridad" value="0" <? if($res2['prioridad']=="0"){echo"checked";}?>></td>
      </tr>
   
	</table></td>
    </tr>
  <tr>
    <td colspan="4" align="center" class="texto_chico_gris_bold">Comentarios finales: <br/>      <textarea name="comentarios_finales" cols="45" rows="4"><? echo $res2['comentarios']?></textarea></td>
    </tr>
  <tr>
    <td class="texto_chico_gris_bold">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" class="texto_chico_gris_bold">&ensp;Datos generales: </td>
  </tr>
  <tr>
    <td colspan="4" class="texto_chico_gris_bold">&ensp;<textarea name="comentarios" cols="90" rows="4"><? echo $res['comentarios']?></textarea></td>
    </tr>
  <tr>
    <td class="texto_chico_gris_bold">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" class="texto_chico_gris_bold" align="center"><input type="submit" name="guardar" id="guardar" value="Guardar" class="texto_info"></td>
    </tr>
  <tr>
    <td width="33%" class="texto_chico_gris_bold">&nbsp;</td>
    <td width="34%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
    <td width="33%">&nbsp;</td>
  </tr>
</table>
</td>
      </tr>
    </table></td>
    <td width="14" valign="top"><img src="images/sombra_der.png" width="14" height="805" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/sombra_abajo.png" width="951" height="18" /></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/spacer.gif" width="10" height="10" /></td>
  </tr>
  <tr>
    <td colspan="3"><table width="930" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="159" class="texto_copy">© Copyright 2013</td>
        <td width="618"><table width="530" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="44" class="texto_copy"><div align="center">INICIO</div></td>
            <td width="19" class="texto_copy"><div align="center"><img src="images/separador_menu_abajo.png" width="19" height="15" /></div></td>
            <td width="140" class="texto_copy"><div align="center">NUESTRA EMPRESA</div></td>
            <td width="19" class="texto_copy"><div align="center"><img src="images/separador_menu_abajo.png" width="19" height="15" /></div></td>
            <td width="93" class="texto_copy"><div align="center">PRODUCTOS</div></td>
            <td width="19" class="texto_copy"><div align="center"><img src="images/separador_menu_abajo.png" width="19" height="15" /></div></td>
            <td width="90" class="texto_copy"><div align="center">INDUSTRIAS</div></td>
            <td width="19" class="texto_copy"><div align="center"><img src="images/separador_menu_abajo.png" width="19" height="15" /></div></td>
            <td width="95" class="texto_copy"><div align="center">CONTACTO</div></td>
          </tr>
        </table></td>
        <td width="159"><a href="http://www.lacocinaestudio.com" target="_blank"><img src="images/logo_la_cocina.png" width="159" height="27" border="0" /></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/spacer.gif" width="10" height="10" /></td>
  </tr>
</table>
</form>
</body>
</html>
