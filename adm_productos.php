<?
//ini_set('display_errors','1');
include_once "Usuario.php";
include_once "Producto.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['todos']);

if(isset($_POST['idPBorrar'])){
    $producto = new Producto();
    $producto->get(intval($_POST['idPBorrar']));
    $producto->delete();
}


if($_REQUEST['buscar']!=''          || $_REQUEST['codigo_buscar']!=''
        || $_REQUEST['familia']!='' || $_REQUEST['proveedor']!=''
        || $_REQUEST['linea_descuento']!=''
        || $_SESSION['buscadorCotizaciones']['codigo_buscar']!=''
        || $_SESSION['buscadorCotizaciones']['familia']!=''
        || $_SESSION['buscadorCotizaciones']['proveedor']!=''
        || $_SESSION['buscadorCotizaciones']['linea_descuento']!='')
    {
    
    $_SESSION['buscadorCotizaciones']['nombre'] = isset($_POST['nombre']) ? $_POST['nombre'] : $_SESSION['buscadorCotizaciones']['nombre'];
    $_SESSION['buscadorCotizaciones']['familia'] = isset($_POST['familia']) ? $_POST['familia'] : $_SESSION['buscadorCotizaciones']['familia'];
    $_SESSION['buscadorCotizaciones']['proveedor'] = isset($_POST['proveedor']) ? $_POST['proveedor'] : $_SESSION['buscadorCotizaciones']['proveedor'];
    $_SESSION['buscadorCotizaciones']['codigo_buscar'] = isset($_POST['codigo_buscar']) ? $_POST['codigo_buscar'] : $_SESSION['buscadorCotizaciones']['codigo_buscar'];
    $_SESSION['buscadorCotizaciones']['linea_descuento'] = isset($_POST['linea_descuento']) ? $_POST['linea_descuento'] : $_SESSION['buscadorCotizaciones']['linea_descuento'];
	
     
    $nombre         = $_SESSION['buscadorCotizaciones']['nombre'];
    $familia        = $_SESSION['buscadorCotizaciones']['familia'];
    $codigo_buscar  = $_SESSION['buscadorCotizaciones']['codigo_buscar'];
    $proveedor      = $_SESSION['buscadorCotizaciones']['proveedor'];
    $linea_descuento = $_SESSION['buscadorCotizaciones']['linea_descuento'];
    
     $where=" Proveedores.id<>7 AND";
    if($nombre!=""){
        $where.= " Productos.nombre LIKE '%$nombre%' AND ";
    }
    if($familia!= ""){
        $where.= " FamiliaCotizador.id = '$familia' AND ";
    }
    if($codigo_buscar!= ""){
        $where.= " Productos.codigo LIKE '%$codigo_buscar%' AND ";
    }
    if($proveedor!=""){
        $where .= " Proveedores.id = $proveedor AND ";
    }
    if($linea_descuento!=""){
        $where .= " Productos.id_catalogo_productos = $linea_descuento AND ";
    }
}
	
if($_POST['campo'] != "" ){
    $campo = $_SESSION['filter']['campo'] = $_POST['campo'];
    $sentido = $_SESSION['filter']['sentido'] = $_POST['sentido'];
    if($sentido == "")
        $sentido = "DESC";
    
    switch ($campo) {
        case 'nombre_ingles':
            $orderBy = " ORDER BY nombre_ingles $sentido ";
            break;
        case 'nombre':
            $orderBy = " ORDER BY nombre $sentido ";
            break;
        case 'codigo_interno':
            $orderBy = " ORDER BY codigo_interno $sentido ";
            break;
        case 'precio':
            $orderBy = " ORDER BY precio $sentido ";
            break;
        case 'codigo_proveedor':
            $orderBy = " ORDER BY codigo_proveedor $sentido ";
            break;
        case 'proveedor':
            $orderBy = " ORDER BY proveedor $sentido ";
            break;
        case 'archivo_ficha_tecnica':
            $orderBy = " ORDER BY archivo_ficha_tecnica $sentido ";
            break;
        default:
            $orderBy = "";
            break;
    }
    
}

