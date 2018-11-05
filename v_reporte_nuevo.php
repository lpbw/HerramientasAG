<?
error_reporting(0);
include_once "Usuario.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";

$idU=$_SESSION['usuario']->id;

$fecha = date('Y-m-d');
$fecha_a = strtotime ( '-1 year' , strtotime ( $fecha ) ) ;
$fecha_a = date ( 'Y-m-d' , $fecha_a );
 

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

@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,300italic,400italic);

body{
  padding-top:15px;
  font-family: 'Open Sans', sans-serif;
    font-size:13px;
}

.tabla {
  margin: 0 auto;
}
.tabla thead {
  cursor: pointer;
  background: rgba(0, 0, 0, .2);
}
.tabla thead tr th { 
  font-weight: bold;
  padding: 10px 20px;
}
.tabla thead tr th span { 
  padding-right: 20px;
  background-repeat: no-repeat;
  background-position: 100% 55%;
}
.tabla thead tr th.headerSortUp,
.tabla thead tr th.headerSortDown {
  background: rgba(0, 0, 0, .2);
}
.tabla thead tr th.headerSortUp span {
  background-image: url('http://tablesorter.com/themes/blue/asc.gif');
}
.tabla thead tr th.headerSortDown span {
  background-image: url('http://tablesorter.com/themes/blue/desc.gif');
}
.tabla tbody tr td {
  text-align: center;
  padding: 10px 20px;
}
.tabla tbody tr td.align-left {
  text-align: left;
}
</style>

