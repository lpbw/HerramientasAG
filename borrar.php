<?
include "checar_sesion_admin.php";
include "coneccion.php";

$id=$_POST["id"];
$tabla=$_POST['tabla'];


$consulta  = "DELETE FROM $tabla WHERE id=$id";
$resultado = mysql_query($consulta) or print("Error en operacion $tabla: " . mysql_error());
echo"<script>alert(\"Borrado\");</script>";
echo"<script> window.location='".end(split('/',$_SERVER['HTTP_REFERER']))."';</script>";
?>