<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<br /><br />
<? 

include "Usuario.php";
include "Producto.php";
session_start();
var_dump($_SESSION);
print_r($_SESSION['carrito'])
?>
<br /><br />
<? //print_r($_POST);?>
<br /><br />
<?// print_r($_GET);

include "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['administrador']);
?>
</body>
</html>