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
	     <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="17%">&nbsp;</td>
    <td width="28%" class="texto_chico_gris_bold">Cliente: 
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
    <td width="38%" class="texto_chico_gris_bold">Contacto: 
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
	<td width="17%"><input type="submit" name="buscar" id="buscar" value="Buscar" class="texto_info"></td>
  </tr>
</table></td>
	     </tr>
	   <tr>
	     <td align="center" class="texto_ver_mas_videos">Periodo de tiempo un año anterior a la fecha de consulta</td>
	     </tr><br />
<tr>
	     <td >&nbsp;</td>
	     </tr>
		 <tr>
	     <td ><table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bgcolor="#F2F2F2">
		<tr>
    <td>
		<table width="100%" border="0" cellspacing="2" cellpadding="1" align="center">
  <tr bgcolor="#E9E9E9">
   <td width="32%" rowspan="2" align="center" class="texto_chico_gris_bold">Cliente / Contacto</td>
    <td width="13%" rowspan="2" align="center" class="texto_chico_gris_bold">Departamento</td>
	<td colspan="2" align="center" class="texto_chico_gris_bold">Cotizaciones</td>
	<td width="11%" rowspan="2" align="center" class="texto_chico_gris_bold">% Efectividad</td>
	<td width="11%" rowspan="2" align="center" class="texto_chico_gris_bold"># Visitas / (Física / Virtual)</td>
    <td width="11%" rowspan="2" align="center" class="texto_chico_gris_bold">Ultima visita</td>
    <td width="11%" rowspan="2" align="center" class="texto_chico_gris_bold">Info relevante</td>
    <td width="11%" rowspan="2" align="center" class="texto_chico_gris_bold">Acción requerida </td>
    <td width="11%" rowspan="2" align="center" class="texto_chico_gris_bold">Info Cliente </td>
  </tr>
   <tr bgcolor="#E9E9E9">
    <td width="11%" align="center" class="texto_chico_gris">#</td>
    <td width="11%" align="center" class="texto_chico_gris">$</td>
    </tr>
  <?
  if($_POST['buscar']=="Buscar"){
 
 	if($_POST['cliente']!="" && $_POST['usuario']!=""){
	$consulta4="select Clientes.alias as cliente, CONCAT(UCASE(LEFT(Contactos.nombre_contacto, 1)), LCASE(SUBSTRING(Contactos.nombre_contacto, 2))) as usuario, Contactos.departamento_empresa, Clientes.id as id_cliente, Contactos.id as id_contacto from Contactos
	inner join Clientes on Clientes.id=Contactos.id_cliente  
	where Clientes.id=".$_POST['cliente']." and Contactos.id=".$_POST['usuario']."";
  	$resultado4= mysql_query($consulta4) or print("$consulta4" . mysql_error());
 	}else{
		if($_POST['cliente']!=""){
		$consulta4="select Clientes.alias as cliente, CONCAT(UCASE(LEFT(Contactos.nombre_contacto, 1)), LCASE(SUBSTRING(Contactos.nombre_contacto, 2))) as usuario, 		Contactos.departamento_empresa, Clientes.id as id_cliente, Contactos.id as id_contacto from Contactos
	inner join Clientes on Clientes.id=Contactos.id_cliente  
	where Clientes.id=".$_POST['cliente']."";
  	$resultado4= mysql_query($consulta4) or print("$consulta4" . mysql_error());
	}
  }
  }
  $color4="FFFFFF";

  while($res4=mysql_fetch_assoc($resultado4)){
 
$consulta5="select count(Cotizaciones.id) as cot, round(sum(CASE tipo_moneda
WHEN '1' THEN total*valor_moneda 
ELSE
total
END)) as total from Cotizaciones
where Cotizaciones.fecha_creacion>='$fecha_a 00:00:01' and id_contacto={$res4['id_contacto']}	";
 $resultado5= mysql_query($consulta5) or print("$consulta4" . mysql_error());
  $res5=mysql_fetch_assoc($resultado5);
 
 $consulta6="select count(Cotizaciones.id) as cot
  	from Cotizaciones
  	where Cotizaciones.fecha_creacion>='$fecha_a 00:00:01' 
    and id_contacto={$res4['id_contacto']}	and Cotizaciones.id_estatus=4";
 $resultado6= mysql_query($consulta6) or print("$consulta6" . mysql_error());
 $res6=mysql_fetch_assoc($resultado6);
 
 $consulta61="select count(Cotizaciones.id) as cot
  	from Cotizaciones
  	where Cotizaciones.fecha_creacion>='$fecha_a 00:00:01' 
    and id_contacto={$res4['id_contacto']}	and ( Cotizaciones.id_estatus=5 || Cotizaciones.id_estatus=6 || Cotizaciones.id_estatus=7 || Cotizaciones.id_estatus=8)";
 $resultado61= mysql_query($consulta61) or print("$consulta61" . mysql_error());
 $res61=mysql_fetch_assoc($resultado61);
 
 $porcentaje_e=($res6['cot']/($res6['cot']+$res61['cot']))*100;
  $color="FFFFFF";
  
  $consulta2="select DATE_FORMAT(max(fecha), '%d-%m-%Y') as ultima, comentarios from visitas where id_cliente={$res4['id_cliente']} and fecha>='$fecha_a 00:00:01' ";
 $resultado2= mysql_query($consulta2) or print("$consulta2" . mysql_error());
 $res2=mysql_fetch_assoc($resultado2);
 
 	$consulta7="select count(visitas_usuarios.id) as visitas, visita from visitas_usuarios where id_usuario={$res4['id_contacto']} and fecha>='$fecha_a 00:00:01'";
  	$resultado7= mysql_query($consulta7) or print("$consulta7" . mysql_error());
	$res7=mysql_fetch_assoc($resultado7);
  
  	$consulta73="select* from visitas_usuarios where id_usuario={$res4['id_contacto']} and fecha>='$fecha_a 00:00:01'";
  	$resultado73= mysql_query($consulta73) or print("$consulta73" . mysql_error());
	//echo $consulta73;
	$countF=0;
	$countV=0;
	while($res73=mysql_fetch_assoc($resultado73)){
	if($res73['visita']=="Física"){$countF++;}
	if($res73['visita']=="Virtual"){$countV++;}
	}
  ?>
 
  <tr bgcolor="#<? echo $color?>">
    <td class="texto_chico_gris" >&nbsp;<? echo $res4['cliente']?> / <b><? echo $res4['usuario']?></b></td>
    <td class="texto_chico_gris" align="center"><? echo $res4['departamento_empresa']?></td>
	<td class="texto_chico_gris" align="center"><? echo $res5['cot']?></td>
    <td class="texto_chico_gris" align="center"><? //setlocale(LC_MONETARY, 'en_US'); echo money_format('%(#10n', $res5['total']) ?><? echo number_format($res5['total'])?> MXN</td>
	<td class="texto_chico_gris" align="center">%<? echo $porcentaje_e?></td>
	<td class="texto_chico_gris" align="center"><? echo $res7['visitas']?> <? echo "/ ($countF / $countV)";?></td>
	<td class="texto_chico_gris" align="center"><? echo $res2['ultima']?></td>
	
    <td class="texto_chico_gris" align="center"><? $consulta8="select * from visitas_usuarios where id_usuario={$res4['id_contacto']}";
  	$resultado8= mysql_query($consulta8) or print("$consulta8" . mysql_error());
	while($res8=mysql_fetch_assoc($resultado8)){
	echo "{$res8['info']} <br/>";
	}
	?></td>
    <td class="texto_chico_gris" align="center"><? $consulta9="select * from visitas_usuarios where id_usuario={$res4['id_contacto']}";
  	$resultado9= mysql_query($consulta9) or print("$consulta9" . mysql_error());
	while($res9=mysql_fetch_assoc($resultado9)){
	echo "{$res9['datos']} <br/>";
	}
	?></td>
    <td class="texto_chico_gris" align="center"><? echo $res2['comentarios']?></td>
    <!--<td class="texto_chico_gris" align="center"><? //echo $res6['cot']?></td>
	<td class="texto_chico_gris" align="center"><? //echo $res6['total']?> MXN</td>-->
  </tr>
  <? }?>
  <tr bgcolor="#E9E9E9">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
		 <tr>
	     <td >&nbsp;</td>
	     </tr>
		 <tr>
		   <td >&nbsp;</td>
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
<script> 
window.onload=function(){ 
cambiar1(); 


} 
</script> 
</form>
</body>
</html>
