<?
function tieneAcceso(){
    if(!isset($_SESSION['cliente'])){
		return false;
    } else {
		return true;
	}
}
?>