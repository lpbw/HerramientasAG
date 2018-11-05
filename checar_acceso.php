<?
    /**
     * Test
     * Developer: Luis Perez
     * Date: 27/07/2018 
     */

    function checarAcceso($nivelAcceso)
    {
        if(intval($_SESSION['usuario']->id_rol) > $nivelAcceso)
        {
            if (intval($_SESSION['usuario']->id_rol) == 9) {
            }else {
                $alert = "<script>";
                $alert .= "alert('No tienes acceso a esta pagina');";
                $alert .= "if(inIframe()){parent.location = 'index.php';}";
                $alert .= "else{window.location = 'index.php';}";
                $alert .= "function inIframe () {";
                $alert .= "try {return window.self !== window.top;}";
                $alert .= "catch (e) {return true;} }";
                $alert .= "</script>";
                echo $alert;
            }
           
        }
    }
    
    function tieneAcceso()
    {
        if(!isset($_SESSION['cliente']))
        {
		    return false;
        } 
        else
        {
		    return true;
	    }
    }
function setAccesoIndividual(){
    /*
     * Los argumentos pasados a esta funcion son
     * los roles que tienen acceso
     */
//    print_r(func_get_args());
    foreach (func_get_args() as $key => $value) {
        if($_SESSION['usuario']->id_rol == intval($value))
            return true;
    }
	?>
    <script>
        alert('No tienes acceso a esta pagina');
        window.location = '<? echo end(split('/',$_SERVER['HTTP_REFERER']));?>';
    </script>
    <?
} 

function hasPermiso(){
    /*
     * Los argumentos pasados a esta funcion son
     * los roles que tienen permiso
     */
    foreach (func_get_args() as $value) {
        if($_SESSION['usuario']->id_rol == $value){
            return  TRUE;
            break;
        }
    }
    return FALSE;
} 

function _SELFRedirect(){
    ?>
<script>window.location.reload()</script>
    <?
}
?>