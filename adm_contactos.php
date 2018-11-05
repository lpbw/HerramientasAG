<?
include 'Usuario.php';
include 'Cliente.php';
include 'Contacto.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['vendedor25']);



$id_usuario=$_SESSION['usuario']->id;
$id_cliente = $_SESSION['cambiaCliente']->id;

if(isset($_POST['idB'])){
    $contacto = new Contacto();
    $contacto->get($_POST['idB'], $id_cliente);
    $contacto->delete();
    ?><script>window.location='adm_contactos.php';</script><?
}

$consulta  = "SELECT * FROM Contactos WHERE id_cliente = '$id_cliente' ORDER BY Contactos.nombre_contacto";
	
$resultado = mysql_query($consulta) or print("La consulta fallo lista clientes: " . mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<title></title>
<style type="text/css">
<!--
body {
	margin:10px;
	background-color: #FFFFFF;
	/*background-image: url(images/bkg_1.jpg);*/
}
-->
</style>
<script type="text/javascript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
function borrar(nombre,id){
    if(confirm("¿Esta seguro de borrar?")){
        var elem = document.createElement('input');
        elem.name = "idB";
        elem.value = id;
        elem.type = 'hidden';
        document.getElementById('form_borrar').appendChild(elem);
        document.getElementById('form_borrar').submit();
    } else return false;
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
<style type="text/css">
<!--
.style5 {font-size: 18}
.style6 {color: #FFFFFF}
-->
</style>
</head>

<body onLoad="MM_preloadImages('images/cerrar_r.jpg')" >
<div style="overflow: inherit; width: 100%; height: 460px; ">
<form id="form1" name="form1" method="post" action="myadmin.php">
  
          <table width="530" border="0" cellpadding="0">
            <tr class="titulo_tabla">
              <td colspan="6" class="style8" scope="row" align="right"><a href="cambia_contacto2.php?id_cliente=<? echo $id_cliente?>" class="texto_info">Alta Contacto</a></td>
            </tr>
            <tr class="titulo_tabla" style="background-image: url(images/bkg_1.jpg);">
              <td width="500" class="style8" scope="row"><div align="center" class="style5 style6">Nombre</div></td>
              <td class="style8" scope="row"><div align="center" class="style5 style6">Email</div></td>
              <td class="style8" scope="row"><div align="center" class="style5 style6">Telefono</div></td>
              <td class="style8" scope="row"><div align="center" class="style5 style6">Departamento</div></td>
              <td class="style8" scope="row"><div align="center" class="style5 style6">Es comprador</div></td>
              <td width="4%" class="style8"><div align="center">&nbsp;</div></td>
            </tr>
            <?
	$count=1;
	while(@mysql_num_rows($resultado)>=$count)
	{
		$res=mysql_fetch_assoc($resultado);
		
		?>
            <tr>
              <td class="style5"><div align="center"><span class="texto_info_negro"> <a href="cambia_contacto.php?id=<? echo $res['id'];?>&id_cliente=<? echo $id_cliente?>" class="texto_info_negro iframe"><? if($res['activo']==0){echo"***";}?><? echo $res['nombre_contacto'];?></a></span></div></td>
              <td class="style5"><div align="left"><a href="cambia_contacto.php?id=<? echo $res['id'];?>&id_cliente=<? echo $id_cliente?>" class="texto_info_negro iframe"><? echo $res['email_contacto'];?></a></div></td>
              <td class="style5"><a href="cambia_contacto.php?id=<? echo $res['id'];?>&id_cliente=<? echo $id_cliente?>" class="texto_info_negro"><? echo $res['telefono_contacto'];?></a><a href="cambia_contacto.php?id=<? echo $res['id'];?>&id_cliente=<? echo $id_cliente?>" class="texto_info_negro iframe"></a></td>
              <td class="style5"><a href="cambia_contacto.php?id=<? echo $res['id'];?>&id_cliente=<? echo $id_cliente?>" class="texto_info_negro"><? echo $res['departamento_empresa'];?></a></td>
              <td class="style5"><a href="cambia_contacto.php?id=<? echo $res['id'];?>&id_cliente=<? echo $id_cliente?>" class="texto_info_negro"><? echo $res['es_comprador'];?></a></td>
              <td class="style5"><div align="center"><a  href="#"  onclick="borrar('<? echo $res['nombre_empresa'];?>',<? echo $res['id'];?>);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id','','images/cerrar_r.jpg',1)"><img src="images/cerrar.jpg" alt="" name="Image<? echo $res['id'];?>" width="17" height="16" border="0" id="Image22" /></a> </div></td>
            </tr>
            <?
			   $count=$count+1;
	}
	
?>
          </table>
    <input type="button" onClick="window.location = 'cambia_cliente.php';" value="Regresar" />
</form>
<form action="" name="form_borrar" target="_self" id="form_borrar"  method="post"></form>
</div>
</body>
</html>
