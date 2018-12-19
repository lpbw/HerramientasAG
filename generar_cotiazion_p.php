<?
/*
 * CUANDO VALLA A GUARDAR LA COTIZACION DEBEN DE ESTAR INICIADAS
 * Y LLENADAS CORRECTAMENTE LAS SIGUIENTES VARIABLES
 * $_SESSION['usuario']
 * $_SESSION['carrito'] -- CONTIENE LOS PRODUCTOS VIVOS DE LA COTIZACIÓN Y
 *                          ES LA VARIABLE QUE SE PASA A COTIZACION PARA 
 *                          QUE ACTUALICE SUS PRODUCTOS.
 *                          -- ES DE ESCRITURA
 * 
 * $_SESSION['cotizacion']->productos
 *                          -- ES SOLO LECTURA LECTURA
 * 
 * $_SESSION['cotizacion'] -- CONTENDRÁ LOS PRODUCTOS DE LA COTIZACIÓN.
 * 
 * 
 * LOS PASOS PARA GUARDAR UNA COTIZACIÓN DEBEN SER
 * 1) GUARDAR DATOS GENERALES DE LA COTIZACIÓN (NO PRODUCTOS) TOMÁDOS DE ESTA VISTA
 * 2) BORRAR TODOS LOS PRODUCTOS Y VOLVERLOS A INSERTAR EN LA TABLA DE ProductosCotizacion
 * 
 * SI SE CREA UNA NUEVA VERSIÓN
 * 1) SE GUARDA LA COTIZACIÓN ACTUAL POR AJAX
 * 2) AL CREAR LA NUEVA COTIZACIÓN
 * 
 */



/*
 * LOS PRODUCTOS QUE ESTÁN EN LA COTIZACIÓN Y CARRITO GUARDAN EN PRECIO
 * EL VALOR ACTUAL PARA LA COTIZACIÓN, ES DECIR, CONVERTIDO A MXN O 
 * A USD SEGÚN EL CASO
 */


include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cliente.php';
include_once 'Cotizacion.php';

session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";

include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['mostrador']);
include_once 'functions_cotizacion.php';

include_once "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
	$_SESSION['accesos']['vendedor25'],
        $_SESSION['accesos']['vendedor']);

if($vistaLectura) $esLectura = "disabled";

if( isset($_REQUEST['idCotizacionEditar']) && isset($_REQUEST['idVersion'])){
    
    if($_GET['borrar']=='true'){
        $cotizacionBorrar = new Cotizacion();
        $cotizacionBorrar -> get( $_REQUEST['idCotizacionEditar'] , $_REQUEST['idVersion'] );
        
        if( $_GET['onlyVersion']=='true' ){
            $cotizacionBorrar -> deleteOneVersion();    
            echo "<script>parent.parent.location.reload();</script>";
            
        } else {
            $cotizacionBorrar -> delete();
            unset( $_SESSION['cotizacion'] );
            echo "<script>parent.location.reload();</script>";
        }

			
    } else {
        $cotizacion = new Cotizacion();
        /* obtiene exclusivamente los datos de la cotizacion, es decir, no obtiene los productos.*/
        $cotizacion -> get( $_REQUEST['idCotizacionEditar'] , $_REQUEST['idVersion'] );
        /* obtiene los productos.*/
        $cotizacion -> setCarritoFromCotizacion();
        $_SESSION['cotizacion'] = $cotizacion;
    }
}

if(isset($_POST['crearVersion'])){
//    guardarCotizacion();
//    saveCotizacionOnDB();
    
    if(!$_SESSION['cotizacion'] ->createVersion()){
        ?><script>alert('No se ha creado la cotizacion');</script><?
    }
}

if($_POST['con_iva']!=""){
    if($_POST['con_iva']=='true')
        $_SESSION['cotizacion']->con_iva=true;
    else
        $_SESSION['cotizacion']->con_iva=false;
    
    guardarCotizacion();
    saveCotizacionOnDB();//puede haber un error!! DEBUG
}

if($_POST['changeCurrencyTo']!=""){
    
    if(isset($_SESSION['carrito'])){
        
        include_once 'changeTipoMoneda.php';
        
        $valor_moneda = $_SESSION['dollar'];
        $tipo_moneda = $_POST['changeCurrencyTo'];
        
//        foreach ($_SESSION['carrito'] as $n => $producto) {
//            if($producto ->precio_original==""){
//                $producto ->precio_original = $producto->precio;
//            }
//            $producto -> precio     = changeTipoMoneda($producto, $tipo_moneda, $valor_moneda);
//        }
        
    }
    
    guardarCotizacion();
    saveCotizacionOnDB();
}

if($_POST['changeLanguageTo']!=""){
    guardarCotizacion();
    saveCotizacionOnDB();
}

//REVISADO
if($_GET['reloadCarritoOnId']!=''){
    reloadCarrito($_GET['reloadCarritoOnId']);
    clearURIVariables();
}

//REVISADO
if($_POST['posicion_borrar']!=""){
    
    unset( $_SESSION['carrito'][ $_POST['posicion_borrar'] ] );
    unset( $_SESSION['cotizacion']->productos[ $_POST['posicion_borrar'] ] );
//    unset( $_SESSION['cotizacion']->productos[ $_POST['posicion_borrar'] ] );
//    for( $n = intval($_POST['posicion_borrar']) + 1 ; $n <= count($_SESSION['carrito']); $n++) {
//        $_SESSION['carrito'][$n]->partida = $n;
//        $_SESSION['cotizacion']->productos[$n]->partida = $n;
//    }
    $count = 1;
    foreach ($_SESSION['cotizacion'] -> productos as $n => $producto) {
        $producto->partida = $count;
        $count++;
    }
    $count = 1;
    foreach ($_SESSION['carrito']as $n => $producto) {
        $producto->partida = $count;
        $count++;
    }
    guardarCotizacion();
    saveCotizacionOnDB();
}

if($_POST['posicion_borrar_archivo']!=""){
    $archivo_quitar = $_SESSION['cotizacion'] -> archivos[ $_POST['posicion_borrar_archivo'] ];
    if( $_SESSION['cotizacion']->borrarArchivo($archivo_quitar) )
        unset( $_SESSION['cotizacion']->archivos[ $_POST['posicion_borrar_archivo'] ] );
}

