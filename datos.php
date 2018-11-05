<?

include 'Usuario.php';
session_start();
include "coneccion.php";

$id_rol=$_SESSION['usuario']->id_rol;

if($_POST['guardar']=="Guardar"){

		$id_l=$_POST['id_l'];
		$notas_adicionales=$_POST['notas_adicionales'];

		$consulta="update datos set notas_adicionales='$notas_adicionales' where marca='$id_l'";
		$resultado = mysql_query($consulta) or print("La consulta linea: $consulta" . mysql_error());
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../../wamp/www/herramientas_AG/version_final/images/textos.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
<link type="text/css" href="../../../wamp/www/herramientas_AG/version_final/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="../../../wamp/www/herramientas_AG/version_final/js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="../../../wamp/www/herramientas_AG/version_final/js/jquery-ui-1.8.16.custom.min.js"></script>
		<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.style2 {color: #FF0000}
-->
</style>
<script>
	function buscar_linea(marca, i)
	{
		
		if (window.XMLHttpRequest)
		  		{// code for IE7+, Firefox, Chrome, Opera, Safari
		  			xmlhttp=new XMLHttpRequest();
		  		}
				else
		  		{// code for IE6, IE5
		  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  		}
					xmlhttp.onreadystatechange=function()
		  		{
					
		  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					resultado=xmlhttp.responseText;
					
					var info=document.getElementById('linea');
					
					info.innerHTML=resultado;
					 
					buscar_datos(marca,0, 2);
					$('linea').empty();
				
			    }
		  	}
				
				xmlhttp.open("GET","buscar_datos.php?marca="+marca+"&i="+i+"",true);
				xmlhttp.send();
				
	}
	
	function buscar_datos(marca,linea, i)
	{
		if (window.XMLHttpRequest)
		  		{// code for IE7+, Firefox, Chrome, Opera, Safari
		  			xmlhttp=new XMLHttpRequest();
		  		}
				else
		  		{// code for IE6, IE5
		  			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  		}
					xmlhttp.onreadystatechange=function()
		  		{
					
		  		if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					resultado=xmlhttp.responseText;
					
					var info=document.getElementById('tabla');
					
					info.innerHTML=resultado;
					
			    }
		  	}
				
				xmlhttp.open("GET","buscar_datos.php?marca="+marca+"&i="+i+"&linea="+linea,true);
				xmlhttp.send();
	}
	window.onload=buscar_linea(0);
</script>
</head>
<body>
<form action="" method="post"   name="form1" id="form1">
	<table width="600px"  border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#E3E3E3" style="max-width:600px" >
		<tr>
			<th align="left" valign="top" class="texto_info" scope="row"  width="200px">MARCA</th>
			<td class="style5"  width="400px"><select name="marca" id="marca" onChange="buscar_linea(this.value, 1); ">
		<option value="0">SELECCIONE MARCA</option>
		<?
			$consulta="select marca from datos group by marca";
			$resultado = mysql_query($consulta) or print("La consulta marca: " . mysql_error());
			while($res=mysql_fetch_assoc($resultado))
			{
		?>
		<option value="<? echo $res['marca'];?>"><? echo $res['marca'];?></option>
		<?
			}
		?>
	</select></td>
		</tr>
		<tr>
			<th align="left" valign="top" class="texto_info" scope="row" width="200px">LINEA</th>
			<td  class="style5"  width="400px"><select name="linea" id="linea" onChange="buscar_datos(marca.value,this.value, 2);">
			
	</select></td>
		</tr>
		<tr>
		<td colspan="2">
		<table  width="600px" border="1" align="center" cellpadding="0" cellspacing="0" bgcolor="#E3E3E3"  name="tabla"id="tabla">
		
		</table>
		</td>
		</tr>
		<tr>
			<td   class="style2" align="center">Si el Factor es 0, el precio esta en el cotizador</td>
			<td   class="style2" align="center"><? if($id_rol=="1"){?><input type="submit" name="guardar" id="guardar" value="Guardar" ><? } ?></td>
		</tr>
		 
	</table>
</form>
</body>