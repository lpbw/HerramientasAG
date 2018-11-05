<?
$_TEST = FALSE;

include_once 'Usuario.php';
include_once 'Producto.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);
$extraMensaje = "";
$errores = 0;

$extensionArchivo = end(explode(".", $_FILES['archivo']["name"]));

if( $_POST["guardar"]=="Importar"){
    if(($extensionArchivo =="csv" || $extensionArchivo=="CSV" )){
		
		$consulta="DELETE FROM datos";
		$resultado = mysql_query($consulta) or print("La consulta linea: " . mysql_error());
		//obtenemos el archivo .csv
		$tipo = $_FILES['archivo']['type'];
		
		$tamanio = $_FILES['archivo']['size'];
		
		$archivotmp = $_FILES['archivo']['tmp_name'];
		
		//cargamos el archivo
		$lineas = file($archivotmp);
		
		//inicializamos variable a 0, esto nos ayudar� a indicarle que no lea la primera l�nea
		$i=0;
		
		//Recorremos el bucle para leer l�nea por l�nea
				foreach ($lineas as $linea_num => $linea)
				{ 
					//abrimos bucle
					/*si es diferente a 0 significa que no se encuentra en la primera l�nea 
					(con los t�tulos de las columnas) y por lo tanto puede leerla*/
					/*if($i != 0) 
					{*/ 
					//abrimos condici�n, solo entrar� en la condici�n a partir de la segunda pasada del bucle.
					/* La funcion explode nos ayuda a delimitar los campos, por lo tanto ir� 
					leyendo hasta que encuentre un ; */
					$datos = explode(",",$linea);
					
					//Almacenamos los datos que vamos leyendo en una variable
					//usamos la funci�n utf8_encode para leer correctamente los caracteres especiales
					$marca = utf8_encode($datos[0]);
					$linea = utf8_encode($datos[1]);
					$factor = $datos[2];
					$notas = utf8_encode($datos[3]);
					$desc_f =	$datos[4];
					$desc_cuu = $datos[5];
					$desc_jrz = $datos[6];
					$desc_max = $datos[7];
					
					//guardamos en base de datos la l�nea leida
					mysql_query("INSERT INTO datos(marca,linea,factor,notas,descuento_frontera,descuento_cuu,descuento_jrz,descuento_maximo) VALUES('$marca','$linea','$factor','$notas','$desc_f','$desc_cuu','$desc_jrz','$desc_max')");
					
					//cerramos condici�n
					/* }*/
					
					/*Cuando pase la primera pasada se incrementar� nuestro valor y a la siguiente pasada ya 
					entraremos en la condici�n, de esta manera conseguimos que no lea la primera l�nea.*/
					$i++;
					//cerramos bucle
				

	}
	echo "<script>alert('Nuevos datos guardados');</script>";
		}
			}
?>
<html>
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.numberTiny {	width: 60px;
	text-align: center;
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
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="100%" border="0" align="center" cellpadding="0">
    <tr>
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">Actualizar Descuentos</div></td>
    </tr>
    <tr>
      <td valign="top" class="texto_info_negro"><div class="texto_chico_rojo">A partir de la segunda linea</div>
        <b>Datos:</b><br/>
	  A. Marca	<br/>
	  B. Linea	<br/>
	  C. Factor	<br/>
	  D. Notas	<br/>
	  E. Descuento Frontera	<br/>
	  F. Descuento CUU	<br/>
	  G. Descuento JRZ	<br/>
	  H. Descuento Maximo	<br/>
	  
	  <div class="texto_chico_rojo">Al importar se eliminan los datos anteriores</div>
	  </td>
    </tr>
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">        
        <tr>
          <th width="87" valign="top" class="texto_info_negro" scope="row"><div align="right">Archivo...</div>            <!--<div align="right">Extra&iacute;do de microsip 
            <input name="extraido_microsip" type="checkbox" id="extraido_microsip" value="1">
          </div>--></th>
          <td width="476" class="style5"><input name="archivo" type="file" class="texto_info_negro_forma" id="archivo">
            <br>
            <em><span class="texto_chico_gris">(Recuerda solo .csv)</span></em></td>
        </tr>
      </table></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="guardar" type="submit" class="texto_info" value="Importar"  onClick="return validar();"/>
      </div></td>
    </tr>
  </table>
</form>
</body>