if($_POST['id_modificar_producto'] != ""){
    $id_modificar_producto = $_POST['id_modificar_producto'];
    guardarCotizacion();
    saveCotizacionOnDB();
    
    if($_POST['id_proveedor'] == 8) 
        $modificarProductoBox = "cambia_producto_especial.php?id=$id_modificar_producto";
    else
        $modificarProductoBox = "cambia_producto.php?id=$id_modificar_producto";
    
    echo $modificarProductoBox;
    
}

if($_POST['guardarCotizacion']!="" || $_POST['previsualizar']!=""){    
    if( $_POST['previsualizar']!="" )
        echo"<script>window.open(\"precotizacion.php\");</script>";
}

if(isset($_POST['guardarCotizacionYNuevo'])){
    unset($_SESSION['cotizacion']);
    unset($_SESSION['carrito']);
    ?><script> window.location.reload(); </script><?
    
}

function clearURIVariables(){
    $location = explode('?',$_SERVER['REQUEST_URI']);
    ?><script> window.location = '<? echo $location[0];?>'; </script><?
}

if(isset($_REQUEST['agregarProductoPorCodigo'])){
    $query = "SELECT id,precio FROM Productos WHERE Productos.codigo_interno LIKE '%{$_REQUEST['agregar_producto_codigo']}%'";
    $result = mysql_query($query) or print(mysql_error());
    
    if(mysql_num_rows($result)==1){
        $prod = mysql_fetch_assoc($result);
        if(intval($prod['precio'])==0){
            $guardarFromDirectlyAdd='true';
        }else{
            ?><script>window.location = 'agregar_carrito.php?id=<?echo $prod['id'];?>&cantidad=1&backTo=generar_cotizacion.php';</script><?
        }
    } else {
        $_SESSION['buscadorCotizaciones']['codigo_buscar'] = $_POST['agregar_producto_codigo'];
        ?><script>window.location = 'seleccionar_productos_cotizacion.php?submit=<? echo $_POST['agregar_producto_codigo'];?>';</script><?
    }
}

/* PARA LAS FLECHAS NEXT Y BACK */
if(isset($_REQUEST['limitInicio'])) $_SESSION['limitInicio'] = intval($_REQUEST['limitInicio']);
if( isset($_REQUEST['limit'])){
    


    //whereInnerJoin_NextBack_cotizaciones is set on adm_cotizacions when the filter is applied
    $consulta  = "SELECT Cotizaciones.id, Cotizaciones.id_version
                FROM Cotizaciones 
                {$_SESSION['whereInnerJoin_NextBack_cotizaciones']}
                LIMIT {$_REQUEST['limit']},1";
    if(intval($_REQUEST['limit'])<0) {    
        ?><script>alert('Inicio de la lista de cotizaciones buscadas');</script><?  
        $limitNext=1;
        $limitBack=-1;
    } else {
        $limitNext = intval($_REQUEST['limit'])+1;
        $limitBack = intval($_REQUEST['limit'])-1;
        $result = mysql_query($consulta) or print("Error en buscador");
        if(mysql_num_rows($result)>0){
            $row = mysql_fetch_assoc($result);
            $_SESSION['cotizacion'] = new Cotizacion();
            $cotizacion = new Cotizacion();
            $cotizacion -> get( $row['id'] , $row['id_version'] );
            $cotizacion -> setCarritoFromCotizacion();
            $_SESSION['cotizacion'] = $cotizacion;
        } else { ?><script>alert('Fin de la lista de cotizaciones buscadas');</script><? }
    }
    
} else {
    $limitNext=1 + $_SESSION['limitInicio'];
    $limitBack=-1 + $_SESSION['limitInicio'];
}
$id_usuario = $_SESSION['usuario']->id;
?>
    
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cotizaciones </title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: -10px;
	background-color: #FFFFFF;
/*	background-image: url(images/bkg_1.jpg);*/
	margin-top: -10px;
}
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
.style511 {font-size: 18}
.style511 {font-size: 18}
-->
<!--
.style5 {font-size: 12}
.style51 {font-size: 18}

.imgIco{margin-bottom: 10px;}

.rotateButton{
	top: 5.5em;
width: 4em;
right: -.5em;
position: fixed;
/* -webkit-transform: rotate(90deg); */
/*-moz-transform: rotate(90deg);*/
display: inline-block;
}

.menuLateral {
	outline: none;
	cursor: pointer;
/*	text-align: center;
	text-decoration: none;
	font: 14px/100% Arial, Helvetica, sans-serif;*/
	padding: .5em 1em .55em;
/*	margin-left:.25em;
	background-color: #F5F5F5;
	text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
	-webkit-border-radius: .5em;
	-moz-border-radius: .5em;
	border-radius: .5em;
	-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
	-moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);*/
}
-->
</style>

  

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="ajaxSubmit.js"></script> 
<script src="colorbox/jquery.colorbox-min.js"></script>
 
