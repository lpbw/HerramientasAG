<?

	include "coneccion.php";
	$id1=$_POST['id1'];//id que buscas.
	$id2=$_POST['id2'];//id que sustituira al id buscado.
	
	$count1=0;
	$count2=0;
	$count3=0;
	$count4=0;
	$count5=0;
	
	$c1="";
	$c2="";
	$c3="";
	$c4="";
	$c5="";
	
	if($_POST['enviar']=='enviar')
		{
		//actualizar la tabla de Contactos si existen registros.
		$query="SELECT * FROM Contactos WHERE id_cliente='$id1'";
		$resquery=mysql_query($query) or print("La consulta query1 del archivo alterar_cliente.php fallo: " . mysql_error());
		while($res=mysql_fetch_assoc($resquery))
		{
			$idvu=$res['id'];
			$queryupdate="UPDATE Contactos SET id_cliente='$id2' WHERE id='$idvu'";
			$resqueryupdate=mysql_query($queryupdate) or print("La consulta queryupdate1 del archivo alterar_cliente.php fallo: " . mysql_error());
			$count1++;
		}
		$c1="$count1 registros modificados de Contactos.";
		
		//actualizar la tabla de Cotizaciones si existen registros.
		$query="SELECT * FROM Cotizaciones WHERE id_cliente='$id1'";
		$resquery=mysql_query($query) or print("La consulta query2 del archivo alterar_cliente.php fallo: " . mysql_error());
		while($res=mysql_fetch_assoc($resquery))
		{
			$idvu=$res['id'];
			$queryupdate="UPDATE Cotizaciones SET id_cliente='$id2' WHERE id='$idvu'";
			$resqueryupdate=mysql_query($queryupdate) or print("La consulta queryupdate2 del archivo alterar_cliente.php fallo: " . mysql_error());
			$count2++;
		}
		$c2="$count2 registros modificados de Cotizaciones.";
		
		//actualizar la tabla de correo_enviado si existen registros.
		$query="SELECT * FROM correo_enviado WHERE id_cliente='$id1'";
		$resquery=mysql_query($query) or print("La consulta query3 del archivo alterar_cliente.php fallo: " . mysql_error());
		while($res=mysql_fetch_assoc($resquery))
		{
			$idvu=$res['id'];
			$queryupdate="UPDATE correo_enviado SET id_cliente='$id2' WHERE id='$idvu'";
			$resqueryupdate=mysql_query($queryupdate) or print("La consulta queryupdate3 del archivo alterar_cliente.php fallo: " . mysql_error());
			$count3++;
		}
		$c3="$count3 registros modificados de correo_enviado.";
		
		//actualizar la tabla de visitas si existen registros.
		$query="SELECT * FROM visitas WHERE id_cliente='$id1'";
		$resquery=mysql_query($query) or print("La consulta query3 del archivo alterar_cliente.php fallo: " . mysql_error());
		while($res=mysql_fetch_assoc($resquery))
		{
			$idvu=$res['id'];
			$queryupdate="UPDATE visitas SET id_cliente='$id2' WHERE id='$idvu'";
			$resqueryupdate=mysql_query($queryupdate) or print("La consulta queryupdate3 del archivo alterar_cliente.php fallo: " . mysql_error());
			$count4++;
		}
		$c4="$count4 registros modificados de visitas.";
		
		//actualizar la tabla de visitas si existen registros.
		$query="SELECT * FROM visitas_usuarios WHERE id_cliente='$id1'";
		$resquery=mysql_query($query) or print("La consulta query3 del archivo alterar_cliente.php fallo: " . mysql_error());
		while($res=mysql_fetch_assoc($resquery))
		{
			$idvu=$res['id'];
			$queryupdate="UPDATE visitas_usuarios SET id_cliente='$id2' WHERE id='$idvu'";
			$resqueryupdate=mysql_query($queryupdate) or print("La consulta queryupdate3 del archivo alterar_cliente.php fallo: " . mysql_error());
			$count4++;
		}
		$c4="$count4 registros modificados de visitas_usuarios.";
		
		echo "$c1 <br> $c2 <br> $c3 <br> $c4 <br> $c5";
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
</head>
<body>
	<form name="e" id="e" method="post">
		<label>cliente viejo</label>
		<input type="text" name="id1" id="id1" />
		<label>ccliente nuevo</label>
		<input type="text" name="id2" id="id2" />
		<input type="submit" name="enviar" id="enviar" value="enviar"/>
	</form>
</body>
</html>
