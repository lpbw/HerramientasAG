<?
function __autoload($class_name) {
    include_once $class_name . '.php';
    echo "$class_name.php<br>";
}
session_start();
print_r($_SESSION['cotizacion']->productos);