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
    include_once "checar_sesion_admin.php";
    include_once "coneccion.php";
    include_once "checar_acceso.php";
    include_once "getFormatedNumberForMoney.php";
    include_once 'functions_cotizacion.php';
    include_once "checar_permisos.php";
    ini_set("session.cookie_lifetime","3600");
    ini_set("session.gc_maxlifetime","3600");
    session_start();
    checarAcceso($_SESSION['accesos']['soporte']);
    $valor_moneda = $_SESSION['dollar'];
    $vistaLectura = !tienePermisoEscritura($_SESSION['accesos']['administrador'],$_SESSION['accesos']['supervisor'],$_SESSION['accesos']['vendedor25'],$_SESSION['accesos']['vendedor'], $_SESSION['accesos']['soporte']);
    $borrar=0;
    if($vistaLectura)
    {
        $esLectura = "disabled";
    } 
    
   
    /*
     * Editar cotizacion y eliminar cotizacion
     * Editar recibe por POST idCotizacionEditar,id_version,limitInicio
     * Eliminar recibidad con GET idCotizacionEditar,idVersion
    */  
    if( isset($_REQUEST['idCotizacionEditar']) && isset($_REQUEST['idVersion']))
    {
    
        if($_GET['borrar']=='true')
        {
            $cotizacionBorrar = new Cotizacion();
            $cotizacionBorrar -> get( $_REQUEST['idCotizacionEditar'] , $_REQUEST['idVersion'] );
        
            if( $_GET['onlyVersion']=='true' )
            {
                $cotizacionBorrar -> deleteOneVersion();    
                echo "<script>parent.parent.location.reload();</script>";
            } 
            else
            {
                $cotizacionBorrar -> delete();
                unset( $_SESSION['cotizacion'] );
                echo "<script>parent.location.reload();</script>";
            }
        } 
        else
        {
            $cotizacion = new Cotizacion();
            /* obtiene exclusivamente los datos de la cotizacion, es decir, no obtiene los productos.*/
            $cotizacion -> get( $_REQUEST['idCotizacionEditar'] , $_REQUEST['idVersion'] );
            /* obtiene los productos.*/
            $cotizacion -> setCarritoFromCotizacion();

            $_SESSION['cotizacion'] = $cotizacion;
            $u = $_SESSION['cotizacion']->subtotal;
        }
    }
   
    if(isset($_POST['crearVersion']))
    {
            
        if(!$_SESSION['cotizacion'] ->createVersion())
        {
            echo "<script>alert('No se ha creado la cotizacion');</script>";
        }
    }

    //checar si tiene o no iva.
    if($_POST['con_iva']!="" || $_POST['con_iva']!=NULL)
    {
        if($_POST['con_iva']=='true')
        {
            $_SESSION['cotizacion']->con_iva=true;
        }   
        else
        {
            $_SESSION['cotizacion']->con_iva=false;
        }
               
        guardarCotizacion();//falla
        $_SESSION['cotizacion']->subtotal=$_POST['subtotal'];
        $_SESSION['cotizacion']->total=$_POST['total'];
        $_SESSION['cotizacion']->iva=$_POST['iva'];
        saveCotizacionOnDB();
    }

    if($_POST['changeCurrencyTo']!="")
    {
        if(isset($_SESSION['carrito']))
        {
            include_once 'changeTipoMoneda.php'; 
            $valor_moneda = $_SESSION['dollar'];
            $tipo_moneda = $_POST['changeCurrencyTo'];      
        } 
        guardarCotizacion();
        saveCotizacionOnDB();
    }

    if($_POST['changeLanguageTo']!="")
    {
        guardarCotizacion();
        saveCotizacionOnDB();
    }
    //echo "<script>alert('".$_POST['posicion_borrar']."')</script>";
    if($_POST['posicion_borrar']!="")
    {
        
        unset( $_SESSION['carrito'][ $_POST['posicion_borrar'] ] );
        unset( $_SESSION['cotizacion']->productos[ $_POST['posicion_borrar'] ] );
        $count = 1;
        foreach ($_SESSION['cotizacion'] -> productos as $n => $producto)
        {
            $producto->partida = $count;
            $count++;
        }
        $count = 1;
        foreach ($_SESSION['carrito']as $n => $producto)
        {
            $producto->partida = $count;
            echo "<script>alert('$con')</script>";
            $count++;
        }
        //guardarCotizacion();
        $borrar=1;
        //saveCotizacionOnDB();
        
    }

    if($_POST['posicion_borrar_archivo']!="")
    {
        $archivo_quitar = $_SESSION['cotizacion'] -> archivos[ $_POST['posicion_borrar_archivo'] ];
        if( $_SESSION['cotizacion']->borrarArchivo($archivo_quitar))
        {
            unset( $_SESSION['cotizacion']->archivos[ $_POST['posicion_borrar_archivo'] ] );
        }
            
    }

    if($_POST['id_modificar_producto'] != "")
    {
        $id_modificar_producto = $_POST['id_modificar_producto'];
        guardarCotizacion();
        saveCotizacionOnDB();
        
        if($_POST['id_proveedor'] == 8) 
        {
            $modificarProductoBox = "cambia_producto_especial.php?id=$id_modificar_producto";
        }  
        else
        {
            $modificarProductoBox = "cambia_producto.php?id=$id_modificar_producto";
        }
        echo $modificarProductoBox;
    }

    if( $_POST['previsualizar']!="")
    {
        if( $_POST['previsualizar']!="" )
        {
            echo"<script>window.open(\"precotizacion.php\");</script>";
        }   
    }

    if(isset($_POST['guardarCotizacionYNuevo']))
    {
        unset($_SESSION['cotizacion']);
        unset($_SESSION['carrito']);
        echo "<script> window.location.'generar_cotizacion_p.php'; </script>";
    }

    function clearURIVariables()
    {
        $location = explode('?',$_SERVER['REQUEST_URI']);
        echo "<script> window.location = '<? echo $location[0];?>'; </script>";
    }

    if(isset($_REQUEST['agregarProductoPorCodigo']))
    {
        $query = "SELECT id,precio FROM Productos WHERE Productos.codigo_interno LIKE '{$_REQUEST['agregar_producto_codigo']}' and Productos.id_proveedor<>8 and Productos.id_proveedor<>7 and Productos.id_proveedor<>7";
        $result = mysql_query($query) or print(mysql_error());
        
        if(mysql_num_rows($result)==1)
        {
            $prod = mysql_fetch_assoc($result);
            if(intval($prod['precio'])==0)
            {
                $guardarFromDirectlyAdd='true';
            }
            else
            {
                echo "<script>window.location = 'agregar_carrito.php?id=".$prod['id']."&cantidad=1&backTo=generar_cotizacion_p.php';</script>;";
            }
        } 
        else
        {


            $_SESSION['buscadorCotizaciones']['familia']='';
            $_SESSION['buscadorCotizaciones']['proveedor']='';
            $_SESSION['buscadorCotizaciones']['nombre']='';
            $_SESSION['buscadorCotizaciones']['codigo_buscar'] = $_POST['agregar_producto_codigo'];
            echo "<script>window.location = 'seleccionar_productos_cotizacion.php?submit=".$_POST['agregar_producto_codigo']."&contacto=".$_POST['contacto']."';</script>";
        }
    }

/* PARA LAS FLECHAS NEXT Y BACK */
if(isset($_REQUEST['limitInicio'])) $_SESSION['limitInicio'] = intval($_REQUEST['limitInicio']);
if( isset($_REQUEST['limit'])){
    


    //whereInnerJoin_NextBack_cotizaciones is set on adm_cotizacions when the filter is applied
    $consulta  = "SELECT Cotizaciones.id, CONCAT(alias,' (' ,codigo,')') AS nombre_cliente, 
                        IF( Cotizaciones.tipo_moneda = 1, Cotizaciones.total * ".$_SESSION['dollar'].", Cotizaciones.total) AS total,
                        Usuarios.nombre AS usuarioAsignado, 
                        EstatusCotizaciones.nombre AS estatus, EstatusCotizaciones.id AS id_estatus, 
                        Cotizaciones.id_version, Cotizaciones.prioridad, Cotizaciones.tipo_moneda, Contactos.nombre_contacto
                FROM Cotizaciones 
                {$_SESSION['whereInnerJoin_NextBack_cotizaciones']}
                LIMIT {$_REQUEST['limit']},1";
			//echo"$consulta";
    if(intval($_REQUEST['limit'])<0) {    
        ?><script>alert('Inicio de la lista de cotizaciones buscadas');</script><?  
        $limitNext=1;
        $limitBack=-1;
    } else {
        $limitNext = intval($_REQUEST['limit'])+1;
        $limitBack = intval($_REQUEST['limit'])-1;
        $result = mysql_query($consulta) or print("Error en buscador". mysql_error());
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

if(isset($_POST['genera']))
if($_POST['genera']=="Generar PDF"){
	date_default_timezone_set('America/Chihuahua');
	
	if(isset($_SESSION['cotizacion'])){
		include_once 'mailCotizacion.php';
		require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
		require_once ("dompdf/include/style.cls.php");
        $cliente = new Cliente();
		$cliente -> get($_SESSION['cotizacion'] ->id_cliente);
        $cotizacion = new Cotizacion();
        $cotizacion = $_SESSION['cotizacion'];
		$cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
		$html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false);
			$pdf = new DOMPDF();
			$pdf ->load_html($html);
			$pdf ->render();
			$archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
			file_put_contents($archivo,$pdf->output());
			$pdf ->stream("cotizacion_".$cotizacion->id.".pdf");
			
	}
}


?>
    
    
<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cotizaciones </title>
<style type="text/css">

body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: -10px;
	background-color: #FFFFFF;
	margin-top: -10px;
}