$consulta  = "SELECT UPPER(Productos.nombre) AS nombre, UPPER(Productos.descripcion) AS nombre_ingles, 
        Productos.id, CatalogoProductos.nombre AS catalogo, precio, archivo_ficha_tecnica,
        Proveedores.nombre AS proveedor, FamiliaCotizador.nombre AS familia, 
        CONCAT(Proveedores.prefijo, Productos.codigo) AS codigo_interno, Productos.numero_consecutivo,
        Productos.codigo AS codigo_proveedor, Productos.tipo_moneda_usa, Productos.stock
    FROM Productos
    LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor
    LEFT OUTER JOIN CatalogoProductos ON CatalogoProductos.id = Productos.id_catalogo_productos
    LEFT OUTER JOIN FamiliaCotizador ON FamiliaCotizador.codigo = Productos.codigo_familia
    WHERE $where 1
    $orderBy LIMIT 0,100";
//echo"$consulta";
$resultado = mysql_query($consulta) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error()); 



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
-->
<!--
.style51 {font-size: 18}
-->

.agregados {
	font-family: sans-serif;
	font-size: x-small;
	background-color: #7FFF00;
}

.numberTiny {	width: 60px;
	text-align: center;
}

.numberMedium{	
	text-align: center;
}
</style>

  
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="colorbox/jquery.colorbox-min.js"></script>
<!--hedaer fixed-->
<script src="colorbox/jquery.fixedheadertable.js"></script>
<!--<script src="colorbox/demo.js"></script>-->
<link href="fix/960.css" rel="stylesheet" media="screen" />
<link href="fix/defaultTheme.css" rel="stylesheet" media="screen" />
<link href="fix/myTheme.css" rel="stylesheet" media="screen" />

<script>
$(document).ready(function(){
    
                //fixheader    
                $('#myTable01').fixedHeaderTable({  
                    caption : 'My header is fixed !',
                    height : parseInt(screen.height * 68/100)
                });

                $('#myTable01').fixedHeaderTable('show', 1000);
                //Examples of how to assign the ColorBox event to elements

                $(".iframe").colorbox({iframe:true,width:"800", height:"553",transition:"fade", opacity:0.5});
				
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
	function abrir(ir, isSizeMini)
	{
		if(isSizeMini){
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"250",transition:"fade", scrolling:false, opacity:0.5});
		} else {
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:false, opacity:0.5});
		}
	}
function cerrarV(){
	$.fn.colorbox.close();
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function validar(){
	if( $('#codigo_buscar').val() == "" &&
	   $('#nombre').val() == "" &&
	   $('#familia').val() == "" &&
	   $('#proveedor').val() == "" &&
	   $('#linea_descuento').val() == "" ){
                alert('Busca por alguna caracter\u00edstica');
                return false;
	}
}


function ordenar(campo,sentido){
    var form = document.getElementById('formBuscar');
	var element = document.createElement('input');
	element.name = 'campo';
	element.type = 'hidden';
	element.value = campo;
	form.appendChild(element);
	
	element = document.createElement('input');
	element.name = 'sentido';
	element.type = 'hidden';
	element.value = sentido;
	form.appendChild(element);
	form.submit()
}
function eliminarProducto(id){
    var elem = document.createElement('input');
    elem.name='idPBorrar';
    elem.value = id;
    $("#formAgregarProducto").append(elem);
    $("#formAgregarProducto").submit();
}
function eliminar(id,nombre){
    if(confirm('Desdeas borrar el producto '+nombre+'?')){
        if(window.XMLHttpRequest) xmlhttp = new XMLHttpRequest();
        else xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState==4 && xmlhttp.status==200){
                var hasCotizaciones = parseInt(xmlhttp.responseText);
                console.log(hasCotizaciones);
                if(hasCotizaciones){
                    eliminarProducto(id);
                } else if( confirm( 'Este producto tiene cotizaciones asociadas. Deseas continuar?' ) ){
                    eliminarProducto(id);
                }
            }
        }
        xmlhttp.open("POST","revisar_producto_tiene_cotizaciones.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("idPBorrar="+id);
    }
    
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function showFilter(id){
    $("#divFiltrar").slideToggle( "slow" );
 }
</script>
<link rel="stylesheet" href="divFiltrar.css" />
<style type="text/css">
<!--
.style54 {font-size: 11px}
.style52 {font-size: 12}
.style52 {font-size: 12}
#divFiltrar {    <? $displayDivFiltros = !empty($_POST['buscar']) ? "none": "block"; ?>
    display: <? echo $displayDivFiltros;?>;
}
-->
</style>
</head>

