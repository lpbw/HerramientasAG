<?
error_reporting(0);
include_once "Usuario.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";

$idU=$_SESSION['usuario']->id;

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
  dateFormat: 'dd-mm-yyyy' 
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
function habilitar(value)
		{
			if(value=="")
			{
				// habilitamos
				document.getElementById("cliente").disabled=false;
				document.getElementById("usuario").disabled=false;
			}else if(value>"0" ){
				// deshabilitamos
				document.getElementById("cliente").value="";
				document.getElementById("usuario").value="";
				
				document.getElementById("cliente").disabled=true;
				document.getElementById("usuario").disabled=true;
				
				
			}
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
        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
	     <td colspan="4" >&nbsp;</td>
	     </tr>
	   <tr>
	     <td colspan="4" >&nbsp;</td>
	     </tr>
		 <script>

function cambiar1()
{
var index=document.forms.form1.cliente.selectedIndex;
form1.usuario.length=0;

if(index==0){ objetivo0();}

<? 
		$query23 = "SELECT * FROM Clientes order by nombre_empresa";
        $result23 = mysql_query($query23);
		$count23=1;
        while($res23 = mysql_fetch_assoc($result23)){ 
         ?>  
if(index==<? echo $count23?>){ objetivo<? echo $count23?>();}
	<? $count23++; }?>
}
function objetivo0(){
opcion0=new Option("--Selecciona--","","defauldSelected");
document.forms.form1.usuario.options[0]=opcion0;
}

<? 
		$query24 = "SELECT * FROM Clientes order by nombre_empresa";
        $result24 = mysql_query($query24);
		$count24=1;
        while($res24 = mysql_fetch_assoc($result24)){ 
         ?> 
function objetivo<? echo $count24?>(){
opcion0=new Option("--Selecciona--","","defauldSelected");
document.forms.form1.usuario.options[0]=opcion0;

		<? 
		$query = "SELECT * FROM Contactos where id_cliente={$res24['id']} order by activo desc,nombre_contacto";
        $result = mysql_query($query) or print("<option value=\"ERROR\">".mysql_error()."</option>");
		$count=1;
        while($lags = mysql_fetch_assoc($result)){ 
         ?>       
opcion1=new Option("<? if($lags['activo']==0){echo"***";}?><? echo $lags['nombre_contacto']?>","<? echo $lags['id']?>", "", "<? echo $_POST['usuario']==$lags['id']?"selected":""; ?>");
document.forms.form1.usuario.options[<? echo $count?>]=opcion1;

<?
$count++;
}
?>
}
<? $count24++; }?>

</script>
	     <tr>
	       <td >&nbsp;</td>
	       <td colspan="2" class="texto_chico_gris_bold" align="center" >Cartera: <select name="cartera" class="texto_chico_gris" id="cartera"  onchange="habilitar(this.value)"  style="width:200px" >
                                <option value="">--Selecciona--</option>
                                <? $query2 = "SELECT CarteraClientes.id, CarteraClientes.nombre FROM CarteraClientes inner join CarteraClientes_Usuarios on CarteraClientes_Usuarios.id_cartera_clientes=CarteraClientes.id where id_usuario=$idU order by nombre";
                            $result2 = mysql_query($query2) or print("<option value=\"ERROR\">".mysql_error()."</option>");
                            while($carteras = mysql_fetch_assoc($result2)){
							?>
                          <option value="<? echo $carteras['id']?>" <? echo $_POST['cartera']==$carteras['id']?"selected":""; ?>><? echo $carteras['nombre']?></option>
                          <?
                            }
                            ?>
                </select></td>
	       <td >&nbsp;</td>
          </tr>
	     <tr>
	     <td width="103" >&nbsp;</td>
	     <td width="313" class="texto_chico_gris_bold" >Cliente: 
	       <select name="cliente" class="texto_chico_gris" id="cliente"   style="width:200px" onchange="cambiar1(this.value);">
                                <option value="">--Selecciona--</option>
                                <? $query2 = "SELECT * FROM Clientes order by nombre_empresa";
                            $result2 = mysql_query($query2) or print("<option value=\"ERROR\">".mysql_error()."</option>");
                            while($clientes2 = mysql_fetch_assoc($result2)){
							?>
                          <option value="<? echo $clientes2['id']?>" <? echo $_POST['cliente']==$clientes2['id']?"selected":""; ?>><? echo $clientes2['alias']?></option>
                          <?
                            }
                            ?>
            </select></td>
	     <td width="313" class="texto_chico_gris_bold" >Contacto: 
      <select name="usuario" id="usuario" class="texto_chico_gris" style="width:200px">
                                <option value="">--Selecciona--</option>
                                <? //$query3 = "SELECT * FROM Contactos order by nombre_contacto";
                            //$query3  = mysql_query($query3 ) or print("<option value=\"ERROR\">".mysql_error()."</option>");
                           // while($usuario = mysql_fetch_assoc($query3)){
							?>
                         <!-- <option value="<? //echo $usuario['id']?>" <? //echo $_POST['usuario']==$usuario['id']?"selected":""; ?>><? //echo $usuario['nombre_contacto']?></option>-->
                          <?
                            //}
                            ?>
            </select></td>
	     <td width="248" ><input type="submit" name="buscar" id="buscar" value="Buscar" class="texto_info"></td>
	   </tr>
		
	   <tr>
	     <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">&nbsp;</td>
    </tr>
</table></td>
	     </tr>
	   <tr>
	     <td colspan="4" >&nbsp;</td>
	     </tr>
	   <tr>
        <td colspan="4" ><table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bgcolor="#F2F2F2">
		<tr>
    <td>
		<table width="100%" border="0" cellspacing="2" cellpadding="1" align="center" id="mi-tabla" class="tabla">
  <thead>
  <tr>
   <th width="14%" class="texto_chico_gris_bold" align="center"># Cotizacion</th>
    <th width="14%" class="texto_chico_gris_bold" align="center">Monto MXN</th>
    <th width="15%" class="texto_chico_gris_bold" align="center">Cliente</th>
    <th width="15%" class="texto_chico_gris_bold" align="center">Contacto</th>
    <th width="14%" class="texto_chico_gris_bold" align="center">Fecha</th>
	<th width="14%" class="texto_chico_gris_bold" align="center">Prioridad</th>
	<th width="14%" class="texto_chico_gris_bold" align="center">Ultima Visita</th>
  </tr>
  </thead>
  <tbody>
  <?
  if($_POST['buscar']=="Buscar"){
  	if($_POST['cartera']!=""){
		 $consulta="select Cotizaciones.id, round(CASE tipo_moneda WHEN '1' THEN total*valor_moneda ELSE total END, 2) as monto, Clientes.alias as cliente, DATE_FORMAT(fecha_creacion , '%d-%m-%Y') as fecha, DATE_FORMAT(fecha_creacion , '%Y%m%d') as fecha2, prioridad, Clientes.id as id_cliente, Cotizaciones.id_contacto from Cotizaciones 
  inner join Clientes on Clientes.id=Cotizaciones.id_cliente 
where (id_estatus=1 or id_estatus=2 or id_estatus=3) and Clientes.id_cartera=".$_POST['cartera']." and es_version=0 group by id";
 	 $resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
	}else{
  	if($_POST['cliente']!="" && $_POST['usuario']!="")	{
  $consulta="select Cotizaciones.id, round(CASE tipo_moneda WHEN '1' THEN total*valor_moneda ELSE total END, 2) as monto, Clientes.alias as cliente, Contactos.nombre_contacto as usuario, DATE_FORMAT(fecha_creacion , '%d-%m-%Y') as fecha, DATE_FORMAT(fecha_creacion , '%Y%m%d') as fecha2, prioridad, Clientes.id as id_cliente from Cotizaciones 
  inner join Clientes on Clientes.id=Cotizaciones.id_cliente 
   inner join Contactos on Contactos.id=Cotizaciones.id_contacto 
where (id_estatus=1 or id_estatus=2 or id_estatus=3) and Cotizaciones.id_cliente=".$_POST['cliente']." and Cotizaciones.id_contacto=".$_POST['usuario']." and es_version=0 group by id";
  $resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
  
  	}else{
		if($_POST['cliente']!=""){
			
		$consulta="select Cotizaciones.id, round(CASE tipo_moneda WHEN '1' THEN total*valor_moneda ELSE total END, 2) as monto, Clientes.alias as cliente, DATE_FORMAT(fecha_creacion , '%d-%m-%Y') as fecha, DATE_FORMAT(fecha_creacion , '%Y%m%d') as fecha2, prioridad, Clientes.id as id_cliente, Cotizaciones.id_contacto from Cotizaciones 
  inner join Clientes on Clientes.id=Cotizaciones.id_cliente  
where (id_estatus=1 or id_estatus=2 or id_estatus=3) and Cotizaciones.id_cliente=".$_POST['cliente']." and es_version=0 group by id";
  $resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
		}
	}
  }
  }
  $color="FFFFFF";
 while($res=mysql_fetch_assoc($resultado)){
 
 $consulta2="select DATE_FORMAT(max(fecha), '%d-%m-%Y') as ultima from visitas where id_cliente={$res['id_cliente']}";
 $resultado2= mysql_query($consulta2) or print("$consulta2" . mysql_error());
 $res2=mysql_fetch_assoc($resultado2);

	if($_POST['usuario']==""){
 		$consulta3="select * from Contactos where id={$res['id_contacto']}";
 		$resultado3= mysql_query($consulta3) or print("$consulta3" . mysql_error());
 		$res3=mysql_fetch_assoc($resultado3);
  	}
  ?>
  <tr bgcolor="#<? echo $color?>">
    <td class="texto_chico_gris" align="center"><? echo $res['id']?></td>
    <td class="texto_chico_gris" align="center"><span style='display: none;'><? echo $res['monto']?></span><? echo number_format($res['monto'])?></td>
	<td class="texto_chico_gris" align="center"><? echo $res['cliente']?></td>
	<td class="texto_chico_gris" align="center"><? echo $res3['nombre_contacto']?><? echo $res['usuario']?></td>
	<td class="texto_chico_gris" align="center"><span style='display: none;'><? echo $res['fecha2']?></span><? echo $res['fecha']?></td>
	<td class="texto_chico_gris" align="center"><? echo $res['prioridad']?></td>
	<td class="texto_chico_gris" align="center"><? echo $res2['ultima']?></td>
  </tr>
  <? }?>
  </tbody>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table></td>
  </tr></table></td>
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
<script> 
window.onload=function(){ 
cambiar1(); 


} 
</script> 
</form>
</body>
</html>
