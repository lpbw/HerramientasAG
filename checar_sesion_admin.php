<?
if(!isset($_SESSION['usuario'])){
    if(isset($_COOKIE['usuarioID'])){
        include_once 'Usuario.php';
        $usuario = new Usuario();
        $usuario->getUser($_COOKIE['usuarioID']);
        setcookie("usuarioID", $usuario->id, time() + 43200);
        $_SESSION['usuario'] =  $usuario;
    } else {
	?><script> 
        parent.location = 'index.php';
        window.location = 'index.php';
        </script><?
    }
}
if ($_SESSION['usuario']=="" || !$_SESSION['usuario'] ){
	?><script>
        
    parent.location = 'index.php';
    window.location = 'index.php';
</script><?
}

?>