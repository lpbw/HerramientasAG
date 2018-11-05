<?
session_start();
include "checar_sesion_admin.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);
include "coneccion.php";
$id_proveedor = $_GET['id_proveedor'];
$query = "SELECT * FROM Productos WHERE id_proveedor = $id_proveedor order by codigo";
$result= mysql_query($query) or print("error 1 $query");

for($i=1;$productoArray = mysql_fetch_array($result);$i++){
    
    $query = "UPDATE Productos SET numero_consecutivo = $i
        WHERE id_proveedor = $id_proveedor AND id = ".$productoArray['id'];
    echo "$query<br>";
    $r = mysql_query($query) or print("error 2 $query");;

}

?>