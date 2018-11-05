<?
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cliente.php';
include_once 'Cotizacion.php';

session_start();

include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['mostrador']);
include_once 'functions_cotizacion.php';


include_once "checar_permisos.php";
$vistaLectura = !tienePermisoEscritura(
        $_SESSION['accesos']['administrador'],
        $_SESSION['accesos']['supervisor'],
        $_SESSION['accesos']['vendedor']);


if($_POST['reestablecerCotizacion']!=""){
	$_SESSION['cotizacionVersion'] -> es_version = "";
	$_SESSION['cotizacionVersion'] -> fecha_version = "";
	$_SESSION['cotizacionVersion'] -> update( $_SESSION['cotizacionVersion'] );
	
	date_default_timezone_set('America/Chihuahua');
	$_SESSION['cotizacion'] -> fecha_version =  date('Y-m-d H:i:s');
	$_SESSION['cotizacion'] -> es_version = true;
	$_SESSION['cotizacion'] -> update( $_SESSION['cotizacion'] );
	
	$_SESSION['cotizacion'] = $_SESSION['cotizacionVersion'];
	
        $idCotizacion = $_SESSION['cotizacionVersion']->id;
        $idVersion = $_SESSION['cotizacionVersion']->id_version;
	unset($_SESSION['cotizacionVersion']);
	?>
<script>
    parent.location = "generar_cotizacion.php?idCotizacionEditar=<? echo $idCotizacion;?>&idVersion=<? echo $idVersion;?>";
</script><?
}