<script>
$(document).ready(function(){
                //Examples of how to assign the ColorBox event to elements

                $(".iframe").colorbox({iframe:true,width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
				
                $(".iframeMini").colorbox({iframe:true,width:"400", height:"553",transition:"fade", scrolling:true, opacity:0.5});

                //Example of preserving a JavaScript event for inline calls.
                $("#click").click(function(){ 
                        $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
                        return false;
                });
        });
	function goTo(url){
		window.location = url;
	}
	
	function eliminarProducto(posicion_borrar){
		var element = document.createElement('input');
		element.name='posicion_borrar';
		element.value = posicion_borrar;
		element.type = 'hidden';
		var form = document.getElementById('form1');
		form.appendChild(element);
		form.submit();
	}
	
	function modificarProducto(id,id_proveedor){
		var form = document.getElementById('form1');

		var element = document.createElement('input');
		element.name='id_modificar_producto';
		element.value = id;
		element.type = 'hidden';
		form.appendChild(element);
                
		element = document.createElement('input');
		element.name='id_proveedor';
		element.value = id_proveedor;
		element.type = 'hidden';
		form.appendChild(element);
                
		form.submit();
	}
	
	function eliminarArchivo(posicion_borrar){
		var element = document.createElement('input');
		element.name='posicion_borrar_archivo';
		element.value = posicion_borrar;
		element.type = 'hidden';
		var form = document.getElementById('formBorrarProductoCarrito');
		form.appendChild(element);
		form.submit();
	}
	
	function abrir(ir, isSizeMini)
	{
		if(isSizeMini){
		$.colorbox({iframe:true,href:""+ir+"",width:"400", height:"553",transition:"fade", scrolling:true, opacity:0.5});
		} else {
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
		}
	}
        
	function abrir2(ir){
            $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
	}
	function abrir3(ir){
            $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"250",transition:"fade", scrolling:true, opacity:0.5});
	}
        if('<? echo $modificarProductoBox; ?>' != ""){
            abrir('<? echo $modificarProductoBox; ?>' ,false);
        }
        
		
	function checkIfNumber(obj){
		if(!isNaN(obj.value) ){
			return true;
		} else {
			alert('escribe solo numeros en '+obj.name);
			obj.value = '';
			return false;
		}
	}
    function checkDescuento(n){
        var obj = document.getElementById('descuento' + n);
        var limite_descuento = parseFloat(<? echo $_SESSION['usuario']->limite_descuento?>) * 100;
        var precioAnterior = parseFloat( document.getElementById('subtotal' + n ).value );
        var numProveedor=document.getElementById('numProvee' + n ).value;
        if(numProveedor==20 && limite_descuento!=100)
            limite_descuento=parseFloat(0.52) * 100;
        if(obj.value<0)
        {
            document.getElementById('descuento' + n).value=Math.abs(obj.value);
            obj.value=Math.abs(obj.value);
        }
			
        if(obj.value > limite_descuento){
            alert('Tu l\u00edmite de descuento es ' + limite_descuento  + '%');
            document.getElementById('descuento' + n).value=0;
            obj.value=0;
            var precioUnit = document.getElementById('precio_unitario' + n).value;
            var cantidad = document.getElementById('cantidad' + n).value;
            var recargo = document.getElementById('recargo' + n).value;
            var descuento=(obj.value/100) * precioUnit;
            var subtotalProducto = (  (precioUnit*1 - descuento*1 + recargo*1) * cantidad ).toFixed(2);
            var precioVenta=(  (precioUnit*1 - descuento*1 + recargo*1) ).toFixed(2);
            document.getElementById('subtotal' + n).value = subtotalProducto;
            document.getElementById('precio_unitario_v' + n).value = precioVenta;
			
            setTotals( subtotalProducto , precioAnterior );
        } else {
            var precioUnit = document.getElementById('precio_unitario' + n).value;
            var cantidad = document.getElementById('cantidad' + n).value;
            var recargo = document.getElementById('recargo' + n).value;
            var descuento=(obj.value/100) * precioUnit;
            var subtotalProducto = (  (precioUnit*1 - descuento*1 + recargo*1) * cantidad ).toFixed(2);
            var precioVenta=(  (precioUnit*1 - descuento*1 + recargo*1) ).toFixed(2);
            document.getElementById('subtotal' + n).value = subtotalProducto;
            document.getElementById('precio_unitario_v' + n).value = precioVenta;
			
            setTotals( subtotalProducto , precioAnterior );
        }
    }
    function setTotals(subtotalProducto , precioAnterior){
        if(!isNaN(subtotalProducto) && !isNaN(precioAnterior)){
            var diferencia = subtotalProducto - precioAnterior;
            var subttl = parseFloat(document.getElementById('subtotal').value);
            var iva_value = (subttl + diferencia) * 0.16;

            document.getElementById('subtotal').value = subttl + diferencia;
            document.getElementById('total').value = subttl + diferencia + iva_value;
            document.getElementById('iva').value = iva_value;
            setViewCurrency('iva','ivaView');
            setViewCurrency('subtotal','subtotalView');
            setViewCurrency('total','totalView');
        }
    }
    
    var xmlhttp;
