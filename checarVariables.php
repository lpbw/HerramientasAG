<?
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
include 'Cliente.php';
//function __autoload($class_name) {
//    include $class_name . '.php';
//    echo "$class_name.php<br>";
//}
session_start();

include "coneccion.php";
$id=2222;
//var_dump(setcookie("usuarioID", $id, time() + 43200));

var_dump($_COOKIE);

var_dump(getdate());

function gett($array){
    $count=1;
    foreach ($array as $key => $value) {
        if(is_array($value)){
            gett($value);
            $count++;
        }
        echo "<br><h$count><br>$key</h$count>";
        var_dump($value);
    }
}
function otravez($array){
    foreach ($array as $key => $value) {
        echo "<br><br><b>$key</b><br>";
        var_dump($value);
        if( is_array($value) )
            otravez($value);
    }
}
otravez($_SESSION);
echo "<BR><BR><BR><BR><BR>SESSION";
var_dump($_SESSION);
?>