if($_REQUEST['idCotizacionEditar'] != "" && $_REQUEST['idVersion']!=""){
    $cotizacion = new Cotizacion();
    $cotizacion -> get( $_REQUEST['idCotizacionEditar'] , $_REQUEST['idVersion'] );
    $cotizacion -> setCarritoFromCotizacion();
    $_SESSION['cotizacionVersion'] = $cotizacion;
    $vistaLectura = true;
    $esLectura = "disabled";
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
-->
</style>

  
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script> 
<script src="colorbox/jquery.colorbox-min.js"></script>



<!--
FOR DATEPICKER--------------------------------
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
 --> 

<!--  <script>
  $(function() {
    $( "#fecha_entrega" ).datepicker({
      numberOfMonths: 3,
      showButtonPanel: true,
	  dateFormat: 'yy-mm-dd'
    });
  });
  </script>-------------------------------------->
 
<script>
$(document).ready(function(){
                //Examples of how to assign the ColorBox event to elements

                $(".iframe").colorbox({iframe:true,width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
				
                $(".iframeMini").colorbox({iframe:true,width:"400", height:"250",transition:"fade", scrolling:false, opacity:0.5});

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
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"250",transition:"fade", scrolling:false, opacity:0.5});
		} else {
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
		}
	}
        
	function abrir2(ir){
            $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
	}
	function abrir3(ir){
            $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"250",transition:"fade", scrolling:false, opacity:0.5});
	}
        if('<? echo $modificarProductoBox; ?>' != ""){
            abrir('<? echo $modificarProductoBox; ?>' ,false);
        }
        
    function checkDescuento(n){
        var obj = document.getElementById('descuento' + n);
        var limite_descuento = parseFloat(<? echo $_SESSION['usuario']->limite_descuento?>) * 100;
		var precioAnterior = parseFloat( document.getElementById('subtotal' + n ).value );
        if(obj.value > limite_descuento){
            alert('Tu l\u00edmite de descuento es ' + limite_descuento * 100 + '%');
        }else {
            var precioUnit = document.getElementById('precio_unitario' + n).value;
            var cantidad = document.getElementById('cantidad' + n).value;
			var subtotalProducto = Math.round((1 - (obj.value/100)) * precioUnit * cantidad);
            document.getElementById('subtotal' + n).value = subtotalProducto;
        }
		setTotals( subtotalProducto , precioAnterior );
    }
	
	function setTotals(subtotalProducto , precioAnterior){
		var diferencia = parseFloat(subtotalProducto) - parseFloat(precioAnterior);
		document.getElementById('subtotal').value +=  parseFloat(diferencia);
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
    var form = document.getElementById('form1');
    var element = document.createElement('input');
    element.name = 'changeCurrencyTo';
    element.value = value;
    element.type = 'hidden';
    form.appendChild(element);
    form.submit();
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
	$.fn.colorbox.close();
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
	for(var i=1; i <= <? echo count($_SESSION['cotizacionVersion'] ->productos);?> ; i++){
		var x = i;
		if(document.getElementById('conDescripcion' + i).value == 'no'){
			alert('Producto sin descripcion');
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
	var isSizeMini = false;
    if(cotizacionSessionExist()){
        abrir('enviar_cotizacion.php',isSizeMini);
    }
}
function cotizacionSessionExist(){
	if(<? if($_SESSION['cotizacionVersion'] !="") echo 'true'; else echo 'false'; ?>)
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

function saveCotizacion(nextFunction, variables){
	$("#form1").ajaxSubmit({
            url: 'saveCotizacion.php', 
            type: 'post', 
            success: nextFunction(variables)
        });
}

function autoSaveCotizacion(){
	$("#form1").ajaxSubmit({
            url: 'saveCotizacion.php', 
            type: 'post'
        });
}

</script>
<style type="text/css">
<!--
.numberTiny {	width: 60px;
	text-align: center;
}

.numberMedium{	
	text-align: center;
}
-->
</style>
</head>

<body onLoad="MM_preloadImages('images/cerrar_r.jpg')">
<div style="overflow: inherit; width: 800px; height: 553px;">
<form id="form1" name="form1" method="post" action="">
<table width="890px"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="max-width:890px">
          <tr>
            <td width="568" valign="top"><table width="568" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"><div align="left" class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="16" /></div></td>
              </tr>
              <tr>
                <td><div align="left"><img src="images/tit_cotizaciones.jpg" alt="" width="221" height="28" /></div></td>
                <td align="right"><span class="texto_chico_gris">
                  
   <? if(!$vistaLectura){?><input <? echo $esLectura;?> name="guardarCotizacion" type="submit" class="texto_info_negro" id="guardarCotizacion" onClick="return validar();" value="Guardar Cotización" />
   <? } else { ?> 
   <input name="reestablecerCotizacion" type="submit" class="texto_info_negro" id="reestablecerCotizacion" value="Reestablecer Cotización" />
   <? } ?>
                </span></td>
              </tr>
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="16" />FOLIO: <? if(isset($_SESSION['cotizacionVersion'] ))
				echo $_SESSION['cotizacionVersion'] ->id;
			else  {?> 
            <i>guarda cotizacion para ver folio</i>
            <? }?>
                </span></td>
              </tr>
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#e3e3e3"><table width="550px" border="0" align="center" cellpadding="0" cellspacing="2">
                  <tr>
                    <td width="66" class="texto_info_negro">Cliente</td>
                    <td width="214"><span class="style5">
                      <select name="id_cliente" class="texto_info_negro_forma" id="id_cliente" style="width:200px" <? echo $esLectura;?>  onblur="autoSaveCotizacion();" >
                        <option value="">Nombre del Cliente</option>
                        <?php
    $consulta  = "SELECT id, id_cartera,nombre_empresa, nombre_contacto AS nombre
	FROM Clientes ORDER BY nombre";
    $resultado_clientes= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
    while($array=mysql_fetch_assoc($resultado_clientes)) {
        ?>
                        <option <? if( $_SESSION['cotizacionVersion'] ->id_cliente == $array['id'] ) echo 'selected';?>
            value="<? echo $array['id'];?>"><? echo $array['nombre']." (".$array['nombre_empresa'].")";?></option>
                        <?
    }
    ?>
                      </select>
                    </span></td>
                    <td width="70" class="texto_info_negro"><div align="left">Prioridad</div></td>
                    <td width="210"><select name="id_prioridad" class="texto_info_negro_forma" id="id_prioridad" style="width:200px" <? echo $esLectura;?>  onblur="autoSaveCotizacion();" >
                      <option value="0">- -</option>
                      <option value="1"
        <? if($_SESSION['cotizacionVersion'] ->prioridad == 1) echo "selected";?>
        >Petici&oacute;n de una requisici&oacute;n</option>
                      <option value="2"
        <? if($_SESSION['cotizacionVersion'] ->prioridad == 2) echo "selected";?>
        >Presupuesto o requerimiento futuro</option>
                      <option value="3"
        <? if($_SESSION['cotizacionVersion'] ->prioridad == 3) echo "selected";?>
        >Sugerencia m&iacute;a</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">Moneda</td>
                    <td><table width="150" border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td width="20"><input name="tipo_moneda" type="radio" id="tipo_moneda_mx" value="0" <? if($_SESSION['cotizacionVersion'] ->tipo_moneda == 0) echo "checked";?> onChange="changeCurrency(this.value)" <? echo $esLectura;?>/></td>
                        <td width="21" class="texto_info_negro">MX</td>
                        <td width="21"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
                        <td width="20"><input type="radio" name="tipo_moneda" value="1" id="tipo_moneda_usa" <? if($_SESSION['cotizacionVersion'] ->tipo_moneda == 1) echo "checked";?> onChange="changeCurrency(this.value)" <? echo $esLectura;?> /></td>
                        <td width="30" class="texto_info_negro">USA</td>
                        <td width="74" class="texto_info_negro">(<? echo $_SESSION['dollar']?>)</td>
                      </tr>
                    </table></td>
                    
                    <? if($_SESSION['cotizacionVersion'] ->id_estatus >= 2) {?>
                    <td class="texto_info_negro">Estatus</td>
                    <td><span class="style52">
                      <select name="id_estatus" class="texto_info_negro_forma" id="id_estatus"  <? echo $esLectura;?> onBlur="autoSaveCotizacion();" >
                        <?php
	    $consulta  = "SELECT * FROM EstatusCotizaciones WHERE id >= 2";
        $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_estatus)>=1){
            while($array=mysql_fetch_assoc($resultado_estatus)) {
                ?>
                        <option <? if($_SESSION['cotizacionVersion'] ->id_estatus==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                        <?
            }
        }
     
		  ?>
                      </select>
                    </span></td>
                    <? }  else echo "<td>&nbsp;</td>
                    <td>&nbsp;</td>"?>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">Idioma</td>
                    <td><table width="150" border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td width="20"><input <? echo $esLectura;?> name="idioma" type="radio" id="idioma_ESP" value="ESP" <? if($_SESSION['cotizacionVersion'] ->idioma == 'ESP' || $_SESSION['cotizacionVersion'] ->idioma=="") echo "checked";?> onChange="changeLanguage(this.value)" /></td>
                        <td width="21" class="texto_info_negro">Espa&ntilde;ol</td>
                        <td width="21"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
                        <td width="20"><input <? echo $esLectura;?> type="radio" name="idioma" value="ENG" id="idioma_ING" <? if($_SESSION['cotizacionVersion'] ->idioma == 'ENG') echo "checked";?> onChange="changeLanguage(this.value)" /></td>
                        <td class="texto_info_negro">Ingl&eacute;s</td>
                      </tr>
                    </table></td>
                    <td class="texto_info_negro">Vigencia</td>
                    <td><input <? echo $esLectura;?>  name="vigencia" type="text" class="texto_info_negro_forma" id="vigencia" value="<? echo $_SESSION['cotizacionVersion'] ->vigencia;?>" size="25" maxlength="100" onBlur="autoSaveCotizacion();" /></td>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">Atención</td>
                    <td><input <? echo $esLectura;?> name="atencion" type="text" class="texto_info_negro_forma" id="atencion" value="<? echo $_SESSION['cotizacionVersion'] ->atencion;?>" size="25" maxlength="100" onBlur="autoSaveCotizacion();" /></td>
                    <td class="texto_info_negro">Referencia</td>
                    <td><input <? echo $esLectura;?>  name="referencia" type="text" class="texto_info_negro_forma" id="referencia" value="<? echo $_SESSION['cotizacionVersion'] ->referencia;?>" size="25" maxlength="100" onBlur="autoSaveCotizacion();" /></td>
                  </tr>
                </table></td>
              </tr>
              
                <?	  

if( count( $_SESSION['cotizacionVersion'] ->productos )!=0){
  $color ="#CCCCCC";
	$count=1;
	foreach ($_SESSION['cotizacionVersion'] ->productos as $n => $producto) {
            if( $producto -> cantidad != "" && intval($producto -> cantidad) != 0){
		?>
              
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="2"><table width="580" border="0" align="center" cellpadding="2" cellspacing="2" style=" max-width: 500px; ">
                  <tr>
                    <td width="50" class="texto_info_blanco">&nbsp;</td>
                    <td width="540" bgcolor="#DD1A22">
                      <div align="left" class="texto_info_blanco">
                        <div align="center">
                          <table width="536" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><div align="center">
                                <?
                if($_SESSION['cotizacionVersion'] ->idioma == 'ESP'){
                    if($producto->nombre!="")
                        $nombre=$producto->nombre;
                    else $nombre = "Sin descripci&oacute;n";
                    
                } else if($_SESSION['cotizacionVersion'] ->idioma == 'ENG'){
                    if($producto->descripcion!="")
                        $nombre=$producto->descripcion;
                    else $nombre = "Sin descripci&oacute;n";
                } else $nombre = "Sin descripci&oacute;n";
                    
                    
                    ?>
                    <a href="javascript:modificarProducto(<? echo "$producto->id,$producto->id_proveedor";?>);" class="texto_info_blanco"><? echo $nombre; ?></a>
                    <input <? echo $esLectura;?> type="hidden" id="conDescripcion<? echo $count;?>" value="si"onblur="autoSaveCotizacion();" />
                              </div>                                <div align="center"></div></td>
                              <td width="18"><div align="right"><a href="#" class="iframe" onClick="eliminarProducto(<? echo $n;?>);"><img src="images/cerrar.jpg" alt="" name="Image86" width="17" height="16" border="0" id="Image86" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image86','','images/cerrar_r.jpg',1)"  /></a></div></td>
                            </tr>
                          </table>
                        </div>
                    </div></td>
                  </tr>
                  <tr>
                    <td  width="50" valign="top" class="texto_info_negro"><table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center" valign="middle"><? if($producto->archivo_ficha_tecnica!=""){?>
      <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank"><img src="images/pdf.ico" alt="pdf" width="21" height="21" border="0" /></a>
      <? } else { echo "Sin Ficha Tecnica  ";}?>&nbsp;</td>
                      </tr>
                    </table></td>
                    <td><table width="537" border="0" cellspacing="2" cellpadding="0">
                      <tr>
                        <td width="85" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Proveedor</div></td>
                        <td width="66" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">P.U.</div></td>
                        <td width="63" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Cantidad</div></td>
                        <td width="60" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Desc. %</div></td>
                        <td width="65" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Subtotal</div></td>
                        <td width="160" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Notas Generales </div></td>
                      </tr>
                      <tr>
                        <td rowspan="2" class="texto_info_negro"><div align="center"><span class="texto_contenido">
                          <?
        echo $producto->nombre_proveedor;
    ?>
                        </span></div></td>
                        <td rowspan="2" class="texto_info_negro"><div align="center">
                          <input <? echo $esLectura;?> name="precio_unitario[]" type="hidden" class="numberTiny" id="precio_unitario<? echo $count?>" value="<? echo round($producto->precio,2);?>" size="6" maxlength="3" readonly="readonly" />
                        <? echo round($producto->precio,2);?>    <br /> <!--<?
	if($producto->tipo_moneda_usa==1)
		echo "original USA";
	else echo "original MXN";
	?>--></div></td>
                        <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                          <input <? echo $esLectura;?> name="cantidad[]" type="number" class="texto_info_negro numberTiny" id="cantidad<? echo $count?>" onChange="checkDescuento(<? echo $count;?>);" value="<? echo $producto->cantidad;?>" size="6" maxlength="3" onBlur="autoSaveCotizacion();" />
                        </div></td>
                        <td rowspan="2" class="texto_info_negro"><div align="center"><input <? echo $esLectura;?> name="descuento[]" type="number" class="texto_info_negro numberTiny" onChange="checkDescuento(<? echo $count;?>);" onBlur="autoSaveCotizacion();"  id="descuento<? echo $count?>" value="<? echo $producto->descuento*100;?>" size="6" maxlength="3" /></div></td>
                        <td rowspan="2" class="texto_info_negro"><div align="center">
                          <input <? echo $esLectura;?> name="subtotal[]" type="text" class="texto_info_negro numberTiny" id="subtotal<? echo $count;?>" value="<? echo round((1-($producto->descuento)) * $producto->cantidad * $producto->precio,2);?>" readonly/>
                        </div></td>
                        <td rowspan="2" class="texto_info_negro"><div align="center"><textarea onBlur="autoSaveCotizacion();" name="comentarioProducto[]" rows="2" class="texto_info_negro_chico" id="comentarioProducto<? echo $count?>" style="width:100%;"  <? echo $esLectura;?> ><? echo $producto->comentario;?></textarea>
</div></td>
                      </tr>
                      <tr>
                        <td align="center" bgcolor="#E3E3E3" class="texto_info_negro_chico"><em>
                          <?
        echo $producto->unidad_metrica;
    ?>
                        </em></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              
  <?
			   $count=$count+1;
            }
	}
        
}
	
		?>
              
              <tr>
                
              </tr>
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="20" /></span></td>
              </tr>
              
   <? if(!$vistaLectura){?>
              <tr>
                <td colspan="2"><label>
                  <input <? echo $esLectura;?> name="Agregar" type="submit" id="Agregar" value="Agregar productos" class="texto_info_negro" />
                </label></td>
              </tr>
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="2"><p>
                  <input <? echo $esLectura;?> name="Agregar2" type="button" class="texto_info_negro" id="Agregar5" onClick="abrir('cambia_producto_especial.php')" value="Agregar producto especial"/>
                  </label>
                </p></td>
              </tr>
              <? }?>
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="20" /></span></td>
              </tr>
              <tr>
                <td colspan="2"><table width="450" border="0" align="center" cellpadding="2" cellspacing="2">
                  
                  <tr>
                    <td width="148" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Libre a bordo</div></td>
                    <td width="296" class="texto_info_negro"><div align="center"><span class="style8">
                      <textarea name="LAB"  <? echo $esLectura;?>  rows="2" class="texto_info_negro" id="LAB" style="width:100%"><? echo $_SESSION['cotizacionVersion'] ->LAB;?></textarea>
                    </span></div></td>
                  </tr>
                  <tr>
                    <td bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">T&eacute;rminos de entrega</div></td>
                    <td class="texto_info_negro"><div align="center"><span class="style8">
                      <textarea name="terminos_entrega" rows="2" onBlur="autoSaveCotizacion();"   <? echo $esLectura;?> class="texto_info_negro" id="terminos_entrega" style="width:100%"><? echo $_SESSION['cotizacionVersion'] ->terminos_entrega;?></textarea>
                    </span></div></td>
                  </tr>
                  <tr>
                    <td bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Otros</div></td>
                    <td class="texto_info_negro"><div align="center"><span class="style5">
                      <textarea name="comentarioCotizacion"  onblur="autoSaveCotizacion();" rows="2" class="texto_info_negro" <? echo $esLectura;?>  id="comentarioCotizacion" style="width:100%"><? echo $_SESSION['cotizacionVersion'] ->notas_adicionales;?></textarea>
                    </span></div></td>
                  </tr>
                  <tr>
                    <td bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Condiciones de pago</div></td>
                    <td class="texto_info_negro"><div align="center">
                      <?
        if(isset($_SESSION['cotizacionVersion'] ->id_cliente)){
            $cliente = new Cliente();
            $cliente->get($_SESSION['cotizacionVersion'] ->id_cliente);
            echo $cliente->condiciones_pago;
        } else {
            echo "<i>Guarda primero la cotización para ver las condiciones de pago</i>";
        }
        ?></div></td>
                  </tr>
                  
                </table><img src="images/spacer.gif" alt="" width="20" height="10" />
                  <table  width="450" border="0" align="center" cellpadding="2" cellspacing="2">
                    <tr>
                      <td colspan="3" bgcolor="#E91B25"><div align="center" class="texto_info_blanco">TOTAL</div></td>
                    </tr>
                    <tr>
                      <td align="center" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">
                      <input name="conIva"  type="checkbox" class="texto_info_negro" id="conIva" onClick="agregarIva(this)" value="1"  
                            <? echo $esLectura;?>
                            <?
                            if($_SESSION['cotizacionVersion'] ->con_iva == 1 || !isset($_SESSION['cotizacionVersion'] )) 
                                echo "checked";
                            ?> />IVA</div></td>
                      <td align="center" bgcolor="#E3E3E3" class="texto_info_negro"><div align="center">Subtotal</div></td>
                      <td align="center" bgcolor="#E3E3E3" class="texto_info_negro">Total</td>
                    </tr>
                    <tr>
                      <td align="center" class="texto_info_negro"><div align="center">
                        <input <? echo $esLectura;?> name="iva" type="text" class="texto_info_negro numberMedium" id="iva" value="<? 
		if($_SESSION['cotizacionVersion'] ->con_iva == 1 )
		echo round($_SESSION['cotizacionVersion'] ->iva , 2);?>" readonly="readonly"/>
                      </div></td>
                      <td align="center" class="texto_info_negro"><input <? echo $esLectura;?> name="subtotal" type="text" class="texto_info_negro numberMedium" id="subtotal" value="<? echo round($_SESSION['cotizacionVersion'] ->subtotal,2);?>" readonly="readonly"/></td>
                      <td align="center" class="texto_info_negro"><input <? echo $esLectura;?> name="total" type="text" class=" texto_info_negro numberMedium" id="total" value="<? echo $_SESSION['cotizacionVersion'] ->con_iva > 0 ? round($_SESSION['cotizacionVersion'] ->total,2) :  round($_SESSION['cotizacionVersion'] ->subtotal,2);?>" readonly="readonly"/></td>
                    </tr>
                  </table>
                <p>&nbsp;</p></td>
              </tr>
              <tr>
                <td colspan="2"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              
   <? if(!$vistaLectura){?>
              <tr>
                <td colspan="2" align="left"><div align="left">
                      <input <? echo $esLectura;?> name="crearVersion" type="submit" class="texto_info_negro" id="crearVersion" value="Crear Version" />
               
                </div></td>
              </tr>
              <tr>
                <td colspan="2" align="left"><div align="center">
                  <blockquote>
                    <p>
                      <input <? echo $esLectura;?> name="guardarCotizacion" type="submit" class="texto_info_negro" id="guardarCotizacion" onClick="return validar();" value="Guardar Cotización" />
                    </p>
                    <p>
                      <input name="previsualizar" type="submit" id="previsualizar" value="previsualizar" onClick="return validar();"  class="texto_info_negro"/>
                    </p>
                  </blockquote>
                </div></td>
              </tr>
              <? }?>
            </table></td>
            <td width="14" valign="top"><img src="images/sombra_productos_gris.jpg" width="14" height="553" /></td>
            <td valign="top" bgcolor="#e5e5e6" width="290px" style="max-width:290px"><table width="290" border="0" cellspacing="0" cellpadding="0" style="max-width:290px">
              <tr width="290px" style="max-width:290px">
                <td width="290"><img src="images/spacer.gif" width="20" height="16" /></td>
              </tr>
              
   <? if(!$vistaLectura){?>
              <tr>
                <td class="texto_info_blanco" style="background-image:url(images/bkg_1.jpg); padding:5px; font-weight:bold">COMENTARIOS</td>
              </tr>
              <tr>
                <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
              </tr>
              <tr>
                <td align="left"><table width="200" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" height="22" background="images/boton_submenu_2.jpg"><table border="0" align="left" cellpadding="4" cellspacing="0">
                      <tr>
                        <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                        <td class="texto_menu_slice"><a href="#" class="texto_menu_slice" onClick="saveCotizacion(abrir3,'cambia_comentario_cotizacion.php');" >AGREGAR COMENTARIO</a></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="center">
                <div style="width:100%; height:210px; overflow:hidden"><iframe 
                src="
                <? 
                if( isset( $_SESSION['cotizacionVersion']  ) ){
                    echo "comentarios_cotizacion.php?id=".$_SESSION['cotizacionVersion'] ->id;
                }
                ?> "
                    width="100%" height="200px" id="comentarios_iframe" style="border:none" ></iframe></div>
                </td>
              </tr>
              <? } ?>
              <tr>
                <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
              </tr>
              <tr>
                <td class="texto_info_blanco" style="background-image:url(images/bkg_1.jpg); padding:5px; font-weight:bold">ARCHIVOS</td>
              </tr>
              <tr>
                <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
              </tr>
              <tr>
                <td align="left">
                
   <? if(!$vistaLectura){?>
                <table width="146" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" height="22" background=""><table width="146" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                            <td class="texto_menu_slice"><a href="#" class="texto_info_blanco" 
                        onclick="saveCotizacion(abrir3,'agregar_archivo.php');"> AGREGAR ARCHIVO</a></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                <? }?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;
                
                <?
if(isset($_SESSION['cotizacionVersion'] )){ ?>
<div id="containerArchivos" style="width:100%">

      <?  foreach ($_SESSION['cotizacionVersion'] ->archivos as $key => $archivo) {
        ?><a href="<? echo $archivo['location'];?>" target="_blank">
          <div id="archivo<? echo $key;?>" style="height:40px; margin:5px; float:left;width: 200px;margin-left: 15px;">
            <span style="float:right; width:84%">
              <? echo $archivo['nombre_real'];?>              </span><img src="images/archivo.png" width="32" border="0" style="float:right" /> </div>
          </a>
          <? if(!$vistaLectura){?>
<a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','images/cerrar_r.jpg',1)" class="iframe" onClick="eliminarArchivo(<? echo "$key";?>);"><img src="images/cerrar.jpg" alt="" name="Image1" width="17" height="16" border="0" id="Image1" style="float:left"/></a>
<? } ?>
        <?
        }
        ?>
      </div>
      <? }//fin if check cotizacionsession ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              
   <? if(!$vistaLectura){?>
              <tr>
                <td align="left"><table width="146" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                            <td class="texto_menu_slice"><a href="#" class="texto_info_blanco" 
                                                            onClick="saveCotizacion(abrir2,'iframe800_handler.php?src=adm_cotizaciones_versiones.php');"> VER VERSIONES</a></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <? } ?>
              <tr>
                <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td align="left"><table width="146" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                        <td class="texto_menu_slice"><a href="#" class="texto_menu_slice"
                        onClick="saveCotizacion(enviarCotizacion,'');">ENVIAR A ...</a></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          
        </table>
</form>
<form action="" method="post" name="formBorrarProductoCarrito" id="formBorrarProductoCarrito"></form>

</div>
</body>
</html>