function loadXMLCurrency(){
    var xmlhttp;
    if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else  {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4){
            var cantidad = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","http://rate-exchange.appspot.com/currency?from=USD&to=MXN&",true);
    xmlhttp.send();
}
function changeCurrency(value){
	if(<? echo count( $_SESSION['cotizacion'] -> productos );?>>0)
	{
   		if(document.form1.tipo_moneda[0].checked)
		{
			document.form1.tipo_moneda[0].checked=false;
			document.form1.tipo_moneda[1].checked=true;
		}else
		{
			document.form1.tipo_moneda[1].checked=false;
			document.form1.tipo_moneda[0].checked=true;
		}
	}else
	{
		 var form = document.getElementById('form1');
    var element = document.createElement('input');
    element.name = 'changeCurrencyTo';
    element.value = value;
    element.type = 'hidden';
    form.appendChild(element);
    form.submit();
		
	}
}

function changeLanguage(value){
    var form = document.getElementById('form1');
    var element = document.createElement('input');
    element.name = 'changeLanguageTo';
    element.value = value;
    element.type = 'hidden';
    form.appendChild(element);
    form.submit();
}
function cerrarV(){
	window.location = 'generar_cotizacion.php';
	//$.fn.colorbox.close();
}
function agregarIva(obj){
    var form = document.getElementById('form1');
    var element = document.createElement('input');
    element.name = 'con_iva';
    element.type = 'hidden';
    element.value = obj.checked;
    form.appendChild(element);
    form.submit();
}
function validar(){
	var returnn = true;
	for(var i=1; i <= <? echo count($_SESSION['cotizacion'] ->productos);?> ; i++){
		var x = i;
		if(document.getElementById('conDescripcion' + i).value == 'no'){
			alert('Producto #' + i + ' sin descripcion');
			document.getElementById('conDescripcion' + i).focus();
			returnn = false;
			break;
		}
		if(document.getElementById('conUnidadMetrica' + i).value == 'no'){
			alert('Producto #' + i + ' sin unidad metrica');
			document.getElementById('conUnidadMetrica' + i).focus();
			returnn = false;
			break;
		}
		
		if(document.getElementById('cantidad' + i).value == ''
			|| document.getElementById('cantidad' + i).value <= 0){
			alert('La cantidad del producto #' + i + ' debe ser mayor que 0');
			document.getElementById('	' + i).focus();
			returnn = false;
			break;
		}
	} 
	
	if(document.getElementById('conIva').checked == false && returnn ){
		if(!confirm('La cotizacion no tiene iva. Continuar?'))
		returnn =  false;
		document.getElementById('conIva').focus();
	}
	
	return returnn;
}

function enviarCotizacion(){
	if(validar()){
		var isSizeMini = false;
		if(cotizacionSessionExist()){
			abrir('enviar_cotizacion.php',isSizeMini);
		}
	}
}
function cotizacionSessionExist(){
	if(<? if($_SESSION['cotizacion'] !="") echo 'true'; else echo 'false'; ?>)
		return true;
	else {
		alert('Primero Guarda la cotizacion antes de enviarla');
		document.getElementById('guardarCotizacion').focus();
		return false;
	}
}

//<--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}



//-->

function backNext(id){
    var elem = document.createElement('input');
    elem.name = "limit";
    elem.value = id;
    elem.type = 'hidden';
    document.getElementById('form1').appendChild(elem);
    document.getElementById('form1').submit();
}



function saveCotizacion(nextFunction, variables){
/* SE USABA PARA AUTOGUARDAR CUANDO PRESIONAN UN LINK
 * QUE NO TIENE PORQUE HACERLO, PORQUE EN CADA INPUT 
 * QUE SE MODIFIQUE SE GUARDARÁ LA COTIZACIÓN.
 * 
 * AHORA SÓLO MANDA LLAMAR LA FUNCIÓN
 */
    $("#form1").ajaxSubmit({
//        url: 'saveCotizacion.php', 
//        type: 'post', 
        success: nextFunction(variables)
    });
}
function autoSaveCotizacion(){
    var returnn = true;
    if(document.getElementById('id_cliente').value!=0 && document.getElementById('id_prioridad').value!=0){
	$("#form1").ajaxSubmit({
            url: 'saveCotizacion.php', 
            type: 'post'
        });
    } else if(document.getElementById('id_cliente').value==0){
        alert('Selecciona un cliente');
        document.getElementById('id_cliente').focus();
        returnn = false;
    } else if(document.getElementById('id_prioridad').value==0){
        alert('Selecciona prioridad');
        document.getElementById('id_prioridad').focus();
        returnn = false;
    }
    return returnn;
}
function clearSessionCotizacion(){
    $("#form1").ajaxSubmit({
        url: 'clearSessionCotizacion.php', 
        type: 'post'
    });
}
</script>
<script>
        /*
         * AGREGA A TODOS LOS INPUT Y TEXTAREA LA FUNCION
         * DE AUTOGUARDADO
         */
$(function(){
    $( "input,textarea" ).each(function( index ) {
        if( $(this).attr('type') != 'button' && $(this).attr('type') != 'submit' ){
            $(this).blur(function(){
                autoSaveCotizacion();
            });
        }
    });
    
    $( "select" ).each(function( index ) {
        if( $(this).attr('name') != 'id_cliente' ){
            $(this).change(function(){
                autoSaveCotizacion();
            });
        }else console.log($(this).attr('name'));
    });
});
</script>
<script src="convertFloatToCurrencyFormat.js"></script>
<style type="text/css">
<!--
.numberTiny {	width: 60px;
	text-align: center;
}

.numberMedium{	
	text-align: center;
} 
.rotateButton1 {top: 18.5em;
width: 37em;
right: -17em;
position: fixed;

    -webkit-transform: rotate(90deg); 
    -moz-transform: rotate(90deg);
	display:inline-block;
}

-->
</style>
<script>
        function validarCotizacion(){
            if($('#id_cliente').val()!=0)
                return false;
            else return true;
        }
</script>
</head>

    <body onLoad="MM_preloadImages('images/cerrar_r.jpg','images/icono_comentarios_r.png','images/icono_versiones_r.png','images/icono_tareas_r.png','images/icono_archivos_r.png','icono_historial_r.png')" onUnload="return validarCotizacion();">
<form id="form1" name="form1" method="post" action="">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="max-width:970px;margin-left: 5px;">
          <tr>
            <td width="785" valign="top"><div style="overflow:hidden; -webkit-overflow-scrolling:touch !important;">
            
                    
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div align="left" class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="16" /></div></td>
                <td width="170" rowspan="3">
                <img src="images/flecha_izq.jpg" alt="" 
                    onmouseover="this.src='images/flecha_izq_r.jpg'" onMouseOut="this.src='images/flecha_izq.jpg'" 
                    style="cursor:pointer" onClick="backNext(<? echo $limitBack;?>)"/>
                <img src="images/flecha_der.jpg" alt="" 
                    onmouseover="this.src='images/flecha_der_r.jpg'"  onmouseout="this.src='images/flecha_der.jpg'" 
                    style="cursor:pointer" onClick="backNext(<? echo $limitNext;?>)"/>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="169"><div align="left"><img src="images/tit_cotizaciones.jpg" alt="" width="221" height="28" /></div></td>
                <td width="447" align="right"><span class="texto_chico_gris">
                  
                  
                <? 
                if(isset($_SESSION['cotizacion'] )) {
					if($_SESSION['cotizacion']->enviada_cliente_en_fecha!="" && $_SESSION['cotizacion']->enviada_cliente_en_fecha!="0000-00-00")
	                    echo  $_SESSION['cotizacion']->enviada_cliente_en_fecha;
                    echo " FOLIO: ".$_SESSION['cotizacion'] ->id;
				} else {?>
                <i>guarda cotizacion para ver folio</i>
                <? }?>
  <img src="images/spacer.gif" alt="" width="20" height="16" />
                <? if(intval($_SESSION['cotizacion']->es_version)){?>
                <input name="reestablecerCotizacion" type="submit" class="texto_info_negro" id="reestablecerCotizacion" value="Reestablecer Cotización" />
                <? } ?>
                </span></td>
              </tr>
              <tr>
                <td><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><table width="100%" bgcolor="#F5F5F5" border="0" align="center" cellpadding="2" cellspacing="0" style="padding:10px; ">
                  <tr>
                    <th width="66" align="right" class="texto_info_negro">Cliente</th>
                    <td width="214"><span class="style5">
                      <select name="id_cliente" class="texto_info_negro_forma" id="id_cliente" style="width:200px" <? echo $esLectura;?>  onblur="" >
                        <option value="">Nombre del Cliente</option>
                        <?php
    $consulta  = "SELECT Clientes.id, Clientes.id_cartera,Clientes.alias,Clientes.codigo
	FROM Clientes inner join CarteraClientes_Usuarios on Clientes.id_cartera=CarteraClientes_Usuarios.id_cartera_clientes where CarteraClientes_Usuarios.id_usuario=".$id_usuario." ORDER BY Clientes.alias";
	//echo"$consulta";
    $resultado_clientes= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
    while($array=mysql_fetch_assoc($resultado_clientes)) {
        ?>
                        <option <? if( $_SESSION['cotizacion'] ->id_cliente == $array['id'] ) echo 'selected';?>
            value="<? echo $array['id'];?>"><? echo $array['alias']." (".$array['codigo'].")";?></option>
                        <?
    }
    ?>
                      </select>
                    </span></td>
                    <th align="right" class="texto_info_negro">Idioma</th>
                    <td class="texto_info_negro">
                    
                        <label>
                        <input <? echo $esLectura;?> name="idioma" type="radio" id="idioma_ESP" value="ESP" <? if($_SESSION['cotizacion'] ->idioma == 'ESP' || $_SESSION['cotizacion'] ->idioma=="") echo "checked";?> onChange="changeLanguage(this.value)" />Espa&ntilde;ol</label>
                        <span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span>
                        <label><input <? echo $esLectura;?> type="radio" name="idioma" value="ENG" id="idioma_ING" <? if($_SESSION['cotizacion'] ->idioma == 'ENG') echo "checked";?> onChange="changeLanguage(this.value)" />Ingl&eacute;s</label>
                    
                    </td>
                    <th align="right" class="texto_info_negro">Moneda</th>
                    <td class="texto_info_negro">
                    <label><input name="tipo_moneda" type="radio" id="tipo_moneda_mx" value="0" <? if($_SESSION['cotizacion'] ->tipo_moneda == 0) echo "checked";?> onChange="changeCurrency(this.value)" <? echo $esLectura;?>/>MX</label><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span><input type="radio" name="tipo_moneda" value="1" id="tipo_moneda_usa" <? if($_SESSION['cotizacion'] ->tipo_moneda == 1) echo "checked";?> onChange="changeCurrency(this.value)" <? echo $esLectura;?> />
                        USA(<? echo $_SESSION['dollar']?>)</label>
                        
                    </td>
                  </tr>
                  <tr>
                    <th align="right" class="texto_info_negro">Atención</th>
                    <td><input <? echo $esLectura;?> name="atencion" type="text" class="texto_info_negro_forma" id="atencion" value="<? echo $_SESSION['cotizacion'] ->atencion;?>" size="25" maxlength="100" onBlur="" /></td>
                    <th align="right" class="texto_info_negro">Referencia</th>
                    <td><input <? echo $esLectura;?>  name="referencia" type="text" class="texto_info_negro_forma" id="referencia" value="<? echo $_SESSION['cotizacion'] ->referencia;?>" size="25" maxlength="100" onBlur="" /></td>
                    <th align="right" class="texto_info_negro">Vigencia</th>
                    <td><input <? echo $esLectura;?>  name="vigencia" type="text" class="texto_info_negro_forma" id="vigencia" value="<? echo $_SESSION['cotizacion'] ->vigencia;?>" size="25" maxlength="100" onBlur="" /></td>
                  </tr>
                  <tr>
                    <th align="right" class="texto_info_negro"><div align="left">Prioridad</div></th>
                    <td><select name="id_prioridad" class="texto_info_negro_forma" id="id_prioridad" style="width:200px" <? echo $esLectura;?>  onblur="" >
                      <option value="0">- -</option>
                      <option value="1"
        <? if($_SESSION['cotizacion'] ->prioridad == 1) echo "selected";?>
        >Petici&oacute;n de una requisici&oacute;n</option>
                      <option value="2"
        <? if($_SESSION['cotizacion'] ->prioridad == 2) echo "selected";?>
        >Presupuesto o requerimiento futuro</option>
                      <option value="3"
        <? if($_SESSION['cotizacion'] ->prioridad == 3) echo "selected";?>
        >Sugerencia m&iacute;a</option>
                    </select></td>
                    <th align="right" class="texto_info_negro">Estatus</th>
                    <td><span class="style52">
                      <select name="id_estatus" class="texto_info_negro_forma" id="id_estatus"  <? echo $esLectura;?> onBlur="" >
                        <?php
	    $consulta  = "SELECT * FROM EstatusCotizaciones WHERE 1";
        $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_estatus)>=1){
            while($array=mysql_fetch_assoc($resultado_estatus)) {
                ?>
                        <option <? if($_SESSION['cotizacion'] ->id_estatus==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                        <?
            }
        }
     
		  ?>
                      </select>
                    </span></td>
                    <td class="texto_info_negro">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              
              <tr>
                <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="3" align="right">
                    
            <div  style="padding:0px 10px 0px 10px; background-color:#F5F5F5; width:880" class="texto_info_negro"><span class="texto_chico_gris">
              <span class="texto_info_negro" style="padding:0px 10px 0px 10px; background-color:#F5F5F5; width:880">
              <input <? echo $esLectura;?> style="float:left" name="Agregar" type="button" id="Agregar" value="Buscar producto" 
                                                     onclick="if(autoSaveCotizacion()) window.location = 'seleccionar_productos_cotizacion.php'; " class="texto_info_negro" />
              </span>
              <input <? echo $esLectura;?> style="float:left" type="button" class="texto_info_negro" id="Agregar5" onClick="if(autoSaveCotizacion()) abrir('cambia_producto_especial.php');" 
                                               value="Agregar Especial"/>
            </span>Agregar producto por código
              <input <? echo $esLectura;?> name="agregar_producto_codigo" type="text" class="texto_info_negro_forma" id="agregar_producto_codigo" size="25" maxlength="100" onBlur="" />
              <input <? echo $esLectura;?> type="submit" name="agregarProductoPorCodigo" class="texto_info_negro" id="Agregar2" onClick="return autoSaveCotizacion();" value="Agregar"/>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                      <tr>
                        <td width="18" class="texto_info_negro">&nbsp;</td>
                        <th align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">#</th>
                        <th width="500" bgcolor="#E3E3E3" class="texto_info_negro">Nombre</th>
                        <th width="30" bgcolor="#E3E3E3" class="texto_info_negro"><!--Proveedor-->Stock</th>
                        
                        <th width="100" colspan="2" bgcolor="#E3E3E3" class="texto_info_negro">Cant</th>
                        <th bgcolor="#E3E3E3" class="texto_info_negro">Precio</th>
                        <th width="60" bgcolor="#E3E3E3" class="texto_info_negro">Desc. %</th>
                        <th width="60" bgcolor="#E3E3E3" class="texto_info_negro">Subtotal</th>
                        <th width="20" bgcolor="#E3E3E3" class="texto_info_negro">Notas                
<? //print_r( $_SESSION['cotizacion'] -> productos );die?></th>
                  </tr>
                <?	  
                


if( count( $_SESSION['cotizacion'] -> productos ) > 0){
  $color ="#CCCCCC";
	$count=1;
        /*
         * LOS PRODUCTOS QUE ESTÁN EN LA COTIZACIÓN Y CARRITO GUARDAN EN PRECIO
         * EL VALOR ACTUAL PARA LA COTIZACIÓN, ES DECIR, CONVERTIDO A MXN O 
         * A USD SEGÚN EL CASO
         */
        $color = "";
	foreach ($_SESSION['cotizacion'] -> productos as $n => $producto) {?>
                      <tr class="texto_info_negro_c" bgcolor="<? echo $color;?>">
                        <td bgcolor="#FFFFFF" ><div align="right"><a href="#" class="iframe" onClick="eliminarProducto(<? echo $n;?>);">
                                    <img src="images/cerrar.jpg" alt="" name="Image86" width="17" height="16" border="0" id="Image<? echo $count;?>" 
                                         onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<? echo $count;?>','','images/cerrar_r.jpg',1)"  /></a></div></td>
                        <td align="center" valign="middle"  bgcolor="<? echo $color;?>"><strong><? echo $producto->partida;?></strong></td>
                        <td ><div align="left">
                          <? if($producto->archivo_ficha_tecnica!=""){?>
                              <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank">
                              <img src="images/pdf.ico" alt="pdf" width="21" height="21" border="0" />
                              </a>
                          <? } ?>
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
	?><a href="cambia_atributo.php?<? echo "attrName=$attrName&id=$producto->id";?>" onClick="" class="texto_info_negro_c iframe"> 
        Sin descripci&oacute;n <img src="images/warning.png" alt="" name="warning" width="16" height="16" id="warning"/></a><?
} else { 
	?><a href="<? echo $producto->id_proveedor == 8 ? "cambia_producto_especial.php" : "cambia_producto.php";	
    echo "?id=$producto->id";?>" onClick="" class="texto_info_negro_c iframe" style="line-height: 1;"> <? echo $_SESSION['cotizacion'] ->idioma == 'ESP' ? "$producto->nombre ($producto->codigo_interno)":"$producto->descripcion ($producto->codigo_interno)"; ?> </a>
                          <? }//END IF conDescr?>
                          <input type="hidden" id="conDescripcion<? echo $count;?>" value="<? echo $conDescr;?>" />
                          </div></td>
                        <td ><div align="center"><span class="texto_contenido">
                          <?
//        echo $producto->nombre_proveedor;
        echo $producto->stock;
    ?><input type="hidden" id="numProvee<? echo $count;?>" value="<? echo $producto->id_proveedor?>" />
                          </span></div></td>
                        <td ><div align="center" >
                          <input <? echo $esLectura;?> name="cantidad<? echo $n;?>" type="text" class="texto_info_negro numberTiny" id="cantidad<? echo $count?>" 
                          onChange="if(checkIfNumber(this)){checkDescuento(<? echo $count;?>);setViewCurrency('subtotal<? echo $count;?>','subtotalView<? echo $count;?>'); setViewCurrency('precio_unitario_v<? echo $count;?>','precioVenta<? echo $count;?>');}" 
                          value="<? echo $producto->cantidad;?>" size="5" maxlength="5" /></div></td>
                        <td width="10" ><?
						if($producto->unidad_metrica!=""){?>
                          <span style="font-size: xx-small"><? echo $producto->unidad_metrica;?></span>
                          <input type="hidden" id="conUnidadMetrica<? echo $count;?>" value="si" />
                          <?                        
                        } else { ?>
                          <a href="cambia_um.php?attrName=unidad_metrica<? echo "&id=$producto->id";?>" class="iframe"> <img src="images/warning.png" alt="agregar unidad metrica" name="warning" width="16" height="16" id="warning2" title="agregar unidad metrica"/>
                          <input type="hidden" id="conUnidadMetrica<? echo $count;?>" value="no" />
                          </a>
                        <? }   ?></td>
                        <td ><div align="center">
                          <input <? echo $esLectura;?> name="precio_unitario_v[]" type="hidden" class="numberTiny" id="precio_unitario_v<? echo $count?>" 
                                                value="<? echo round((((1-($producto->descuento))*$producto->precio)+$producto->recargo),2);?>" size="6" 
                                                maxlength="3" readonly="readonly" />
                          <input <? echo $esLectura;?> name="precio_unitario[]" type="hidden" class="numberTiny" id="precio_unitario<? echo $count?>" 
                                                         value="<? echo round($producto->precio,2);?>" size="6" maxlength="3" readonly="readonly" />
                          <span name="precioVenta[]" class="texto_info_negro numberTiny" id="precioVenta<? echo $count;?>"><? echo getFormatedNumberForMoney(( ((1 - $producto->descuento ) * $producto->precio) + $producto->recargo ) );?>
                          </span>
                          <span style="font-size: xx-small"><?
                          if($producto->tipo_moneda_usa != $_SESSION['cotizacion']->tipo_moneda){
                              if($producto->tipo_moneda_usa) echo "USD";
                              else echo "MXN";
                          }
                          ?></span></div></td>
                        
                        <td>
                            <div align="center">
                                <input <? echo $esLectura;?> name="descuento<? echo $n;?>" type="number" class="texto_info_negro numberTiny" 
                                        onChange="checkDescuento(<? echo $count;?>);setViewCurrency('subtotal<? echo $count;?>','subtotalView<? echo $count;?>'); setViewCurrency('precio_unitario_v<? echo $count;?>','precioVenta<? echo $count;?>');"  
                                        id="descuento<? echo $count?>" value="<? echo $producto->descuento*100;?>" size="6" maxlength="3" style="width:40px"/>
                                <input <? echo $esLectura;?> name="recargo[]" type="hidden" class="texto_info_negro numberTiny" id="recargo<? echo $count?>" 
                                                             value="<? echo  $producto->recargo;?>"/>
                          </div></td>
                        <td ><div align="center">
                          
                          <span name="subtotalView[]" class="texto_info_negro numberTiny" id="subtotalView<? echo $count;?>"><? echo getFormatedNumberForMoney((((1-($producto->descuento))*$producto->precio)+$producto->recargo) * $producto->cantidad);?></span>
                                <input <? echo $esLectura;?> name="subtotal[]" type="hidden" class="texto_info_negro numberTiny" id="subtotal<? echo $count;?>" 
                                        value="<? echo round(((((1-($producto->descuento))*$producto->precio)+$producto->recargo) * $producto->cantidad),2);?>"/>
                          </div></td>
                        <td align="center">
                            <img src="images/detalles.png" alt="" name="nota<? echo $producto->id;?>" width="25" height="25" id="nota<? echo $producto->id;?>" style="cursor: pointer;<? echo empty($producto->comentario)?"-webkit-filter: invert(100%);":"";?>" 
                                 onclick="abrir('cambia_nota_producto.php?pos=<? echo $n;?>',false)"/>
                            
<input name="comentarioProducto<? echo $n;?>" type="hidden"
                              id="comentarioProducto<? echo $count?>" value="<? echo htmlspecialchars($producto->comentario);?>"/></td>
                  </tr>
                  <?
				  if($color == "#F5F5F5")
				  	$color = "";
				else $color = "#F5F5F5";
			   $count=$count+1;
	}
        
}
	
		?>
              </table>
                    
                    <!---------->
                </td>
              </tr>
              <tr>
                <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              
   <? if(!$vistaLectura){?>
              <tr>
                <td colspan="3" align="left"><input <? echo $esLectura;?> type="button" class="texto_info_negro" id="Agregar3" onClick="autoSaveCotizacion();abrir('ordenar_partidas.php',false);" 
                                               value="Ordenar Partidas"/></td>
              </tr>
              <tr>
                <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="3" align="left"><table width="99%" border="0" align="center" cellpadding="2" cellspacing="2" style="margin:10px; ">
                  <tr>
                    <th width="25%" align="center" class="texto_info_negro">Libre Abordo</th>
                    <th width="25%" align="center" class="texto_info_negro">T&eacute;rminos de entrega</th>
                    <th width="25%" align="center" class="texto_info_negro">Otros</th>
                  </tr>
                  <tr>
                    <td width="25%" align="center" class="texto_info_negro"><span class="style8">
                      <textarea name="LAB"  <? echo $esLectura;?>  rows="2" class="texto_info_negro" id="LAB" style="width:95%"><? 
                      echo stripslashes($_SESSION['cotizacion'] ->LAB);
                      ?></textarea>
                    </span></td>
                    <td width="25%" align="center"><span class="style8">
                      <textarea name="terminos_entrega" rows="2" onBlur=""   <? echo $esLectura;?> class="texto_info_negro" id="terminos_entrega" style="width:95%"><? echo stripslashes($_SESSION['cotizacion'] ->terminos_entrega);?></textarea>
                    </span></td>
                    <td width="25%" align="center" class="texto_info_negro"><span class="style5">
                      <textarea name="comentarioCotizacion"  onblur="" rows="2" class="texto_info_negro" <? echo $esLectura;?>  id="comentarioCotizacion" style="width:95%"><? echo stripslashes($_SESSION['cotizacion'] ->notas_adicionales);?></textarea>
                    </span></td>
                  </tr>
                </table>
                  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" style="margin:10px; ">
                    <tr>
                      <th width="25%" align="center" class="texto_info_negro">Condiciones de pago</th>
                    </tr>
                    <tr bgcolor="#F5F5F5">
                      <td width="25%" align="center" class="texto_info_negro">&nbsp;<?
        if(isset($_SESSION['cotizacion'] ->id_cliente)){
            $cliente = new Cliente();
            $cliente->get($_SESSION['cotizacion'] ->id_cliente);
            echo $cliente->condiciones_pago;
        } else {
            echo "<i>Guarda primero la cotización para ver las condiciones de pago</i>";
        }
        ?></td>
                    </tr>
                  </table>
                <p>&nbsp;</p></td>
              </tr>
              <? }?>
              <tr>
                <td colspan="3"><img src="images/spacer.gif" alt="" width="20" height="10" />
                  <table  width="450" border="0" align="center" cellpadding="2" cellspacing="2">
                    <tr>
                      <td colspan="3" bgcolor="#E91B25"><div align="center" class="texto_info_blanco">TOTAL</div></td>
                    </tr>
                    <tr>
                      <td align="center" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">
                      <input name="conIva"  type="checkbox" class="texto_info_negro" id="conIva" onClick="agregarIva(this)" value="1"  
                            <? echo $esLectura;?>
                            <?
                            if($_SESSION['cotizacion'] ->con_iva == 1 || !isset($_SESSION['cotizacion'] )) 
                                echo "checked";
                            ?> />IVA</div></td>
                      <td align="center" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Subtotal</div></td>
                      <td align="center" bgcolor="#E3E3E3" class="texto_info_negro">Total</td>
                    </tr>
                    <tr>
                      <td align="center" class="texto_info_negro"><div align="center">
                      
                        
                        <span class="texto_info_negro numberMedium" id="ivaView">$
                        <? if($_SESSION['cotizacion'] ->con_iva == 1 )
		echo getFormatedNumberForMoney($_SESSION['cotizacion'] ->iva);?></span>
                        <input <? echo $esLectura;?> name="iva" type="hidden" class="texto_info_negro numberMedium" id="iva" value="<? 
		if($_SESSION['cotizacion'] ->con_iva == 1 )
		echo round($_SESSION['cotizacion'] ->iva , 2);?>"/>
                      </div></td>
                      <td align="center" class="texto_info_negro">
                      
                      <span class="texto_info_negro numberMedium" id="subtotalView">$<? echo getFormatedNumberForMoney($_SESSION['cotizacion'] ->subtotal);?></span>
                      
                      <input <? echo $esLectura;?> name="subtotal" type="hidden" class="texto_info_negro numberMedium" id="subtotal" value="<? echo round($_SESSION['cotizacion'] ->subtotal,2);?>"/>
                      
                      </td>
                      <td align="center" class="texto_info_negro">
                      
                      <span class=" texto_info_negro numberMedium" id="totalView"> $<? echo $_SESSION['cotizacion'] ->con_iva > 0 ? getFormatedNumberForMoney($_SESSION['cotizacion'] ->total) :  getFormatedNumberForMoney($_SESSION['cotizacion'] ->subtotal);?></span>
                      
                      <input name="total" type="hidden" class=" texto_info_negro numberMedium" id="total" value="<? echo $_SESSION['cotizacion'] ->con_iva > 0 ? round($_SESSION['cotizacion'] ->total,2) :  round($_SESSION['cotizacion'] ->subtotal,2);?>" <? echo $esLectura;?>/></td>
                    </tr>
                  </table>
                <p>&nbsp;</p></td>
              </tr>
              <tr>
                <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              
   <? if(!$vistaLectura){?>
              <tr>
                <td colspan="3" align="center">
                    
                  <input <? echo $esLectura;?> name="crearVersion" type="submit" class="texto_info_negro " id="crearVersion" value="Crear Version" onClick="return autoSaveCotizacion();"/>
                  <input <? echo $esLectura;?> name="guardarCotizacion" type="button" class="texto_info_negro " onClick="autoSaveCotizacion();" id="guardarCotizacion"  value="Guardar" />
                  <input name="previsualizar" type="submit" id="previsualizar" value="previsualizar" onClick="return validar();"  class="texto_info_negro"/>
                </td>
              </tr>
              <tr>
                <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="3" align="center"><input <? echo $esLectura;?> name="guardarCotizacionYNuevo" type="submit" class="texto_info_negro " onClick="autoSaveCotizacion();" id="guardarCotizacionYNuevo"  value="Guardar y Nuevo" /></td>
              </tr>
              <tr>
                <td colspan="3" align="left"><div align="center">
                  <blockquote>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                  </blockquote>
                </div></td>
              </tr>
              <? }?>
            </table></div></td>
            <td width="7" valign="top"><img src="images/sombra_productos_gris.jpg" alt="enviar" width="7" height="553" /></td>
            <td valign="top" width="40" bgcolor="#808080">
            <div style="" class="rotateButton texto_info_negro">
    <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "enviar_cotizacion.php";?>" class="texto_info_negro menuLateral iframe" >
    <img src="images/icono_enviar.png" alt="enviar" title="enviar" class="imgIco"/></a>
    
    <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "historial_correo.php";?>" class="texto_info_negro menuLateral iframe" 
    onmouseout="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image3','','images/icono_historial_r.png',1)"> <img src="images/icono_historial.png" alt="historial de correo" title="historial de correo" class="imgIco" name="Image3" width="26" height="20" border="0" id="Image3" />
    </a>
    
    <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "adm_cotizaciones_versiones.php";?>" class="texto_info_negro menuLateral iframe" 
    onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image22','','images/icono_versiones_r.png',1)">
    <img src="images/icono_versiones.png" alt="versiones" title="versiones" class="imgIco" name="Image22" width="26" height="20" border="0" id="Image22" /><!--VERSIONES-->
    </a>
    

    <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "adm_tareas.php?fromCotizacion=1";?>" class="texto_info_negro menuLateral iframeMini" 
    onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image21','','images/icono_tareas_r.png',1)">
    <? if(count($_SESSION['cotizacion']->getTareas())){ ?>
        <div id="totalTareas" style="float: left;font-size: smaller;color: #FFF;margin-right: -10px;background: #000000;padding: 0px 1px;border-radius: 10px;">
    <strong><?
    echo count($_SESSION['cotizacion']->getTareas());?>
    </strong></div>
        <? }?>
        <img src="images/icono_tareas.png"      alt="tareas" title="tareas" class="imgIco" name="Image1" width="26" height="20" border="0" id="Image21" />
    </a>
    
    <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "comentarios_cotizacion.php?test=1&id=".$_SESSION['cotizacion'] ->id;?>" 
       class="texto_info_negro menuLateral iframeMini" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image23','','images/icono_comentarios_r.png',1)">
