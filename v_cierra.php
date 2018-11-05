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

if($_POST['guardar']=="Cerrar"){

	//$consulta="update visitas set comentarios='".$_POST['comentarios']."' where id=$id_visita";
	//$resultado= mysql_query($consulta) or print("$consulta" . mysql_error());
	
	$datos=$_POST['datos'];
	$info=$_POST['info'];
	$prioridad=$_POST['prioridad'];
	$estatus=$_POST['estatus'];
	$fecha_fin=$_POST['fecha_fin'];
	$comentarios_finales=$_POST['comentarios_finales'];
	
	$consulta2="update visitas_usuarios set  estatus='1', fecha_fin=now(), comentarios='$comentarios_finales' where id_visita=$id_visita and id_usuario=$id_usuario";
	$resultado2= mysql_query($consulta2) or print("$consulta2" . mysql_error());

	echo"<script>parent.location=\"v_menu.php\"</script>";
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Visitas</title>
<style type="text/css">
<!--

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
<!--


function cerrarV(){
	$.fn.colorbox.close();
}
//-->
</script>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
</head>

<body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_industrias_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
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
    <td colspan="2" class="texto_chico_gris_bold" align="center">&nbsp;</td>
    <td colspan="2" class="texto_chico_gris_bold" align="center">&nbsp;</td>
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
        <td width="25%" class="texto_chico_gris"><? echo $res2['nombre_contacto']?></td>
        <td width="25%" class="texto_chico_gris"><? echo $res2['datos']?></td>
        <td width="25%" class="texto_chico_gris"><? echo $res2['info']?></td>
        <td width="20%" class="texto_chico_gris" align="center">Si<input type="radio" name="prioridad" value="1"  <? if($res2['prioridad']=="1"){echo"checked";}?>> No<input type="radio" name="prioridad" value="0" <? if($res2['prioridad']=="0"){echo"checked";}?>></td>
      </tr>
   
	</table></td>
    </tr>
  <tr>
    <td colspan="4" align="center" class="texto_chico_gris_bold">Comentarios finales: <br/>      <textarea name="comentarios_finales" cols="45" rows="4"><? echo $res2['comentarios']?></textarea></td>
    </tr>
  <tr>
    <td colspan="4" class="texto_chico_gris_bold" align="center"><input type="submit" name="guardar" id="guardar" value="Cerrar" class="texto_info"></td>
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
    </table>
</form>
</body>
</html>