<body onLoad="MM_preloadImages('images/cerrar_r.jpg','images/<? 
if($campo == 'cliente'){ if($sentido=="ASC") echo "descending"; else echo "ascending";} ?>.png','images/<?
                            if($campo == 'cliente'){ 
                                if($sentido=="ASC") echo "descending"; 
                                else echo "ascending";
                            }
                                ?>.png','images/<?
                    if($campo == 'cliente'){ 
                                            if($sentido=="ASC") echo "descending"; 
                                            else echo "ascending";}
                                            ?>.png','images/<?
                    if($campo == 'costoTotal'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png','images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png','images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png','images/<?
                    if($campo == 'usuario'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png','images/<?
                      $campoAux = 'proveedor';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png')">
<table width="890"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3"><div align="left" class="texto_chico_gris"> <img src="images/spacer.gif" alt="" width="20" height="16" /> </div></td>
      </tr>
      <tr>
        <td width="18%"><? if($_SESSION['usuario']->id_rol <= $_SESSION['accesos']['supervisor'] ){ ?>
          <table width="146" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                  <td class="texto_menu_slice"><a href="cambia_producto.php" class="texto_menu_slice iframe">NUEVO PRODUCTO</a></td>
                </tr>
              </table></td>
            </tr>
          </table>
          <? }?></td>
        <td width="20%"><? if($_SESSION['usuario']->id_rol <= $_SESSION['accesos']['supervisor'] ){ ?>
          <table width="146" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                  <td class="texto_menu_slice"><a href="importar_productos.php" class="texto_menu_slice iframe">IMPORTAR</a></td>
                </tr>
              </table></td>
            </tr>
          </table>
          <? } ?></td>
		  <td width="20%">
		  	<table width="146" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                  <td class="texto_menu_slice"><a href="datos.php" class="texto_menu_slice iframe">LISTA DESCUENTOS</a></td>
                </tr>
              </table></td>
            </tr>
          </table>
		  </td>
        <td width="22%" align="right"><button class="texto_info_negro" onclick="showFilter(null)" type="button" >Filtrar</button></td>
      </tr>
      <tr>
        <td colspan="3"><span class="texto_chico_gris" style="float:right"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
      </tr>
      <tr>
        <td colspan="3"><? if($resultado){?>
<table width="100%"   cellpadding="0" cellspacing="0"  id="myTable01">
                <thead>
                  <tr>
                    <th width="20" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">&nbsp;</th>
                    <th width="106" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                      $campoAux = 'codigo_interno';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onclick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')">
                      <div align="center">
                        <? if($campo == $campoAux){ ?>
                        <img src="images/<?
					if($campo == $campoAux){ 
                                            if($sentido=="ASC") echo "ascending"; 
                                            else echo "descending";}
                                ?>.png" name="filter" width="20" height="20" border="0" id="filter" />
                        <? } ?>
                        Codigo Interno</div>
                    </a></th>
                    <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    $campoAux = 'nombre';
                    if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onclick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')">
                      <div align="center">
                        <? if($campo == $campoAux){ ?>
                        <img src="images/<?
					if($campo == $campoAux){ 
                                            if($sentido=="ASC") echo "ascending"; 
                                            else echo "descending";}
                                ?>.png" name="filter" width="20" height="20" border="0" id="filter" />
                        <? } ?>
                        Nombre Español</div>
                    </a></th>
                    <th width="63" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                      $campoAux = 'precio';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onclick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')">
                      <div align="center">
                        <? if($campo == $campoAux){ ?>
                        <img src="images/<?
					if($campo == $campoAux){ 
                                            if($sentido=="ASC") echo "ascending"; 
                                            else echo "descending";}
                                ?>.png" name="filter" width="20" height="20" border="0" id="filter" />
                        <? } ?>
                        Precio AG </div>
                    </a></th>
                    <th width="98" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                      $campoAux = 'proveedor';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onclick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')"> </a>
                      <div align="center"> <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('filter1','','images/<? $campoAux = 'proveedor';
                      if($campo == $campoAux){
                          if($sentido=="ASC") echo "descending";
                          else echo "ascending";
                          }?>.png',1)" class="texto_info_blanco" onClick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')">
                        <? if($campo == $campoAux){ ?>
                        <img src="images/<?
					if($campo == $campoAux){ 
                                            if($sentido=="ASC") echo "ascending"; 
                                            else echo "descending";}
                                ?>.png" name="filter1" width="20" height="20" border="0" id="filter1" />
                        <? } ?>
                        Proveedor</a></div>
                      <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('filter','','images/<?
                      $campoAux = 'proveedor';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onClick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')"></a></th>
                    <th width="130" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                      $campoAux = 'codigo_proveedor';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onclick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')">
                      <div align="center">
                        <? if($campo == $campoAux){ ?>
                        <img src="images/<?
					if($campo == $campoAux){ 
                                            if($sentido=="ASC") echo "ascending"; 
                                            else echo "descending";}
                                ?>.png" name="filter" width="20" height="20" border="0" id="filter" />
                        <? } ?>
                        Codigo Proveedor</div>
                    </a></th>
                    <th width="20" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                      $campoAux = 'archivo_ficha_tecnica';
                      
                        if($campo == $campoAux){ 
                          if($sentido=="ASC") echo "descending"; 
                          else echo "ascending";
                        }?>.png',1)" class="texto_info_blanco" onclick="ordenar('<? echo $campoAux?>','<?
                          if($campo == $campoAux){
                              if($sentido=="ASC") echo "DESC"; 
                              else echo "ASC";    
                          }?>')">
                      <div align="center">
                        <? if($campo == $campoAux){ ?>
                        <img src="images/<?
					if($campo == $campoAux){ 
                                            if($sentido=="ASC") echo "ascending"; 
                                            else echo "descending";}
                                ?>.png" name="filter" width="20" height="20" border="0" id="filter" />
                        <? } ?>
                    FT</div>
                    </a></th>
                    <th width="47" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Stock</th>
                    <!-- <th width="34" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/cerrar_r.jpg',1)"></a></th>-->
                  </tr>
                </thead>
                <tbody>
                  <?	  

	$count=1;
	$color = 'white';
	$tm="";

        $permisoEliminar = hasPermiso($_SESSION['accesos']['administrador'],$_SESSION['accesos']['compras']);
        
	while(@mysql_num_rows($resultado)>=$count)
	{
		$res=mysql_fetch_assoc($resultado);
		if($res['tipo_moneda_usa']=="1")
			$tm="USD";
		else
			$tm="MXP";
		?>
                  <tr bgcolor="<?echo"$color";?>" >
                    <td class="texto_info_negro"><? if($permisoEliminar){ ?>
                      <img src="images/cerrar.jpg" alt="" name="Image22" width="17" height="16" border="0" id="Image1"
                    onClick="eliminar(<? echo $res['id'];?>,'<? echo $res['codigo_interno'];?>')" style="cursor:pointer">
                      <? }?></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                      <div align="left"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['codigo_interno'];?></a></div>
                    </div></td>
                    <td class="texto_info_negro"><div align="center"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['nombre'];?></a></div></td>
                    <td class="texto_info_negro"><div align="right"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe">$<? echo	getFormatedNumberForMoney($res['precio']);?> <?echo"$tm";?> </a></div></td>
                    <td class="texto_info_negro"><div align="center"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['proveedor'];?></a></div></td>
                    <td class="texto_info_negro"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['codigo_proveedor'];?></a></td>
                    <td class="texto_info_negro"><div align="left">
                      <? if($res['archivo_ficha_tecnica']!=""){?>
                      <a href="<? echo $res['archivo_ficha_tecnica'];?>" target="_blank"><img src="images/pdf.ico" alt="pdf" width="20" border="0" /></a>
                      <? }?>
                    </div></td>
                    <td align="center" valign="middle" class="texto_info_negro"><? echo $res['stock']?></td>
                    <!-- <td class="texto_info_negro">
                    
                    <div align="center"><span style="float:right"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image86','','images/cerrar_r.jpg',1)" onclick="borrarCartera(<? echo $idCartera?>);"><img src="images/cerrar.jpg" alt="" name="Image86" width="17" height="16" border="0" id="Image86" /></a></span></div> </td>-->
                  </tr>
                  <?
			   $count=$count+1;
			   if($color == 'white')
			   $color = '#E3E3E3';
			   else $color = 'white';
	}
	?>
                </tbody>
              </table>
<!--            </div>
          </div>-->
          <? } ?>
          <form method="POST" name="formAgregarProducto" id="formAgregarProducto">
          </form></td>
      </tr>
    </table></td>
    <td width="14" valign="top"></td>
    <td width="" align="center" valign="top"><div id="divFiltrar">
      <form id="formBuscar" name="formBuscar" method="post" action="">
        <table border="0" align="center" cellpadding="0" cellspacing="2">
          <tr>
            <td class="texto_info_negro">Codigo</td>
            <td><span class="style9">
              <input name="codigo_buscar" type="text" class="texto_info_negro_forma" id="codigo_buscar" value="<? echo $codigo_buscar;?>" />
            </span></td>
          </tr>
          <tr>
            <td width="61" class="texto_info_negro">Nombre</td>
            <td width="228"><span class="style9">
              <input name="nombre" type="text" class="texto_info_negro_forma" value="<? echo $nombre;?>" id="nombre" />
            </span></td>
          </tr>
          <tr>
            <td class="texto_info_negro">Familia</td>
            <td><span class="style51">
              <select name="familia" class="texto_info_negro_forma" id="familia" >
                <option value="">- -</option>
                <?php
	    $consulta  = "SELECT * FROM FamiliaCotizador ORDER BY nombre";
        $resultado_familia= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_familia)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_familia)) {
                ?>
                <option <? if($familia==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre']." (".$array['codigo'].")";?></option>
                <?
            }
        }
     
		  ?>
              </select>
            </span></td>
          </tr>
          <tr>
            <td class="texto_info_negro">Proveedor</td>
            <td><span class="style51">
              <select name="proveedor" class="texto_info_negro_forma" id="proveedor" style="width:150px">
                <option value="">- -</option>
                <?php
	    $consulta  = "SELECT * FROM Proveedores where Proveedores.id<>7 ORDER BY nombre";
        $resultado_proveedor = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_proveedor)>=1){
            while($array=mysql_fetch_assoc($resultado_proveedor)) {
                ?>
                <option <? if($proveedor==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                <?
            }
        }
     
		  ?>
              </select>
            </span></td>
          </tr>
          <tr>
            <td class="texto_info_negro">Linea de descuento</td>
            <td><span class="style51">
              <select name="linea_descuento" class="texto_info_negro_forma" id="linea_descuento"  style="width:150px">
                <option value="">- -</option>
                <?php
	    $consulta  = "SELECT CatalogoProductos.id, CatalogoProductos.nombre, Proveedores.prefijo FROM CatalogoProductos
		LEFT OUTER JOIN Proveedores ON Proveedores.id = CatalogoProductos.id_proveedor
		ORDER BY Proveedores.nombre, CatalogoProductos.nombre";
        $resultado_proveedor = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_proveedor)>=1){
            while($array=mysql_fetch_assoc($resultado_proveedor)) {
                ?>
                <option <? if($linea_descuento==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['prefijo']."-".$array['nombre'];?></option>
                <?
            }
        }
     
		  ?>
              </select>
            </span></td>
          </tr>
          <tr>
            <td colspan="2" class="texto_info_negro"><!--<label>
                  Mostrar Versiones
                      <input name="mostrar_versiones" type="checkbox" id="mostrar_versiones" value="TRUE" />
                    </label>--></td>
          </tr>
          <tr>
              <td class="texto_info_negro" colspan="2">   <input type="button" value="Cerrar" onclick="showFilter(null)"  class="texto_info_negro"/>
          <input name="buscar" type="submit" class="texto_info_negro" id="buscar" value="Buscar" onClick="return validar();" />
      </td>
          </tr>
        </table>
      </form>
    </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>