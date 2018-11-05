<?
ini_set('display_errors','0');

include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
date_default_timezone_set('America/Chihuahua');
if(isset($_POST["guardar"])){
    $_SESSION['cotizacion']->productos[$_REQUEST['pos']]->comentario = $_POST['comentario'];
    
    if( $_SESSION['cotizacion']->updateProductos( $_SESSION['cotizacion']->productos ) ){
        $_SESSION['carrito'] = $_SESSION['cotizacion'] -> productos;
        ?><script>parent.cerrarV();</script><?
    } else {
        ?><script>alert('error en session. Contacta a tu admin');</script><? //parent.parent.location.reload();
    }
}
if(isset($_REQUEST['pos'])){
    $producto = $_SESSION['cotizacion']->productos[$_REQUEST['pos']];
}


?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!--<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(function(){
        new nicEditor().panelInstance('saludo');
        new nicEditor().panelInstance('firma');
        new nicEditor().panelInstance('mensaje');});
    function format(){
        nicEditors.findEditor('saludo').saveContent();
        nicEditors.findEditor('firma').saveContent();
        nicEditors.findEditor('mensaje').saveContent();
    }
</script>-->

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.style5 {font-size: 18}
-->
</style>

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: -10px;
	background-color: #FFFFFF;
	margin-top: -10px;
}
.style51 {font-size: 12}
</style>

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
-->
</style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px">
                          <?
                $conDescr="";
                if($_SESSION['cotizacion'] ->idioma == 'ESP'){
                    if( $producto->nombre == ""){
                        $conDescr = "no";
                        $attrName = "nombre";    
                    }
                } else if($_SESSION['cotizacion'] ->idioma == 'ENG'){
                    if($producto->descripcion == "")
                        $conDescr = "no";
                    
                    $attrName = "descripcion";
                }
                    
if($conDescr == "no"){
        ?>Sin descripci&oacute;n <img src="images/warning.png" alt="" name="warning" width="16" height="16" id="warning"/> <?
} else {
    ?> <? echo $_SESSION['cotizacion'] ->idioma == 'ESP' ? "$producto->nombre ($producto->codigo_interno)":"$producto->descripcion ($producto->codigo_interno)"; ?> </a>
 <? }?>
<div align="center">
    <input type="hidden" value="<? echo $_REQUEST['pos'];?>" name="pos"/>
    <textarea onBlur="" name="comentario" id="comentario" rows="10" class="texto_info_negro_chico" style="width:96%;" ><? if(isset($producto->comentario)){$coment1=str_replace("\\","",$producto->comentario); echo stripcslashes($coment1);}?></textarea>
  </div>
<input type="submit" value="Guardar" name="guardar" onClick="changeImg()"/>
    <script>
        function changeImg(){
            var per = "0%";
            if(document.getElementById("comentario").value=="")
                per = "100%";
            else per = "0%";
            parent.document.getElementById('nota<? echo $_SESSION['cotizacion']->productos[$_REQUEST['pos']]->id;?>').setAttribute("style", "cursor: pointer;-webkit-filter: invert("+per+");");
        }
    </script>
</div>
</form>
</body>
</html>