<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="jquery.tablesorter.js"></script>
<script src="colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<SCRIPT>
	$(function() {
		$( "#desde" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#hasta" ).datepicker({ dateFormat: 'yy-mm-dd' });
		
		
	});
	$(function(){
  $('#mi-tabla').tablesorter(); 
});
	</SCRIPT>
<script type="text/javascript">
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
              <tr >                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
     
	   <tr>
	     <td >&nbsp;</td>
	     </tr>
	   <tr>
	     <td >&nbsp;</td>
	     </tr>
	   <tr>
	     <td >&nbsp;</td>
	     </tr>
		
	   <tr>
	     <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="7%">&nbsp;</td>
    <td width="29%" class="texto_chico_gris_bold">Cartera: <select name="id_cartera" class="texto_chico_gris" id="id_cartera"  style="width:200px" >
                                <option value="">--Selecciona--</option>
                                <? $query2 = "SELECT CarteraClientes.id, CarteraClientes.nombre FROM CarteraClientes inner join CarteraClientes_Usuarios on CarteraClientes_Usuarios.id_cartera_clientes=CarteraClientes.id where id_usuario=$idU order by nombre";
                            $result2 = mysql_query($query2) or print("<option value=\"ERROR\">".mysql_error()."</option>");
                            while($carteras = mysql_fetch_assoc($result2)){
							?>
                          <option value="<? echo $carteras['id']?>" <? echo $_POST['id_cartera']==$carteras['id']?"selected":""; ?>><? echo $carteras['nombre']?></option>
                          <?
                            }
                            ?>
                </select></td>
    <td width="24%" class="texto_chico_gris_bold">Desde: <input type="text" readonly name="desde" id="desde" class="texto_chico_gris" value="<? echo $_POST['desde'];?>"></td>
	<td width="24%" class="texto_chico_gris_bold">Hasta: <input type="text" readonly name="hasta" id="hasta" class="texto_chico_gris" value="<? echo $_POST['hasta'];?>"></td>
	<td width="9%"><input type="submit" name="buscar" id="buscar" value="Buscar" class="texto_info"></td>
	<td width="7%">&nbsp;</td>
  </tr>
</table></td>
	     </tr>
	   <tr>
	     <td >&nbsp;</td>
	     </tr>
	   <tr>
        <td ><table width="98%" border="1" cellspacing="0" cellpadding="0" align="center" bgcolor="#F2F2F2">
		<tr>
    <td>
		<table width="100%" border="0" cellspacing="2" cellpadding="1" align="center">
  <thead>
  <tr>
  	<td width="9%" rowspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9">Alias Cliente</td>
	<td width="7%" rowspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9">Tipo Cliente</td>
   	<td colspan="3" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9">Visitas</td>
   	<td width="7%" rowspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9"># Usuarios Diferentes</td>
	<td colspan="3" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9">Departamentos Visitados</td>
    <td colspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9">Cotizaciones del periodo</td>
	<td width="7%" rowspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9"># Cotizaciones ganadas o perdidas</td>
	<td width="7%" rowspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9"># cot vivas</td>
	<td width="7%" rowspan="2" align="center" class="texto_chico_gris_bold" bgcolor="#E9E9E9">$ cot vivas</td>
  </tr>
  <tr>
  	<td width="7%" align="center" bgcolor="#E9E9E9" class="texto_chico_gris_bold">Objetivo por Mes </td>
   	<td width="7%" align="center" bgcolor="#E9E9E9" class="texto_chico_gris_bold"># Visitas</td>
	<td width="7%" align="center" bgcolor="#E9E9E9" class="texto_chico_gris_bold">% cump</td>
	<td width="7%" align="center" bgcolor="#E9E9E9" class="texto_chico_gris_bold">% mtmto</td>
   	<td width="7%" align="center" bgcolor="#E9E9E9" class="texto_chico_gris_bold">% Compras</td>
	<td width="7%" align="center" bgcolor="#E9E9E9" class="texto_chico_gris_bold">% Otros</td>
  	<td width="7%" align="center" class="texto_chico_gris" bgcolor="#E9E9E9">#</td>
    <td width="7%" align="center" class="texto_chico_gris" bgcolor="#E9E9E9">$</td>
    </tr>
	</thead>
	 <tbody>
  <?
  if($_POST['buscar']=="Buscar"){
  
  $color="FFFFFF";
  
  $consulta="select * from Clientes where id_cartera=".$_POST['id_cartera']." order by alias";
  $resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
  $tot_clientes=mysql_num_rows($resultado);
  while($res=mysql_fetch_assoc($resultado)){
  
  
  $consulta2="select count(*) as vistas_n from visitas_usuarios where id_cliente={$res['id']} and fecha>='".$_POST['desde']."' and fecha<='".$_POST['hasta']."'";
  $resultado2= mysql_query($consulta2) or print("$consulta2" . mysql_error());
  $res2=mysql_fetch_assoc($resultado2);
  
  $consulta3="select * from visitas_usuarios where id_cliente={$res['id']} and fecha>='".$_POST['desde']."' and fecha<='".$_POST['hasta']."' group by id_usuario";
  $resultado3= mysql_query($consulta3) or print("$consulta3" . mysql_error());
  $usudif=mysql_num_rows($resultado3);
  
  
  //departamentos visitados
  	$consulta4="SELECT Contactos.departamento_empresa FROM `visitas_usuarios` 
				inner join Contactos on Contactos.id=visitas_usuarios.id_usuario
				where visitas_usuarios.id_cliente={$res['id']} and fecha>='".$_POST['desde']."' and fecha<='".$_POST['hasta']."'";
 	$resultado4= mysql_query($consulta4) or print("$consulta4" . mysql_error());
	$tot=mysql_num_rows($resultado4);
	
	$consulta5="SELECT Contactos.departamento_empresa FROM `visitas_usuarios` 
				inner join Contactos on Contactos.id=visitas_usuarios.id_usuario
				where visitas_usuarios.id_cliente={$res['id']} and fecha>='".$_POST['desde']."' and fecha<='".$_POST['hasta']."' and departamento_empresa like '%mante%'";
 	$resultado5= mysql_query($consulta5) or print("$consulta5" . mysql_error());
  	$mante=mysql_num_rows($resultado5);
	
	$consulta6="SELECT Contactos.departamento_empresa FROM `visitas_usuarios` 
				inner join Contactos on Contactos.id=visitas_usuarios.id_usuario
				where visitas_usuarios.id_cliente={$res['id']} and fecha>='".$_POST['desde']."' and fecha<='".$_POST['hasta']."' and departamento_empresa like '%compra%'";
 	$resultado6= mysql_query($consulta6) or print("$consulta6" . mysql_error());
  	$comp=mysql_num_rows($resultado6);
	
	$consulta7="SELECT Contactos.departamento_empresa FROM `visitas_usuarios` 
				inner join Contactos on Contactos.id=visitas_usuarios.id_usuario
				where visitas_usuarios.id_cliente={$res['id']} and fecha>='".$_POST['desde']."' and fecha<='".$_POST['hasta']."' and departamento_empresa not like '%compra%' and departamento_empresa not like '%mante%'";
 	$resultado7= mysql_query($consulta7) or print("$consulta7" . mysql_error());
  	$otro=mysql_num_rows($resultado7);
	
	$mantenimiento=($mante/$tot)*100;
	$compras=($comp/$tot)*100;
	$otros=($otro/$tot)*100;
  //fin departamentos visitados
  
    $consulta10="select count(*) as cotizaciones_vivasn, round(sum(CASE tipo_moneda WHEN '1' THEN total*valor_moneda ELSE total END)) as cotizaciones_vivasp from Cotizaciones
where id_cliente={$res['id']} and Cotizaciones.fecha_creacion>='".$_POST['desde']." 00:00:01' and Cotizaciones.fecha_creacion<='".$_POST['hasta']." 23:59:59' and (Cotizaciones.id_estatus=1 || Cotizaciones.id_estatus=2 || Cotizaciones.id_estatus=3)";
    $resultado10= mysql_query($consulta10) or print("$consulta10" . mysql_error());
    $res10=mysql_fetch_assoc($resultado10);
   
    $consulta11="select count(*) as cotizaciones_gp, round(sum(CASE tipo_moneda WHEN '1' THEN total*valor_moneda ELSE total END)) as cotizaciones_gn from Cotizaciones  
where id_cliente={$res['id']} and Cotizaciones.fecha_creacion>='".$_POST['desde']." 00:00:01' and Cotizaciones.fecha_creacion<='".$_POST['hasta']." 23:59:59' and (Cotizaciones.id_estatus=4 || Cotizaciones.id_estatus=5 || Cotizaciones.id_estatus=6 || Cotizaciones.id_estatus=7 || Cotizaciones.id_estatus=8)";
  	$resultado11= mysql_query($consulta11) or print("$consulta11" . mysql_error());
    $res11=mysql_fetch_assoc($resultado11);
	
	$consulta12="select count(*) as cotizaciones_n, round(sum(CASE tipo_moneda WHEN '1' THEN total*valor_moneda ELSE total END)) as cotizaciones_p from Cotizaciones 
where id_cliente={$res['id']} and Cotizaciones.fecha_creacion>='".$_POST['desde']." 00:00:01' and Cotizaciones.fecha_creacion<='".$_POST['hasta']." 23:59:59'";
  	$resultado12= mysql_query($consulta12) or print("$consulta12" . mysql_error());
	$res12=mysql_fetch_assoc($resultado12);
  ?>
  <tr bgcolor="#<? echo $color?>">
  	<td class="texto_chico_gris" align="center"><a href="cambia_cliente.php?id=<? echo $res['id']?>" class="texto_chico_gris iframe"><? echo $res['alias']?></a></td>
	<td class="texto_chico_gris" align="center"><? echo $res['tipo_cliente']?></td>
	<td class="texto_chico_gris" align="center"><? echo $res['objetivo']?></td>
    <td class="texto_chico_gris" align="center"><? echo $res2['vistas_n']?></td>
	<td class="texto_chico_gris" align="center">%<? echo round(($res2['vistas_n']/$res['objetivo'])*100); ?></td>
	<td class="texto_chico_gris" align="center"><? echo $usudif?></td>
	<td class="texto_chico_gris" align="center">%<? echo round($mantenimiento)?></td>
	<td class="texto_chico_gris" align="center">%<? echo round($compras)?></td>
	<td class="texto_chico_gris" align="center">%<? echo round($otros)?></td>
    <td align="center" class="texto_chico_gris"><? echo $res12['cotizaciones_n']?></td>
	<td align="center" class="texto_chico_gris"><? echo number_format($res12['cotizaciones_p'])?> MXN</td>
    <td class="texto_chico_gris" align="center"><? echo $res11['cotizaciones_gp']?></td>
	<td class="texto_chico_gris" align="center"><? echo $res10['cotizaciones_vivasn']?></td>
	<td class="texto_chico_gris" align="center"><? echo number_format($res10['cotizaciones_vivasp'])?> MXN</td>
  </tr>
  <? 
  $tot_cum=$tot_cum+ (($res2['vistas_n']/$res['objetivo'])*100);
  $tot_dif=$tot_dif+$usudif;
  $tot_mante=$tot_mante+$mantenimiento;
  $tot_comp=$tot_comp+$compras;
  $tot_otro=$tot_otro+$otros;
  } } ?>
  </tbody>
  <tr height="30" bgcolor="#E9E9E9">
    <td align="center" class="texto_chico_gris_bold" >TOTALES</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="texto_chico_gris" align="center">%<? echo round($tot_cum/$tot_clientes)?></td>
    <td class="texto_chico_gris" align="center"><? echo $tot_dif?></td>
    <td class="texto_chico_gris" align="center">%<? echo round($tot_mante/$tot_clientes)?></td>
	<td class="texto_chico_gris" align="center">%<? echo round($tot_comp/$tot_clientes)?></td>
	<td class="texto_chico_gris" align="center">%<? echo round($tot_otro/$tot_clientes)?></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
</table></td>
  </tr></table></td>
      </tr>
	  
		 <tr>
		   <td >&nbsp;</td>
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