<? if(count($_SESSION['cotizacion']->getComentarios())){ ?>
        <div style="float: left;font-size: smaller;color: #FFF;margin-right: -10px;background: #000000;padding: 0px 1px;border-radius: 10px;">
    <strong><?
    echo count($_SESSION['cotizacion']->getComentarios());?>
    </strong></div>
        <? }?>
<img src="images/icono_comentarios.png" alt="comentarios" title="comentarios" class="imgIco" name="Image23" width="26" height="20" border="0" id="Image23" /><!--COMENTARIOS--></a>
       
    <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "archivos_cotizacion.php";?>" class="texto_info_negro menuLateral iframeMini" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image24','','images/icono_archivos_r.png',1)">
        <img src="images/icono_archivos.png" alt="archivos" title="archivos" name="Image24" width="26" height="20" border="0" class="imgIco" id="Image24" />	<!--ARCHIVOS--></a></div>
            </td>
          </tr>
          
  </table>
</form>
<script>
if(<? echo $guardarFromDirectlyAdd?>){
        $.colorbox({iframe:true,href:'seleccionar_origen_producto.php?id='+<? echo $prod['id']?>+"&guardarFromDirectlyAdd=true",
            width:"400", 
            height:"553",
            transition:"fade", 
            scrolling:false, opacity:0.5}
        );
    }
</script>
<form action="" method="post" name="formBorrarProductoCarrito" id="formBorrarProductoCarrito"></form>
</body>
</html>