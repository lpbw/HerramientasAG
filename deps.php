<?
include_once "coneccion.php";

	 $query = "SELECT departamento_empresa FROM Contactos group by departamento_empresa
ORDER BY Contactos.departamento_empresa  ASC";
     $result = mysql_query($query);
     while ($res = mysql_fetch_assoc($result)) {
            
		$consulta  = "insert into departamentos (nombre) values('{$res['departamento_empresa']}')";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " . mysql_error());
			
     }

?>