.style52 {font-size: 12}
.style52 {font-size: 12}
.style511 {font-size: 18}
.style511 {font-size: 18}

.style5 {font-size: 12}
.style51 {font-size: 18}

.imgIco{margin-bottom: 10px;}
.apuntador{cursor: pointer;}
.rotateButton{
	top: 5.5em;
width: 4em;
right: -.5em;
position: fixed;
display: inline-block;
}

.menuLateral {
	outline: none;
	cursor: pointer;
	padding: .5em 1em .55em;
}
textarea{resize:none;}
</style>

  

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="ajaxSubmit.js"></script> 
<script src="colorbox/jquery.colorbox-min.js"></script>
<!-- <script  src="convertFloatToCurrencyFormat.js"></script> -->
<script>
function setViewCurrency(objSrcId,objTargetid){
    var number = parseFloat(document.getElementById(objSrcId).value);
    if(!isNaN(number)){
        number = number.toFixed(4);
        var thousands  = parseInt(number/1000);
        var hundreds = number - thousands*1000;
        
        var pre="";
        if(hundreds<100 && thousands>=1)
        {
            pre="0";
             
        }
            
	
       var result = "$0";
        if(thousands >= 1){
            
            result ="$" + thousands + "," + pre + hundreds.toFixed(2);
        } else{
            result = "$" + pre + hundreds.toFixed(2);  
        } 
        

        document.getElementById(objTargetid).innerHTML = result;
    }
}

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
	
	
    
    function ModificaProducto(productocotizacion,n) {
        console.log(productocotizacion);
        var des=$('#descuento'+n).val();
        console.log(des);
        var limite_descuento = parseFloat(<? echo $_SESSION['usuario']->limite_descuento?>) * 100;
        var precioAnterior = parseFloat( document.getElementById('subtotal' + n ).value );
        var numProveedor=document.getElementById('numProvee' + n ).value;
        if(numProveedor==20 && limite_descuento!=100)
            limite_descuento=parseFloat(0.52) * 100;
		if(numProveedor==18 && limite_descuento!=100)
            limite_descuento=parseFloat(0.32) * 100;
        if(des<0)
        {
            //document.getElementById('descuento' + n).value=Math.abs(obj.value);
            //obj.value=Math.abs(obj.value);
            $('#descuento'+n).val(Math.abs(des));
            des=Math.abs($('#descuento'+n).val());
        }
			
        if(des > limite_descuento){
        } 
        else {
            des=des/100;
            var producto={productocotizacion:productocotizacion,descuento:des,cantidad:$('#cantidad'+n).val(),precio:$('#precio_unitario'+n).val(),comentario:$('#comentarioProducto'+n).val()};
            //console.log(producto);
            $.ajax({
                method: "POST",
                url: "Modificar_Producto_Cotizacion.php",
                data: producto,
                beforeSend: function(){
                }
            })
            .done(function(data) {
                console.log(data);
                if (data==1){
                    //location.reload(true);
                }
            });
        }
        
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
        CalcularIva();
		var t=document.getElementById('id_cliente').value;
            $.colorbox({iframe:true,href:""+ir+t+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
	}
	function abrir24(ir){
		
            $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
	}
	function abrir22(ir){
            $.colorbox({iframe:true,href:""+ir+document.getElementById('id_cliente').value+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
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
        //var obj = document.getElementById('descuento' + n);
        var descuento = $('#descuento'+n).val();
        //console.log('descuento: '+descuento);
        //descuento = descuento*100;
        
        var limite_descuento = parseFloat(<? echo $_SESSION['usuario']->limite_descuento?>) * 100;
        var precioAnterior = parseFloat( document.getElementById('subtotal' + n ).value );
        console.log('anterior: '+precioAnterior);
        
        var numProveedor=document.getElementById('numProvee' + n ).value;
        if(numProveedor==20 && limite_descuento!=100)
            limite_descuento=parseFloat(0.52) * 100;
		if(numProveedor==18 && limite_descuento!=100)
            limite_descuento=parseFloat(0.32) * 100;
        if(descuento<0)
        {
            //document.getElementById('descuento' + n).value=Math.abs(obj.value);
            //obj.value=Math.abs(obj.value);
            $('#descuento'+n).val(Math.abs(descuento));
            descuento=Math.abs($('#descuento'+n).val());
        }
			
        if(descuento > limite_descuento){//limite_descuento){
            alert('Tu l\u00edmite de descuento es ' + limite_descuento  + '%');
            $('#descuento'+n).val(0);
            descuento=0
            var precioUnit = $('#precio_unitario'+n).val();
            var cantidad = $('#cantidad'+n).val();
            var recargo = $('#recargo'+n).val();
            var caldescuento=((precioUnit*1+recargo*1)*(descuento/100));//0
            var subtotalProducto =  ( ((precioUnit*1+recargo*1) - caldescuento*1) * cantidad ).toFixed(4);
            var precioVenta=((precioUnit*1+ recargo*1)-caldescuento*1).toFixed(4);
            $('#subtotal'+n).val(subtotalProducto);
			$('#precio_unitario_v'+n).val(precioVenta);
            setTotals( subtotalProducto , precioAnterior );
        } else {
            var precioUnit = $('#precio_unitario'+n).val();
            //console.log('precio unitario: '+precioUnit);
            
            var cantidad = $('#cantidad'+n).val();
            //console.log('cantidad: '+cantidad);
            var recargo = $('#recargo'+n).val();
            //console.log('recargo: '+recargo);
            var caldescuento= ((precioUnit*1+recargo*1)*(descuento/100));//0
            //console.log('caldescuento: '+caldescuento);
            var subtotalProducto = ( ((precioUnit*1+recargo*1) - caldescuento*1) * cantidad ).toFixed(4);
            //console.log('subtotalproducto: '+subtotalProducto);
            //var precioVenta=((precioUnit*1 - descuento*1 + recargo*1)).toFixed(4);
            var precioVenta=((precioUnit*1+ recargo*1)-caldescuento*1).toFixed(4);
            //console.log('precioVenta: '+precioVenta);
            $('#subtotal'+n).val(subtotalProducto);
			$('#precio_unitario_v'+n).val(precioVenta);
            setTotals( subtotalProducto , precioAnterior );
        }
    }
    function setTotals(subtotalProducto , precioAnterior){
        
        if(!isNaN(subtotalProducto) && !isNaN(precioAnterior))
        {
            //console.log('subtotal: '+subtotalProducto);
            var diferencia = subtotalProducto - precioAnterior;
           
            
            var subttl = parseFloat(document.getElementById('subtotal').value);
            //console.log('subtotal: '+subttl);
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
	window.location = 'generar_cotizacion_p.php';
	//$.fn.colorbox.close();
}
function agregarIva(obj){
    //obj = input conIva
    var form = document.getElementById('form1');
    var element = document.createElement('input');
    element.name = 'con_iva';
    element.type = 'hidden';
    element.value = obj.checked;//false o true
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
	//autoSaveCotizacion();
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
function agregarcontacto(){

	$.colorbox({iframe:true,href:"cambia_contacto3.php",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});	
		
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
    if(document.getElementById('id_cliente').value!=0 && document.getElementById('id_prioridad').value!=0 && document.getElementById('id_contacto').value!=0){
	var subtotal = $('#subtotal').val();
    var total = $('#total').val();
    var iva = $('#iva').val();
    var contacto = $('#id_contacto').val();
    $('#contacto').val(contacto);
    console.log($('#contacto').val());
    
    //console.log(subtotal);
    
    $("#form1").ajaxSubmit({
            url: 'saveCotizacion.php?sub='+subtotal+'&total='+total+'&iva='+iva+'&contacto='+contacto, 
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
    } else if(document.getElementById('id_contacto').value==0){
	 	alert('Selecciona Contacto');
        document.getElementById('id_contacto').focus();
        returnn = false;
		}
    return returnn;
}


function eliminarProducto(posicion_borrar,IdProducto,IdCotizacion){
        //console.log(Partida);
            var total = 0;
            var subtotal = 0;
            var iva = 0;
            if ($('#count')!=0) 
            {
                var count = parseInt($('#count').val())-1;
               // console.log('count'+count);
                for (i = 1; i <= count; i++)
                {
                    if ($('#subtotal'+i).length>0)//si existe
                    {
                        
                        subtotal = parseFloat($('#subtotal'+i).val());
                        console.log('subtotal: '+subtotal);
                        total = total + subtotal;
                        console.log('total: '+total);
                    }
                }
            }
            var c=posicion_borrar+1;
            total=total-parseFloat($('#subtotal'+c).val());
            console.log('total: '+total);
            //cuando tiene seleccionado el iva
                if($('#conIva').attr('checked'))
                {
                   
                    iva = total * 0.16;
                    console.log('iva: '+iva);
                    
                }

                //console.log('iva: '+iva);
                var total2 = total+iva;
                $('#subtotal').val(total);//input no se ve.
                $('#iva').val(iva);//input no se ve.
                $('#total').val(total2);//input no se ve.
                setViewCurrency('subtotal','subtotalView');
                setViewCurrency('iva','ivaView');
                setViewCurrency('total','totalView');
                
         $.ajax({
            method: "POST",
            url: "Eliminar_Producto_Cotizacion.php",
            data: {IdBorrar:IdProducto,IdCotizacion:IdCotizacion},
            beforeSend: function(){
            }
        })
        .done(function(data) {
            console.log(data);
            if (data==1){
                if(autoSaveCotizacion()==true)
                {
                    location.reload(true);
                }
                    
            }
            
        });
	}
function clearSessionCotizacion(){
    $("#form1").ajaxSubmit({
        url: 'clearSessionCotizacion.php', 
        type: 'post'
    });
}

        /* AGREGA A TODOS LOS INPUT Y TEXTAREA LA FUNCION DE AUTOGUARDADO*/
		
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
function borrar(id_version,id){
    if(confirm("Borrar\u00e1 la cotizaci\u00f3n y sus versiones. Desea continuar?")){
       // abrir('generar_cotizacion.php?idVersion=' + id_version + '&idCotizacionEditar=' + id + '&borrar=true');
		window.location="generar_cotizacion_p.php?idVersion=" + id_version + "&idCotizacionEditar=" + id + "&borrar=true";
    }
    
}
</script>

<style type="text/css">

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


</style>
<script>
        function validarCotizacion(){
           if($('#id_cliente').val()!=0)
			{
                
			    return false;
				
			}
            else
			{
			
			 return true;
			 }
        }

        //obtiene el subtotal de todos los productos, los suma y otiene el iva
		function CalcularIva() 
        {
            var total = 0;
            var subtotal = 0;
            var iva = 0;
            if ($('#count')!=0) 
            {
                var count = parseInt($('#count').val())-1;
               // console.log('count'+count);
                for (i = 1; i <= count; i++)
                {
                    if ($('#subtotal'+i).length>0)//si existe
                    {
                        
                        subtotal = parseFloat($('#subtotal'+i).val());
                        //console.log('subtotal: '+subtotal);
                        total = total + subtotal;
                        //console.log('total: '+total);
                    }
                }

                
               
                
               //cuando tiene seleccionado el iva
                if($('#conIva').attr('checked'))
                {
                   
                    iva = total * 0.16;
                }

                //console.log('iva: '+iva);
                var total2 = total+iva;
                $('#subtotal').val(total);//input no se ve.
                $('#iva').val(iva);//input no se ve.
                $('#total').val(total2);//input no se ve.
                setViewCurrency('subtotal','subtotalView');
                setViewCurrency('iva','ivaView');
                setViewCurrency('total','totalView');
                //$('#subtotalView').text('$'+total);//span que muestra subtotal.
                autoSaveCotizacion2();
            }
        }
		
		

</script>
</head>

    <body onLoad="MM_preloadImages('images/cerrar_r.jpg','images/icono_comentarios_r.png','images/icono_versiones_r.png','images/icono_tareas_r.png','images/icono_archivos_r.png','images/icono_historial_r.png')" onUnload="return validarCotizacion();">
        <form id="form1" name="form1" method="post" action="">
            <input type="hidden" value="<?echo $_SESSION['cotizacion']->contacto;?>" id="contacto" name="contacto"/>
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="max-width:970px;margin-left: 5px;">
                <tr>
                    <td width="785" valign="top">
                        <div style="overflow:hidden; -webkit-overflow-scrolling:touch !important;">
			                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <div align="left" class="texto_chico_gris">
                                            <img src="images/spacer.gif" alt="" width="20" height="16" />
                                        </div>
                                    </td>
                                    <td width="170" rowspan="3">
                                        <img src="images/flecha_izq.jpg" alt="" onmouseover="this.src='images/flecha_izq_r.jpg'" onMouseOut="this.src='images/flecha_izq.jpg'" style="cursor:pointer" onClick="backNext(<? echo $limitBack;?>)"/>
                                        <img src="images/flecha_der.jpg" alt="" onmouseover="this.src='images/flecha_der_r.jpg'" onmouseout="this.src='images/flecha_der.jpg'" style="cursor:pointer" onClick="backNext(<? echo $limitNext;?>)"/>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td>
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td width="169">
                                        <div align="left">
                                            <img src="images/tit_cotizaciones.jpg" alt="" width="221" height="28" />
                                        </div>
                                    </td>
                                    <td width="447" align="right">
                                        <span class="texto_chico_gris">   
                                            <? 
                                                if(isset($_SESSION['cotizacion'] ))
                                                {
                                                    if($_SESSION['cotizacion']->enviada_cliente_en_fecha!="" && $_SESSION['cotizacion']->enviada_cliente_en_fecha!="0000-00-00")
                                                    {
                                                        echo  $_SESSION['cotizacion']->enviada_cliente_en_fecha;
                                                    }    
                                                    echo " FOLIO: ".$_SESSION['cotizacion'] ->id;
                                                    if($_SESSION['cotizacion'] ->id_version!=0)
                                                    {
                                                        echo " - ".$_SESSION['cotizacion'] ->id_version;
                                                    } 
                                                } 
                                                else
                                                {
                                            ?>
                                                    <i>guarda cotizacion para ver folio</i>
                                            <? 
                                                }
                                            ?>
                                            <img src="images/spacer.gif" alt="" width="20" height="16" />
                                            <?
                                                if(intval($_SESSION['cotizacion']->es_version))
                                                {
                                            ?>
                                                    <input name="reestablecerCotizacion" type="submit" class="texto_info_negro" id="reestablecerCotizacion" value="Reestablecer Cotización" />
                                            <? 
                                                }
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                                
                                <!-- Borrar cotizacion -->
                                <tr>
                                    <td>
                                        <span class="texto_chico_gris">
                                            <img src="images/spacer.gif" alt="" width="20" height="10" />
                                        </span>
                                    </td>
                                    <td>
                                        <? 
                                            if($_SESSION['usuario']->id_rol <= $_SESSION['accesos']['supervisor'] )
                                            {
                                        ?>
                                                <div align="right">
                                                    <a href="#" onClick="return borrar(<? echo $_SESSION['cotizacion'] ->id_version?>,<? echo $_SESSION['cotizacion'] ->id;?>);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<? echo $count;?>','','images/cerrar_r.jpg',1)" >
                                                        <img src="images/cerrar.jpg" alt="" name="Image<? echo $count;?>" width="17" height="16" border="0" id="Image86" /> 
                                                    </a>
                                                </div>
                                        <?
                                            }
                                        ?>
                                    </td>
                                </tr>
			                    <script>
                                    // llena el select clientes y llena los contactos del cliente
                                    function cambiar1()
                                    {
                                        var index=document.forms.form1.id_cliente.selectedIndex;
                                        form1.id_contacto.length=0;
                                        if(index==0)
                                        {
                                            objetivo0();
                                        }
                                        <? 
		                                    $query23 = "SELECT Clientes.id, Clientes.id_cartera,Clientes.alias,Clientes.codigo FROM Clientes inner join CarteraClientes_Usuarios on Clientes.id_cartera=CarteraClientes_Usuarios.id_cartera_clientes where CarteraClientes_Usuarios.id_usuario=".$id_usuario." ORDER BY Clientes.alias";
                                            $result23 = mysql_query($query23);
		                                    $count23=1;
                                            while($res23 = mysql_fetch_assoc($result23))
                                            { 
                                        ?>  
                                                if(index==<? echo $count23?>)
                                                {
                                                    objetivo<? echo $count23?>();
                                                }
                                        <?
                                                $count23++; 
                                            }
                                        ?>
                                    }
                                    // llena contactos si no tiene cliente seleccionado.
                                    function objetivo0()
                                    {
                                        opcion0=new Option("Nombre del Contacto","","defauldSelected");
                                        document.forms.form1.id_contacto.options[0]=opcion0;
                                    }
                                    <? 
		                                $query24 = "SELECT Clientes.id, Clientes.id_cartera,Clientes.alias,Clientes.codigo FROM Clientes inner join CarteraClientes_Usuarios on Clientes.id_cartera=CarteraClientes_Usuarios.id_cartera_clientes where CarteraClientes_Usuarios.id_usuario=".$id_usuario." ORDER BY Clientes.alias";
                                        $result24 = mysql_query($query24);
		                                $count24=1;
                                        while($res24 = mysql_fetch_assoc($result24))
                                        { 
                                    ?> 
                                            function objetivo<? echo $count24?>()
                                            {
                                                opcion0=new Option("--Selecciona--","","defauldSelected");
                                                document.forms.form1.id_contacto.options[0]=opcion0;

		                            <? 
		                                        $query = "SELECT * FROM Contactos where id_cliente={$res24['id']} and activo=1 order by nombre_contacto";
                                                $result = mysql_query($query) or print("<option value=\"ERROR\">".mysql_error()."</option>");
		                                        $count=1;
                                                while($lags = mysql_fetch_assoc($result))
                                                { 
                                    ?>       
                                                    opcion<? echo $count?>=new Option("<? if($lags['activo']==0){echo"***";}?><? echo $lags['nombre_contacto']?>","<? echo $lags['id']?>", "", "<? echo $_SESSION['cotizacion']->id_contacto==$lags['id']?"selected":""; ?>");
                                                    document.forms.form1.id_contacto.options[<? echo $count?>]=opcion<? echo $count?>;
                                    <?
                                                    $count++;
                                                }
                                    ?>  
                                            }
                                    <? 
                                            $count24++; 
                                        }
                                    ?>

                                </script>
                                <tr>
                                    <td colspan="3">
                                        <!-- llenar cotizacion -->
                                        <table width="100%" bgcolor="#F5F5F5" border="0" align="center" cellpadding="2" cellspacing="0" style="padding:10px; ">
                                            <tr>
                                                <!-- Seleccionar Cliente -->
                                                <th width="66" align="right" class="texto_info_negro">
                                                    Cliente
                                                </th>
                                                <td width="214">
                                                    <span class="style5">
                                                        <select name="id_cliente" class="texto_info_negro_forma" id="id_cliente" style="width:200px" <? echo $esLectura;?> onChange="cambiar1(this.value);" onBlur="" >
                                                            <option value="">Nombre del Cliente</option>
                                                            <?
                                                                $consulta  = "SELECT Clientes.id, Clientes.id_cartera,Clientes.alias,Clientes.codigo FROM Clientes inner join CarteraClientes_Usuarios on Clientes.id_cartera=CarteraClientes_Usuarios.id_cartera_clientes where CarteraClientes_Usuarios.id_usuario=".$id_usuario." ORDER BY Clientes.alias";
                                                                $resultado_clientes= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
                                                                while($array=mysql_fetch_assoc($resultado_clientes))
                                                                {
                                                            ?>
                                                                    <option <? if( $_SESSION['cotizacion'] ->id_cliente == $array['id'] ) echo 'selected';?> value="<? echo $array['id'];?>">
                                                                        <? echo $array['alias']." (".$array['codigo'].")";?>
                                                                    </option>
                                                            <?
                                                                }
                                                            ?>
                                                        </select>
                                                    </span>
                                                    <input type="button" name="agregarcliente" onClick="abrir24('cambia_cliente.php')" class="texto_info_negro" value="Agregar cliente">
                                                </td>

                                                <!-- Seleccionar idioma -->
                                                <th align="right" class="texto_info_negro">
                                                    Idioma
                                                </th>
                                                <td class="texto_info_negro">
                                                    <label>
                                                        <input <? echo $esLectura;?> name="idioma" type="radio" id="idioma_ESP" value="ESP" <? if($_SESSION['cotizacion'] ->idioma == 'ESP' || $_SESSION['cotizacion'] ->idioma=="") echo "checked";?> onChange="changeLanguage(this.value)" />
                                                        Espa&ntilde;ol
                                                    </label>
                                                    <span class="texto_chico_gris">
                                                        <img src="images/spacer.gif" alt="" width="20" height="10" />
                                                    </span>
                                                    <label>
                                                        <input <? echo $esLectura;?> type="radio" name="idioma" value="ENG" id="idioma_ING" <? if($_SESSION['cotizacion'] ->idioma == 'ENG') echo "checked";?> onChange="changeLanguage(this.value)" />
                                                        Ingl&eacute;s
                                                    </label>
                                                </td>

                                                <!-- Seleccionar moneda -->
                                                <th align="right" class="texto_info_negro">
                                                    Moneda
                                                </th>
                                                <td class="texto_info_negro">
                                                    <label>
                                                        <input name="tipo_moneda" type="radio" id="tipo_moneda_mx" value="0" <? if($_SESSION['cotizacion'] ->tipo_moneda == 0) echo "checked";?> onChange="changeCurrency(this.value)" <? echo $esLectura;?>/>
                                                        MX
                                                    </label>
                                                    <span class="texto_chico_gris">
                                                        <img src="images/spacer.gif" alt="" width="20" height="10" />
                                                    </span>
                                                    <label>
                                                        <input type="radio" name="tipo_moneda" value="1" id="tipo_moneda_usa" <? if($_SESSION['cotizacion'] ->tipo_moneda == 1 || $_SESSION['cotizacion'] ->tipo_moneda=="") echo "checked";?> onChange="changeCurrency(this.value)" <? echo $esLectura;?> />
                                                        USA(<? echo $_SESSION['dollar']?>)
                                                    </label>   
                                                </td>
                                            </tr>
                                            <tr>
                                                 <!-- llenar atencion -->
                                                <th align="right" class="texto_info_negro">
                                                    Atención
                                                </th>
                                                <td>
                                                    <input <? echo $esLectura;?> name="atencion" type="text" class="texto_info_negro_forma" id="atencion" value="<? echo $_SESSION['cotizacion'] ->atencion;?>" size="25" maxlength="100" onBlur="" />
                                                </td>
                                                <!-- llenar referencia -->
                                                <th align="right" class="texto_info_negro">
                                                    Referencia
                                                </th>
                                                <td>
                                                    <input <? echo $esLectura;?>  name="referencia" type="text" class="texto_info_negro_forma" id="referencia" value="<? echo $_SESSION['cotizacion'] ->referencia;?>" size="25" maxlength="100" onBlur="" />
                                                </td>
                                                <!-- llenar vigencia -->
                                                <th align="right" class="texto_info_negro">
                                                    Vigencia
                                                </th>
                                                <td>
                                                    <input <? echo $esLectura;?>  name="vigencia" type="text" class="texto_info_negro_forma" id="vigencia" value="<? echo $_SESSION['cotizacion'] ->vigencia;?>" size="25" maxlength="100" onBlur="" />
                                                </td>
                                            </tr>

                                            <tr>
                                                <!-- Seleccionar prioridad -->
                                                <th align="right" class="texto_info_negro">
                                                    <div align="left">
                                                        Prioridad
                                                    </div>
                                                </th>
                                                <td>
                                                    <select name="id_prioridad" class="texto_info_negro_forma" id="id_prioridad" style="width:200px" <? echo $esLectura;?>  onblur="" >
                                                        <option value="0">- -</option>
                                                        <option value="1" <? if($_SESSION['cotizacion'] ->prioridad == 1) echo "selected";?> >
                                                            Petici&oacute;n de una requisici&oacute;n
                                                        </option>
                                                        <option value="2" <? if($_SESSION['cotizacion'] ->prioridad == 2) echo "selected";?> >
                                                            Presupuesto o requerimiento futuro
                                                        </option>
                                                        <option value="3" <? if($_SESSION['cotizacion'] ->prioridad == 3) echo "selected";?> >
                                                            Sugerencia m&iacute;a
                                                        </option>
                                                    </select>
                                                </td>

                                                <!-- Seleccionar prioridad -->
                                                <th align="right" class="texto_info_negro">
                                                    Estatus
                                                </th>
                                                <td>
                                                    <span class="style52">
                                                        <select name="id_estatus" class="texto_info_negro_forma" id="id_estatus"  <? echo $esLectura;?> onBlur="" >
                                                            <?
                                                                $consulta  = "SELECT * FROM EstatusCotizaciones WHERE 1";
                                                                $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
                                                                if(@mysql_num_rows($resultado_estatus)>=1)
                                                                {
                                                                    while($array=mysql_fetch_assoc($resultado_estatus))
                                                                    {
                                                                        ?>
                                                                            <option <? if($_SESSION['cotizacion'] ->id_estatus==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                                                                        <?
                                                                    }
                                                                }
                                                            
                                                            ?>
                                                        </select>
                                                    </span>
                                                </td>

                                                <!-- Seleccionar contacto -->
                                                <th align="right" class="texto_info_negro">
                                                    Contacto
                                                </th>
                                                <td>
                                                    <select name="id_contacto" class="texto_info_negro_forma" id="id_contacto" style="width:200px" <? echo $esLectura;?>  onblur="" >
                                                        <option value="">Nombre del Contacto</option>
                                                    </select>
                                                    <br/>
                                                    <input type="button" name="agregarcontacto" onClick="abrir2('cambia_contacto3.php?id_cliente=')" class="texto_info_negro" value="Agregar contacto">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
              
                                <tr>
                                    <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right">      
                                        <div  style="padding:0px 10px 0px 10px; background-color:#F5F5F5; width:880" class="texto_info_negro">
                                            <span class="texto_chico_gris">
                                                <!-- boton buscar producto -->
                                                <span class="texto_info_negro" style="padding:0px 10px 0px 10px; background-color:#F5F5F5; width:880">
                                                    <input <? echo $esLectura;?> style="float:left" name="Agregar" type="button" id="Agregar" value="Buscar producto" onclick="if(autoSaveCotizacion()) var contacto=$('#id_contacto').val(); window.location = 'seleccionar_productos_cotizacion.php?contacto='+contacto; " class="texto_info_negro" />
                                                </span>
                                                <!-- boton agregar especial -->
                                                <input <? echo $esLectura;?> style="float:left" type="button" class="texto_info_negro" id="Agregar5" onClick="if(autoSaveCotizacion()) abrir('cambia_producto_especial.php');" value="Agregar Especial"/>
                                            </span>
                                            <!-- boton producto por codigo -->
                                            Agregar producto por código
                                            <input <? echo $esLectura;?> name="agregar_producto_codigo" type="text" class="texto_info_negro_forma" id="agregar_producto_codigo" size="25" maxlength="100" onBlur="" />
                                            <input <? echo $esLectura;?> type="submit" name="agregarProductoPorCodigo" class="texto_info_negro" id="Agregar2" value="Agregar" onClick="CalcularIva();if(autoSaveCotizacion()) return true;"/>
                                        </div>

                                        <!-- productos en la cotizacion -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                            <tr>
                                                <td width="18" class="texto_info_negro">
                                                    &nbsp;
                                                </td>
                                                <th align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    #
                                                </th>
                                                <th width="500" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Nombre
                                                </th>
                                                <th width="30" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    <!--Proveedor-->
                                                    Stock/StockProv
                                                </th>
                                                <th width="100" colspan="2" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Cant
                                                </th>
                                                <th bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Precio
                                                </th>
                                                <th width="60" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Desc. %
                                                </th>
                                                <th width="60" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Subtotal
                                                </th>
                                                <th width="20" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Notas 
                                                </th>
                                            </tr>
                                            <?	  
                                                if( count( $_SESSION['cotizacion'] -> productos ) > 0)
                                                {
                                                    /*
                                                    * LOS PRODUCTOS QUE ESTÁN EN LA COTIZACIÓN Y CARRITO GUARDAN EN PRECIO
                                                    * EL VALOR ACTUAL PARA LA COTIZACIÓN, ES DECIR, CONVERTIDO A MXN O 
                                                    * A USD SEGÚN EL CASO
                                                    */
                                                    $count=1;
                                                    $color = "";
                                                    foreach ($_SESSION['cotizacion'] -> productos as $n => $producto)
                                                    {
                                                        $ConsultaProductoCotizacion="SELECT id FROM Cotizaciones_Productos WHERE id_cotizacion=".$_SESSION['cotizacion'] ->id." AND id_producto=".$producto->id." AND partida=".$producto->partida;
                                                        $ResultadoConsulta = mysql_query($ConsultaProductoCotizacion) or print("Eliminar_Producto_Cotizacion: $ConsultaProductoCotizacion " . mysql_error());
                                                        if(@mysql_num_rows($ResultadoConsulta)>=1){
                                                            $res=mysql_fetch_assoc($ResultadoConsulta);
                                                            $cotizacionproducto = $res['id'];
                                                        }
                                            ?>	
                                                        <tr class="texto_info_negro_c" bgcolor="<? echo $color;?>">
                                                            <!-- borrar producto -->
                                                            <td bgcolor="#FFFFFF" >
                                                                <a name="<?php echo $count?>">
                                                                </a>
                                                                <div align="right">
                                                                    <a onClick="eliminarProducto(<? echo $n;?>,<? echo $cotizacionproducto;?>,<? echo $_SESSION['cotizacion'] ->id;?>);" class="apuntador">
                                                                        <img src="images/cerrar.jpg" alt="" name="Image86" width="17" height="16" border="0" id="Image<? echo $count;?>" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image<? echo $count;?>','','images/cerrar_r.jpg',1)"  />
                                                                    </a>
                                                                </div>
                                                            </td>

                                                            <!-- numero -->
                                                            <td align="center" valign="middle"  bgcolor="<? echo $color;?>">
                                                                <strong>
                                                                    <? echo $producto->partida;?>
                                                                </strong>
                                                            </td>

                                                            <!-- nombre o descripcion -->
                                                            <td>
                                                                <div align="left">
                                                                    <?
                                                                        if($producto->archivo_ficha_tecnica!="" || $producto->archivo_ficha_tecnica!= null)
                                                                        {
                                                                    ?>
                                                                            <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank">
                                                                                <img src="images/pdf.ico" alt="pdf" width="21" height="21" border="0" />
                                                                            </a>
                                                                    <? 
                                                                        } 
                                                                    ?>
                                                                    <?
                                                                        $conDescr="";
                                                                        if($_SESSION['cotizacion'] ->idioma == 'ESP')
                                                                        {
                                                                            if( $producto->nombre == "")
                                                                            {
                                                                                $conDescr = "no";
                                                                                $attrName = "nombre";    
                                                                            }
                                                                        } 
                                                                        else if($_SESSION['cotizacion'] ->idioma == 'ENG')
                                                                        {
                                                                            if($producto->descripcion == "")
                                                                            {
                                                                                $conDescr = "no";
                                                                            }
                                                                            $attrName = "descripcion";
                                                                        }
                                                                        if($conDescr == "no")
                                                                        {
                                                                    ?>
                                                                            <a href="cambia_atributo.php?<? echo "attrName=$attrName&id=$producto->id";?>" onClick="" class="texto_info_negro_c iframe"> 
                                                                                Sin descripci&oacute;n 
                                                                                <img src="images/warning.png" alt="" name="warning" width="16" height="16" id="warning"/>
                                                                            </a>
                                                                    <?
                                                                        } 
                                                                        else 
                                                                        { 
                                                                    ?>
                                                                            <a href="<? echo $producto->id_proveedor == 8 ? "cambia_producto_especial.php" : "cambia_producto.php";	echo "?id=$producto->id";?>" onClick="" class="texto_info_negro_c iframe" style="line-height: 1;"> 
                                                                                <? echo $_SESSION['cotizacion'] ->idioma == 'ESP' ? "$producto->nombre ($producto->codigo_interno)":"$producto->descripcion ($producto->codigo_interno)"; ?> 
                                                                            </a>
                                                                    <? 
                                                                        }//END IF conDescr
                                                                    ?>
                                                                    <input type="hidden" id="conDescripcion<? echo $count;?>" value="<? echo $conDescr;?>" />
                                                                </div>
                                                            </td>
                                                            
                                                            <!-- stock -->
                                                            <td>
                                                                <div align="center">
                                                                    <span class="texto_contenido">
                                                                        <?
                                                                            echo $producto->stock;
                                                                            echo"/";
                                                                            echo $producto->stock_proveedor;
                                                                        ?>
                                                                        <input type="hidden" id="numProvee<? echo $count;?>" value="<? echo $producto->id_proveedor?>" />
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            
                                                            <!-- cantidad -->
                                                            <td >
                                                                <div align="center">
                                                                    <input <? echo $esLectura;?> name="cantidad<? echo $n;?>" type="text" class="texto_info_negro numberTiny" id="cantidad<? echo $count?>" onChange="if(checkIfNumber(this)){checkDescuento(<? echo $count;?>);setViewCurrency('subtotal<? echo $count;?>','subtotalView<? echo $count;?>'); setViewCurrency('precio_unitario_v<? echo $count;?>','precioVenta<? echo $count;?>');CalcularIva();ModificaProducto(<? echo $cotizacionproducto?>,<?echo $count?>);}" value="<? echo $producto->cantidad;?>" size="5" maxlength="5" />
                                                                </div>
                                                            </td>
                                                            
                                                             <!-- unidad metrica -->
                                                            <td width="10">
                                                                <?
                                                                    if($producto->unidad_metrica!="")
                                                                    {
                                                                ?>
                                                                        <span style="font-size: xx-small">
                                                                            <? echo $producto->unidad_metrica;?>
                                                                        </span>
                                                                        <input type="hidden" id="conUnidadMetrica<? echo $count;?>" value="si" />
                                                                <?                         
                                                                    } 
                                                                    else 
                                                                    { 
                                                                ?>
                                                                        <a href="cambia_um.php?attrName=unidad_metrica<? echo "&id=$producto->id&contacto=".$_SESSION['cotizacion']->id_contacto;?>" class="iframe">
                                                                            <img src="images/warning.png" alt="agregar unidad metrica" name="warning" width="16" height="16" id="warning2" title="agregar unidad metrica"/>
                                                                            <input type="hidden" id="conUnidadMetrica<? echo $count;?>" value="no" />
                                                                        </a>
                                                                <?
                                                                    }   
                                                                ?>
                                                            </td>
                                                            
                                                            <!-- precio -->
                                                            <td>
                                                                <div align="center">
                                                                    <input <? echo $esLectura;?> name="precio_unitario_v[]" type="hidden" class="numberTiny" id="precio_unitario_v<? echo $count?>" value="<? 
                                                                    if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0") {
                                                                        echo round((((1-($producto->descuento))*($producto->precio))+ ($producto->recargo * $valor_moneda)),2);
                                                                    }
                                                                    else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1")
                                                                    {
                                                                        echo round((((1-($producto->descuento))*($producto->precio))+ ($producto->recargo)),2);
                                                                    }else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
                                                                    {
                                                                                switch ($producto->tipo_moneda_usa)
                                                                                {
                                                                                    case "0":
                                                                                       echo round((((1-($producto->descuento))*($producto->precio))+ ($producto->recargo* $valor_moneda)),2);
                                                                                    break;
                                                                                    case "1":
                                                                                        echo round((((1-($producto->descuento))*($producto->precio))+ ($producto->recargo)),2);
                                                                                    break;
                                                                                    default:
                                                                                        # code...
                                                                                    break;
                                                                                }
                                                                    }?>" size="6" maxlength="3" readonly="readonly" />
                                                                    
                                                                    <input <? echo $esLectura;?> name="precio_unitario[]" type="hidden" class="numberTiny" id="precio_unitario<? echo $count?>" value="<? 
                                                                   echo round(($producto->precio),2);
                                                                    
                                                                        
                                                                    ?>" size="6" maxlength="3" readonly="readonly" />
                                                                    <span name="precioVenta[]" class="texto_info_negro numberTiny" id="precioVenta<? echo $count;?>">
                                                                        <? 
                                                                            //Valida que el tipo de moneda seleccionado no sea el mismo que el del producto

                                                                            // cambio de dolar a pesos
                                                                            if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0") {
                                                                                //echo "<script>console.log('dolar a pesos')</script>";
                                                                                //echo "<script>console.log('precioventa: ".$producto->precio."')</script>";
                                                                                echo getFormatedNumberForMoney((($producto->precio)+ ($producto->recargo * $valor_moneda)));
                                                                            }
                                                                                //pesos a dolar
                                                                            else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1")
                                                                            {
                                                                                //echo "<script>console.log('pesos a dolar')</script>";
                                                                                //echo "<script>console.log('precioventa: ".$producto->precio."')</script>";
                                                                               echo getFormatedNumberForMoney(( ($producto->precio) + ($producto->recargo) ));
                                                                            }
                                                                            else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
                                                                            {
                                                                                switch ($producto->tipo_moneda_usa)
                                                                                {
                                                                                    case "0":
                                                                                       echo getFormatedNumberForMoney($producto->precio + ($producto->recargo*$valor_moneda) );
                                                                                    break;
                                                                                    case "1":
                                                                                        echo getFormatedNumberForMoney($producto->precio + $producto->recargo);
                                                                                    break;
                                                                                    default:
                                                                                        # code...
                                                                                    break;
                                                                                }
                                                                                
                                                                            }
                                                                        ?>
                                                                    </span>
                                                                    <span style="font-size: xx-small">
                                                                        <?
                                                                            // cambio de dolar a pesos
                                                                            if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0") {
                                                                                echo "MXN";
                                                                            }
                                                                                //pesos a dolar
                                                                            else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1")
                                                                            {
                                                                                
                                                                               echo "USD";
                                                                            }
                                                                            else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
                                                                            {
                                                                                switch ($producto->tipo_moneda_usa)
                                                                                {
                                                                                    case "0":
                                                                                        echo "MXN";
                                                                                    break;
                                                                                    case "1":
                                                                                        echo "USD";
                                                                                    break;
                                                                                    default:
                                                                                        # code...
                                                                                    break;
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </span>
                                                                </div>
                                                            </td>
                                            
                                                            <!-- descuento-->
                                                            <td>
                                                                <div align="center">
                                                                    <input <? echo $esLectura;?> name="descuento<? echo $n;?>" type="number" class="texto_info_negro numberTiny" onChange="checkDescuento(<? echo $count;?>);setViewCurrency('subtotal<? echo $count;?>','subtotalView<? echo $count;?>'); setViewCurrency('precio_unitario_v<? echo $count;?>','precioVenta<? echo $count;?>');CalcularIva();ModificaProducto(<? echo $cotizacionproducto?>,<?echo $count?>);" id="descuento<? echo $count?>" value="<? echo ($producto->descuento*100);?>" size="6" maxlength="3" style="width:40px"/>
                                                                    <input <? echo $esLectura;?> name="recargo[]" type="hidden" class="texto_info_negro numberTiny" id="recargo<? echo $count?>" value="<? 
                                                                    if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0") {
                                                                        echo  ($producto->recargo * $valor_moneda);
                                                                    }
                                                                    else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1"){
                                                                        echo  ($producto->recargo);
                                                                    }
                                                                    else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
                                                                    {
                                                                        switch ($producto->tipo_moneda_usa)
                                                                        {
                                                                            case "0":
                                                                                echo  ($producto->recargo * $valor_moneda);
                                                                            break;
                                                                            case "1":
                                                                                echo  ($producto->recargo);
                                                                            break;
                                                                            default:
                                                                                # code...
                                                                            break;
                                                                        }
                                                                    }?>"/>
                                                                </div>
                                                            </td>
                                
                                                            <!-- subtotal -->
                                                            <td >
                                                                <div align="center"> 
                                                                    <span name="subtotalView[]" class="texto_info_negro" id="subtotalView<? echo $count;?>"><?
                                                                             if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0") {
                                                                                echo "<script>console.log('dolar a pesos subtotal');</script>";
                                                                                    echo "<script>console.log('pp:".($producto->precio+$producto->recargo)."');</script>";
                                                                                    echo "<script>console.log('descuento:".(($producto->precio+$producto->recargo)*($producto->descuento))."');</script>";
                                                                                echo getFormatedNumberForMoney(((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento)))*$producto->cantidad));
                                                                            }
                                                                                //pesos a dolar
                                                                            else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1")
                                                                            {
                                                                                echo "<script>console.log('pesos a dolar subtotal');</script>";
                                                                                    echo "<script>console.log('pp:".($producto->precio+$producto->recargo)."');</script>";
                                                                                    echo "<script>console.log('descuento:".(($producto->precio+$producto->recargo)*($producto->descuento))."');</script>";
                                                                                 if (empty($producto->descuento)){
                                                                                     $producto->descuento=0;
                                                                                 }
                                                                               echo getFormatedNumberForMoney(((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento)))*$producto->cantidad));
                                                                            }
                                                                            else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
                                                                            {
                                                                                switch ($producto->tipo_moneda_usa)
                                                                                {
                                                                                    case "0":
                                                                                    echo "<script>console.log('pesos a  pesos');</script>";
                                                                                    echo "<script>console.log('pp:".($producto->precio+$producto->recargo)."');</script>";
                                                                                    echo "<script>console.log('descuento:".(($producto->precio+$producto->recargo)*($producto->descuento))."');</script>";
                                                                                        echo getFormatedNumberForMoney(((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento)))*$producto->cantidad));
                                                                                    break;
                                                                                    case "1":
                                                                                    echo "<script>console.log('dolar a  dolar');</script>";
                                                                                    echo "<script>console.log('pp:".($producto->precio+$producto->recargo)."');</script>";
                                                                                    echo "<script>console.log('descuento:".(($producto->precio+$producto->recargo)*($producto->descuento))."');</script>";
                                                                                       echo getFormatedNumberForMoney(((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento)))*$producto->cantidad));
                                                                                    break;
                                                                                    default:
                                                                                        # code...
                                                                                    break;
                                                                                }
                                                                                
                                                                            }
                                                                        ?>
                                                                    </span>
                                                                    <input <? echo $esLectura;?> name="subtotal[]" type="hidden" class="texto_info_negro" id="subtotal<? echo $count;?>" value="<? 
                                                                        if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0")
                                                                        {
                                                                            //dolar a pesos
                                                                            
                                                                            // echo getFormatedNumberForMoney((((1-($producto->descuento))*$producto->precio)+ ($producto->recargo * $valor_moneda)) * $producto->cantidad);
                                                                            //echo ((((1-($producto->descuento))*($producto->precio))+ ($producto->recargo * $valor_moneda)) * $producto->cantidad);
                                                                            echo ((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento/100)))*$producto->cantidad);
                                                                        }
                                                                            //pesos a dolar
                                                                        else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1")
                                                                        {
                                                                            //echo $producto->precio;
                                                                           echo ((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento/100)))*$producto->cantidad);
                                                                           //echo getFormatedNumberForMoney(( ((1 - $producto->descuento ) * $producto->precio) + ($producto->recargo * $valor_moneda)) * $producto->cantidad);
                                                                        }
                                                                        else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda)
                                                                        {
                                                                            switch ($producto->tipo_moneda_usa)
                                                                                {
                                                                                    case "0"://pesos a pesos
                                                                                        echo ((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento/100)))*$producto->cantidad);
                                                                                    break;
                                                                                    case "1"://dolar
                                                                                    echo ((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento/100)))*$producto->cantidad);
                                                                                       //echo (((1-($producto->descuento))*$producto->precio)+$producto->recargo) * $producto->cantidad;
                                                                                    break;
                                                                                    default:
                                                                                        # code...
                                                                                    break;
                                                                                } 
                                                                            
                                                                        }
                                                                    ?>"/>
                                                                </div>
                                                            </td>
                                                            
                                                            <!-- notas -->
                                                            <td align="center">
                                                                <img src="images/detalles.png" alt="" name="nota<? echo $producto->id;?>" width="25" height="25" id="nota<? echo $producto->id;?>" style="cursor: pointer;<? echo empty($producto->comentario)?"-webkit-filter: invert(100%);":"";?>" onclick="abrir('cambia_nota_producto.php?pos=<? echo $n;?>',false)"/>
                                                                <input name="comentarioProducto<? echo $n;?>" type="hidden" id="comentarioProducto<? echo $count?>" value="<? echo htmlspecialchars($producto->comentario);?>"/>
                                                            </td>
                                                        </tr>
                                                        <?
                                                            if($color == "#F5F5F5")
                                                            {
                                                                $color = "";
                                                            }
                                                            else
                                                            {
                                                                $color = "#F5F5F5";
                                                            }
                                                           
                                                        ?>
                                            <?
                                                        $count=$count+1;
                                                    }//final de foreach  
                                                          
                                                }//final if
                                            ?>         
                                            <input type="hidden" name="count" id="count" value="<? echo $count?>">
                                        </table>     
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span class="texto_chico_gris">
                                            <img src="images/spacer.gif" alt="" width="20" height="10" />
                                        </span>
                                    </td>
                                </tr>          
                                <? 
                                    if(!$vistaLectura)
                                    {
                                ?>
                                        <!-- boton ordenar partidas -->
                                        <tr>
                                            <td colspan="3" align="left">
                                                <input <? echo $esLectura;?> type="button" class="texto_info_negro" id="Agregar3" onClick="abrir('ordenar_partidas.php',false);" value="Ordenar Partidas"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="left">
                                                <table width="99%" border="0" align="center" cellpadding="2" cellspacing="2" style="margin:10px; ">
                                                    <tr>
                                                        <th width="25%" align="center" class="texto_info_negro">
                                                            Libre Abordo
                                                        </th>
                                                        <th width="25%" align="center" class="texto_info_negro">
                                                            T&eacute;rminos de entrega
                                                        </th>
                                                        <th width="25%" align="center" class="texto_info_negro">
                                                            Otros
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td width="25%" align="center" class="texto_info_negro">
                                                            <span class="style8">
                                                                <textarea name="LAB"  <? echo $esLectura;?>  rows="2" class="texto_info_negro" id="LAB" style="width:95%"><? echo stripslashes($_SESSION['cotizacion'] ->LAB);?></textarea>
                                                            </span>
                                                        </td>
                                                        <td width="25%" align="center">
                                                            <span class="style8">
                                                                <textarea name="terminos_entrega" rows="2" onBlur="" <? echo $esLectura;?> class="texto_info_negro" id="terminos_entrega" style="width:95%"><? echo stripslashes($_SESSION['cotizacion'] ->terminos_entrega);?></textarea>
                                                            </span>
                                                        </td>
                                                        <td width="25%" align="center" class="texto_info_negro">
                                                            <span class="style5">
                                                                <textarea name="comentarioCotizacion"  onblur="" rows="2" class="texto_info_negro" <? echo $esLectura;?>  id="comentarioCotizacion" style="width:95%"><? echo stripslashes($_SESSION['cotizacion'] ->notas_adicionales);?></textarea>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table width="100%" border="0" align="center" cellpadding="2" cellspacing="2" style="margin:10px; ">
                                                    <tr>
                                                        <th width="25%" align="center" class="texto_info_negro">Condiciones de pago</th>
                                                    </tr>
                                                    <tr bgcolor="#F5F5F5">
                                                        <td width="25%" align="center" class="texto_info_negro">
                                                            &nbsp;
                                                            <?
                                                                if(isset($_SESSION['cotizacion'] ->id_cliente))
                                                                {
                                                                    $cliente = new Cliente();
                                                                    $cliente->get($_SESSION['cotizacion'] ->id_cliente);
                                                                    echo $cliente->condiciones_pago;
                                                                } 
                                                                else
                                                                {
                                                                    echo "<i>Guarda primero la cotización para ver las condiciones de pago</i>";
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p>&nbsp;</p>
                                            </td>
                                        </tr>
                                <? 
                                    }//fin if $vistaLectura
                                ?>
                                <tr>
                                    <td colspan="3"><img src="images/spacer.gif" alt="" width="20" height="10" />
                                        <table  width="450" border="0" align="center" cellpadding="2" cellspacing="2">
                                            <tr>
                                                <td colspan="3" bgcolor="#E91B25">
                                                    <div align="center" class="texto_info_blanco">
                                                        TOTAL
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    <div align="center">
                                                        <input name="conIva"  type="checkbox" class="texto_info_negro" id="conIva" onClick="CalcularIva();agregarIva(this);" value="1" <? echo $esLectura;?><?if($_SESSION['cotizacion']->con_iva == 1 || !isset($_SESSION['cotizacion'] )) echo "checked";?> />
                                                            IVA
                                                    </div>
                                                </td>
                                                <td align="center" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    <div align="center">
                                                        Subtotal
                                                    </div>
                                                </td>
                                                <td align="center" bgcolor="#E3E3E3" class="texto_info_negro">
                                                    Total
                                                </td>
                                            </tr>

                                            <tr>
                                                <!-- iva -->
                                                <td align="center" class="texto_info_negro">
                                                    <div align="center">
                                                        <span class="texto_info_negro numberMedium" id="ivaView">
                                                            
                                                            $<?  if($_SESSION['cotizacion'] ->con_iva == 1 )echo getFormatedNumberForMoney($_SESSION['cotizacion'] ->iva);?>
                                                        </span>
                                                        <input <? echo $esLectura;?> name="iva" type="hidden" class="texto_info_negro numberMedium" id="iva" value="<? if($_SESSION['cotizacion'] ->con_iva == 1)echo round($_SESSION['cotizacion'] ->iva , 2);?>"/>
                                                    </div>
                                                </td>

                                                <!-- subtotal -->
                                                <td align="center" class="texto_info_negro">
                                                    <span class="texto_info_negro numberMedium" id="subtotalView">
                                                        $<? echo getFormatedNumberForMoney($_SESSION['cotizacion'] ->subtotal);
                                                           //var_dump($stotal);
                                                        ?>
                                                    </span>
                                                    <input <? echo $esLectura;?> name="subtotal" type="hidden" class="texto_info_negro numberMedium" id="subtotal" value="<? echo round($_SESSION['cotizacion'] ->subtotal,2);?>"/>
                                                </td>

                                                <!-- total -->
                                                <td align="center" class="texto_info_negro">
                                                    <span class=" texto_info_negro numberMedium" id="totalView">
                                                        $<? echo $_SESSION['cotizacion'] ->con_iva > 0 ? getFormatedNumberForMoney($_SESSION['cotizacion'] ->total) :  getFormatedNumberForMoney($_SESSION['cotizacion'] ->subtotal);?>
                                                    </span>  
                                                    <input name="total" type="hidden" class=" texto_info_negro numberMedium" id="total" value="<? echo $_SESSION['cotizacion'] ->con_iva > 0 ? round($_SESSION['cotizacion'] ->total,2) :  round($_SESSION['cotizacion'] ->subtotal,2);?>" <? echo $esLectura;?>/>
                                                </td>

                                            </tr>
                                        </table>
                                        <p>&nbsp;</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <span class="texto_chico_gris">
                                            <img src="images/spacer.gif" alt="" width="20" height="10" />
                                        </span>
                                    </td>
                                </tr>
              
                                <? 
                                    if(!$vistaLectura)
                                    {
                                ?>
                                        <tr>
                                            <td colspan="3" align="center">   
                                                <input <? echo $esLectura;?> name="crearVersion" type="submit" class="texto_info_negro " id="crearVersion" value="Crear Version" />
                                                <input <? echo $esLectura;?> name="guardarCotizacion" type="submit" class="texto_info_negro "  id="guardarCotizacion"  value="Guardar" onClick="if(autoSaveCotizacion()) return true;"/>
                                                <input name="previsualizar" type="submit" id="previsualizar" value="previsualizar" onClick="if(autoSaveCotizacion()) return validar();"  class="texto_info_negro"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <span class="texto_chico_gris">
                                                    <img src="images/spacer.gif" alt="" width="20" height="10" />
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center">
                                                <input <? echo $esLectura;?> name="guardarCotizacionYNuevo" type="submit" class="texto_info_negro "  id="guardarCotizacionYNuevo"  value="Guardar y Nuevo" />
                                                <input <? echo $esLectura;?> name="genera" type="submit" class="texto_info_negro "  id="genera"  value="Generar PDF" onClick="if(autoSaveCotizacion()) return true;"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="left">
                                                <div align="center">
                                                    <blockquote>
                                                        <p>&nbsp;</p>
                                                        <p>&nbsp;</p>
                                                    </blockquote>
                                                </div>
                                            </td>
                                        </tr>
                                <?  
                                    }
                                ?>
                            </table>
                        </div>
                    </td>
                    <!-- barra lateral derecha -->
                    <td width="7" valign="top">
                        <img src="images/sombra_productos_gris.jpg" alt="enviar" width="7" height="553" />
                    </td>
                    <td valign="top" width="40" bgcolor="#808080">
                        <div style="" class="rotateButton texto_info_negro">

                            <!-- enviar -->
                            <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "enviar_cotizacion.php";?>" class="texto_info_negro menuLateral iframe" >
                                <img src="images/icono_enviar.png" alt="enviar" title="enviar" class="imgIco"/>
                            </a>

                            <!-- historial de correo -->
                            <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "historial_correo.php";?>" class="texto_info_negro menuLateral iframe" onmouseout="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image3','','images/icono_historial_r.png',1)"> 
                                <img src="images/icono_historial.png" alt="historial de correo" title="historial de correo" class="imgIco" name="Image3" width="26" height="20" border="0" id="Image3" />
                            </a>
                            
                            <!-- versiones -->
                            <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "adm_cotizaciones_versiones.php";?>" class="texto_info_negro menuLateral iframe" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image22','','images/icono_versiones_r.png',1)">
                                <img src="images/icono_versiones.png" alt="versiones" title="versiones" class="imgIco" name="Image22" width="26" height="20" border="0" id="Image22" /><!--VERSIONES-->
                            </a>
    
                            <!-- tareas -->
                            <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "adm_tareas.php?fromCotizacion=1";?>" class="texto_info_negro menuLateral iframeMini" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image21','','images/icono_tareas_r.png',1)">
                                <?
                                    if( isset( $_SESSION['cotizacion']  ) )
                                    {
                                         if(count($_SESSION['cotizacion']->getTareas()))
                                         { 
                                ?>
                                            <div id="totalTareas" style="float: left;font-size: smaller;color: #FFF;margin-right: -10px;background: #000000;padding: 0px 1px;border-radius: 10px;">
                                                <strong>
                                <?
                                                    echo count($_SESSION['cotizacion']->getTareas());
                                ?>
                                                </strong>
                                            </div>
                                <? 
                                        }
                                    }
                                ?>
                                <img src="images/icono_tareas.png" alt="tareas" title="tareas" class="imgIco" name="Image1" width="26" height="20" border="0" id="Image21" />
                            </a>


                            <!-- comentario -->
                            <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "comentarios_cotizacion.php?test=1&id=".$_SESSION['cotizacion'] ->id;?>" class="texto_info_negro menuLateral iframeMini" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image23','','images/icono_comentarios_r.png',1)">
                                <? 
                                    if( isset( $_SESSION['cotizacion']  ) )
                                        if(count($_SESSION['cotizacion']->getComentarios()))
                                        { 
                                ?>
                                            <div style="float: left;font-size: smaller;color: #FFF;margin-right: -10px;background: #000000;padding: 0px 1px;border-radius: 10px;">
                                                <strong>
                                <?
                                                    echo count($_SESSION['cotizacion']->getComentarios());
                                ?>
                                                </strong>
                                            </div>
                                <?
                                        }
                                ?>
                                <img src="images/icono_comentarios.png" alt="comentarios" title="comentarios" class="imgIco" name="Image23" width="26" height="20" border="0" id="Image23" /><!--COMENTARIOS-->
                            </a>

                            <!-- archivos    -->
                            <a href="<? if( isset( $_SESSION['cotizacion']  ) ) echo "archivos_cotizacion.php";?>" class="texto_info_negro menuLateral iframeMini" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image24','','images/icono_archivos_r.png',1)">
                                <img src="images/icono_archivos.png" alt="archivos" title="archivos" name="Image24" width="26" height="20" border="0" class="imgIco" id="Image24" />	<!--ARCHIVOS-->
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
            <script> 
                window.onload=function()
                { 
                    cambiar1(); 
                } 
            </script>
        </form>
        <script>
            <?
                if ($guardarFromDirectlyAdd != "") 
                {
            ?>
                    if(<? echo $guardarFromDirectlyAdd?>)
                    {
                        $.colorbox({iframe:true,href:'seleccionar_origen_producto.php?id='+<? echo $prod['id']?>+"&guardarFromDirectlyAdd=true",
                            width:"400", 
                            height:"553",
                            transition:"fade", 
                            scrolling:false, opacity:0.5}
                        );
                    }
            <?
                }
            ?>

            
            function clearSessionCotizacion()
            {
                $("#form1").ajaxSubmit({
                    url: 'clearSessionCotizacion.php', 
                    type: 'post'
                });
            }


            function autoSaveCotizacion2(){

            var subtotal = $('#subtotal').val();
            var total = $('#total').val();
            var iva = $('#iva').val();
            var select = document.getElementById("id_contacto");
            var options=document.getElementsByTagName("option");
            var contacto = $('#id_contacto').val();
            $("#form1").ajaxSubmit({
                    url: 'saveCotizacion.php?sub='+subtotal+'&total='+total+'&iva='+iva+'&contacto='+contacto, 
                    type: 'post'
                });
            }
             function autoSaveCotizacion4(){

            var subtotal = $('#subtotal').val();
            var total = $('#total').val();
            var iva = $('#iva').val();
            var select = document.getElementById("id_contacto");
            var options=document.getElementsByTagName("option");
            
            $("#form1").ajaxSubmit({
                    url: 'saveCotizacion.php?sub='+subtotal+'&total='+total+'&iva='+iva, 
                    type: 'post'
                });
            }
            function autoSaveCotizacion3(contacto){
            //console.log('autoSaveCotizacion3');
            //console.log(contacto);
            var cotizacion=<? echo $_SESSION['cotizacion']->id;?>;
            //console.log(cotizacion);
                $.ajax({
                    method: "POST",
                    url: "savecontacto.php",
                    data: {cotizacion:cotizacion,contacto:contacto},
                    beforeSend: function(){
                    }
                })
                .done(function(data) {
                    //console.log(data);
                    if (data==1){
                        //location.reload(true);
                    }
                });
            }


            //obtiene el subtotal de todos los productos, los suma y otiene el iva
		function CalcularIva2() 
        {
            var total = "0";
            var subtotal = 0;
            var iva = 0;
            if ($('#count')!=0) 
            {
                var count = parseInt($('#count').val())-1;
               // console.log('count'+count);
                for (i = 1; i <= count; i++)
                {
                    if ($('#subtotal'+i).length>0)//si existe
                    {
                        
                        subtotal = $('#subtotal'+i).val();
                        console.log('subtotal2: '+subtotal);
                        total = parseFloat(total) + parseFloat(subtotal);
                        console.log('total2: '+total);
                    }
                }

               //cuando tiene seleccionado el iva
                if($('#conIva').attr('checked'))
                {
                   
                    iva = total * 0.16;
                }

                console.log('iva2: '+iva);
                var total2 = total+iva;
                $('#subtotal').val(total);//input no se ve.
                $('#iva').val(iva);//input no se ve.
                $('#total').val(total2);//input no se ve.
                setViewCurrency('subtotal','subtotalView');
                setViewCurrency('iva','ivaView');
                setViewCurrency('total','totalView');
                //$('#subtotalView').text('$'+total);//span que muestra subtotal.
                //autoSaveCotizacion2();
            }
        }
        </script>
        <form action="" method="post" name="formBorrarProductoCarrito" id="formBorrarProductoCarrito">
        </form>
    </body>
</html>
<?php
    //Borrar producto y guardar cotizacion.
    if ($borrar==1)
    {
        guardarCotizacion();
        echo "<script>CalcularIva();</script>";
        saveCotizacionOnDB();
    }
    //guardar producto agregado y cotizacion nueva.
    //var_dump($_GET['g']);
    $g = $_GET['g'];
    if ($g == "1" || $g == 1)
    {
        $contacto = $_GET['contacto'];//recibido de selleccionar_producto_cotizacion.php
        //echo "<script>alert('$contacto');</script>";
        echo "<script>CalcularIva2();</script>";
        echo "<script>autoSaveCotizacion3('".$contacto."');</script>";
        guardarCotizacion();
        //saveCotizacionOnDB();
        $g="";
    }
    //
    //var_dump($_GET['reloadCarritoOnId']);
    if($_GET['reloadCarritoOnId']!='')
    {
        reloadCarrito($_GET['reloadCarritoOnId']);
        //clearURIVariables();
        $contacto = $_GET['contacto'];
        echo "<script>autoSaveCotizacion3('".$contacto."');</script>";
        echo "<script>CalcularIva2();</script>";
        
        //echo "<script>autoSaveCotizacion4();</script>";
        guardarCotizacion();
        echo "<script> location.href='generar_cotizacion_p.php?idCotizacionEditar=".$_SESSION['cotizacion']->id."&idVersion=0';</script>";
    }
?>