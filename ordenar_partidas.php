<?
ini_set('display_errors', '1');
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";

if(isset($_POST['reset'])){
    $count = 1;
    foreach ($_SESSION['cotizacion'] -> productos as $n => $producto) {
        $producto->partida = $count;
        $count++;
    }
}

if(isset($_POST['cambiar'])){
    
    /* CAMBIANDO PARTIDAS */    
    foreach ($_POST['productosPos'] as $n => $pos) {
        $_SESSION['cotizacion'] -> productos[$pos]->partida = $n + 1;
    }
    
    /* copiando el arreglo */
    foreach ($_SESSION['cotizacion'] -> productos as $n => $producto) {
        $productoArrayAux[$n] = clone $_SESSION['cotizacion'] -> productos[$n];
    }
    
    /* CAMBIANDO ORDEN */
    foreach ($_POST['productosPos'] as $n => $pos) {
        $_SESSION['cotizacion'] -> productos[$n] = clone $productoArrayAux[$pos];
    }
    
    if( $_SESSION['cotizacion']->updateProductos( $_SESSION['cotizacion'] -> productos ) ){
        $_SESSION['carrito'] = $_SESSION['cotizacion'] -> productos;
        ?><script>parent.location.reload();</script><?
        
    } else {
        ?><script>alert('error en session. Contacta a tu admin');parent.parent.location.reload();</script><?
    }
}
$noPartida = !intval($_SESSION['cotizacion'] -> productos[0] -> partida);

?>
<html>
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Ordenar Partidas</title>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  
  <!-- drag and drop jquery-->
  <style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 90%; }
  #sortable li { margin: 0 5px 5px 5px; padding: 5px; height: 1.5em; text-align: left; }
  #sortable span { margin-right: 5px; display: inline-block}
  html>body #sortable li { height: 1.5em; line-height: 1.2em; }
  .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
  </style>
  <script>
  $(document).ready(function(){
    $( "#sortable" ).sortable({
      placeholder: "ui-state-highlight"
    });
    $( "#sortable" ).disableSelection();
    $( "#sortable" ).sortable({ axis: "y" });
  $('li').attr('class','ui-state-default texto_info_negro_c');
							 });
  </script>
</head>

<body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/carrito_r.jpg')">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div  style="margin-top:10px; width:100%;" align="center" >

    <ul id="sortable">
    <?	  
    if($noPartida){
        $count = 1;
        foreach ($_SESSION['cotizacion'] -> productos as $n => $producto) {
            $producto->partida = $count;
            $count++;
        }
    }
    foreach ($_SESSION['cotizacion'] -> productos as $n => $producto) { ?>
        <li>
        <span id="partida<? echo $producto->id;?>" ><? echo $producto->partida;?></span>
        <input name="productosPos[]" type="hidden" value="<? echo $n;?>"/>
        <? echo $_SESSION['cotizacion'] ->idioma == 'ESP' ? 
                "$producto->nombre ($producto->codigo_interno)":
            "$producto->descripcion ($producto->codigo_interno)"; ?>
        <span id="cantidad<? echo $producto->id;?>" style="float: right" >[<? echo $producto->cantidad;?>]</span>
        </li>
    <? } ?>
</ul>
    <input type="submit" value="Cambiar" name="cambiar" />
    <input type="submit" value="Reset" name="reset" />
</div>
</form>
</body>
</html>