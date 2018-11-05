<?
include_once 'Usuario.php';
include_once "coneccion.php";
include_once 'mailTareas.php';


//------DEBUG        
//        $usuario = new Usuario;
//        $usuario->getUser(39);
//
//        echo getBodyTareas($usuario->getTareas());
//------DEBUG

$query = "SELECT id FROM Usuarios WHERE id != 1";
$result = mysql_query($query) or print(mysql_error());
if($result){
    while ($user = mysql_fetch_array($result)) {
        $usuario = new Usuario;
        $usuario->getUser($user['id']);
        $usuario->mailTareasToday();
    }
}

?>