<?
include_once "Usuario.php";
include_once 'Producto.php';
session_start();
//include_once "checar_sesion_admin.php";
include_once "coneccion.php";
//include_once "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['todos']);

        $consulta  = "SELECT 
			Productos.id, 
			CONCAT(Proveedores.prefijo, Productos.codigo) AS codigo_interno
            FROM Productos
            LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor
            WHERE 1";
        echo $consulta."<BR>";
        $resultado = mysql_query($consulta) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error());    
		while($producto = mysql_fetch_assoc($resultado)){
			print_r($producto);
                        $query  = "UPDATE Productos SET codigo_interno = '".$producto['codigo_interno']."'
                            WHERE id = ".$producto['id'];
                        $result = mysql_query($query) or print("<br><h1>ERROR</h1>".mysql_error());
			echo "<Br><BR>";
                        
		}
?>