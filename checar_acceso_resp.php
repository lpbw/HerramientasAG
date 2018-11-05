<?
function checarAcceso($nivelAcceso){
if(intval($_SESSION['usuario']->id_rol) > $nivelAcceso){
    ?>
    <script>
        alert('No tienes acceso a esta pagina');
        if(inIframe()){
//            window.location = '<? echo end(split('/',$_SERVER['HTTP_REFERER']));?>';
            parent.location = 'index.php';
        } else {
            window.location = 'index.php';
        }
        
        function inIframe () {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        }
    </script>
    <?
    }
}
function tieneAcceso(){
    if(!isset($_SESSION['cliente'])){
		return false;
    } else {
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