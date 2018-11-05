<?
function tienePermisoEscritura(){
    /*
     * Los argumentos pasados a esta funcion son
     * los roles que tienen permiso de escritura
     * sobre la vista elegida
     */
    $permiso = true;
    foreach (func_get_args() as $key => $value) {
        if($_SESSION['usuario']->id_rol == intval($value))
            return true;
        else $permiso = false;
    }
    
    foreach ($_SESSION['usuario']->permisos as $key => $value){
        if(intval($key) == 1)
            return true;
        else $permiso = false;
    }
    return $permiso;
}


function tienePermisoEliminarProveedores(){
    /*
     * Los argumentos pasados a esta funcion son
     * los roles que tienen permiso
     */
    foreach (func_get_args() as $key => $value) {
        if($_SESSION['usuario']->id_rol == intval($value))
            return true;
    }
    foreach ($_SESSION['usuario']->permisos as $key => $value){
        if(intval($key) == 1);
            return true;
    }
}
?>