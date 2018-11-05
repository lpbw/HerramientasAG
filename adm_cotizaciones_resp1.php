<?
//ini_set('display_errors', 'On');
ini_set('session.gc_maxlifetime', 86400);

error_reporting(0);
include_once "Usuario.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['soporte']);
 prepareSessionVariablesForEditCotizacion();
  
if($_SESSION['usuario']->id_rol != $_SESSION['accesos']['administrador'] 
        && $_SESSION['usuario']->id_rol != $_SESSION['accesos']['mostrador'] && $_SESSION['usuario']->id_rol != $_SESSION['accesos']['soporte'] ){
    if($_SESSION['usuario']->id_rol == $_SESSION['accesos']['supervisor'] ){
        $vendedores = $_SESSION['usuario']->getVendedoresSubordinados();
        $count = 1;
        $vendedoresQuery = " OR ";
        foreach ($vendedores as $vendedor) {
            $vendedoresQuery .= "Usuarios.id = ".$vendedor['id'];
            if(count($vendedores)!=$count){
                $vendedoresQuery.=" OR ";
            }
            $count++;
        }
        if($vendedoresQuery == " OR ")
            $vendedoresQuery = "";
    }
}

 

if($_POST['buscar']!='' || $_REQUEST['id_cliente']!=''){
    $usuario =  $_SESSION['buscador']['usuario_buscar'] = $_POST['usuario_buscar'];
    $estatus = $_SESSION['buscador']['id_estatus'] = $_POST['id_estatus'];
    $cliente = $_SESSION['buscador']['cliente_buscar'] = $_POST['cliente_buscar'];
    $folio = $_SESSION['buscador']['folio_buscar'] = $_POST['folio_buscar'];
    $contacto =$_SESSION['buscador']['contacto_buscar'] =  $_POST['contacto_buscar'];
    $mostrar_versiones = $_SESSION['buscador']['mostrar_versiones'] = $_POST['mostrar_versiones'];
    
     
    if($contacto!=""){
        $where.= " Contactos.nombre_contacto LIKE '%$contacto%' AND ";
    }
    if($cliente!=""){
        $where.= " Clientes.alias LIKE '%$cliente%' AND ";
    }
    if($folio!=""){
        $where.= " Cotizaciones.id LIKE '%$folio%' AND ";
    }
    if($usuario!=""){
        $where.= " Usuarios.nombre LIKE '%$usuario%' AND ";
    }
    if($estatus!=""){
        $where.= " Cotizaciones.id_estatus = $estatus AND ";
    }
    if( $_REQUEST['id_cliente']!=""){
        $where.= " Cotizaciones.id_cliente = ". $_REQUEST['id_cliente']." AND ";
    }
    
    $_SESSION['where_buscador'] = $where;
    
} 


if( isset($_POST['mostrarGanPerd']) )
    $_SESSION['mostrarGanPerd'] = $_POST['mostrarGanPerd'];
else {
    unset( $_SESSION['mostrarGanPerd'] );
    $noMostrarGanPerd = " Cotizaciones.id_estatus < 4 AND ";
}

if( isset($_POST['mostrarMas100']) ){
    $_SESSION['mostrarMas100'] = $_POST['mostrarMas100'];
} else {
    unset( $_SESSION['mostrarMas100'] );
    $noMostrarMas100 = " LIMIT 0,100 ";
}


if($_POST['campo'] != "" ){
    $campo = $_SESSION['filter']['campo'] = $_POST['campo'];
    $sentido = $_SESSION['filter']['sentido'] = $_POST['sentido'];
    if($sentido == "")
        $sentido = "DESC";
    switch ($campo) {
        case 'cliente':
            $orderBy = " ORDER BY nombre_cliente $sentido ";
            break;
        case 'costoTotal':
            $orderBy = " ORDER BY total $sentido ";
            break;
        case 'estatus':
            $orderBy = " ORDER BY id_estatus $sentido ";
            break;
        case 'usuario':
            $orderBy = " ORDER BY usuarioAsignado $sentido ";
            break;
		case 'contacto':
            $orderBy = " ORDER BY Contactos.nombre_contacto $sentido ";
            break;
        case 'folio':
            $orderBy = " ORDER BY Cotizaciones.id $sentido ";
            break;
        case 'prioridad':
            $orderBy = " ORDER BY Cotizaciones.prioridad $sentido ";
            break;
        default:
            $orderBy = "";
            break;
    }
    $_SESSION['orderBy_buscador'] = $orderBy;
}
$orderBy =$_SESSION['orderBy_buscador'];
$campo = $_SESSION['filter']['campo'];
$sentido = $_SESSION['filter']['sentido'];

    if($mostrar_versiones == ""){
        $mostrar_versiones = "FALSE";
    } else $mostrar_versiones = "TRUE";

    



        $consulta  = "SELECT Cotizaciones.id, CONCAT(alias,' (' ,codigo,')') AS nombre_cliente, 
                        IF( Cotizaciones.tipo_moneda = 1, Cotizaciones.total * ".$_SESSION['dollar'].", Cotizaciones.total) AS total,
                        Usuarios.nombre AS usuarioAsignado, 
                        EstatusCotizaciones.nombre AS estatus, EstatusCotizaciones.id AS id_estatus, 
                        Cotizaciones.id_version, Cotizaciones.prioridad, Cotizaciones.tipo_moneda, Contactos.nombre_contacto
                    FROM Cotizaciones
                    LEFT OUTER JOIN Clientes ON Cotizaciones.id_cliente = Clientes.id
					LEFT OUTER JOIN Contactos ON Cotizaciones.id_contacto = Contactos.id
                    LEFT OUTER JOIN Usuarios ON Cotizaciones.id_usuario = Usuarios.id
                    LEFT OUTER JOIN EstatusCotizaciones ON EstatusCotizaciones.id = Cotizaciones.id_estatus
                    INNER JOIN CarteraClientes_Usuarios on Clientes.id_cartera = CarteraClientes_Usuarios.id_cartera_clientes
                    WHERE Cotizaciones.es_version = $mostrar_versiones 
                        AND  (CarteraClientes_Usuarios.id_usuario = ".$_SESSION['usuario']->id.") 
                        AND {$_SESSION['where_buscador']} {$noMostrarGanPerd} 1
                    GROUP BY Cotizaciones.id
                    $orderBy $noMostrarMas100";
                        
        $_SESSION['whereInnerJoin_NextBack_cotizaciones']="
                   LEFT OUTER JOIN Clientes ON Cotizaciones.id_cliente = Clientes.id
					LEFT OUTER JOIN Contactos ON Cotizaciones.id_contacto = Contactos.id
                    LEFT OUTER JOIN Usuarios ON Cotizaciones.id_usuario = Usuarios.id
                    LEFT OUTER JOIN EstatusCotizaciones ON EstatusCotizaciones.id = Cotizaciones.id_estatus
                    INNER JOIN CarteraClientes_Usuarios on Clientes.id_cartera = CarteraClientes_Usuarios.id_cartera_clientes
                    WHERE Cotizaciones.es_version = $mostrar_versiones 
                        AND  (CarteraClientes_Usuarios.id_usuario = ".$_SESSION['usuario']->id.") 
                        AND {$_SESSION['where_buscador']} {$noMostrarGanPerd} 1
                    GROUP BY Cotizaciones.id
                    $orderBy";
       // echo"$consulta";
        $resultado = mysql_query($consulta) or print("Error en buscador");// . mysql_error());


function prepareSessionVariablesForEditCotizacion(){
    $_SESSION['cotizacion']="";
    $_SESSION['carrito']="";
    unset($_SESSION['cotizacion']);
    unset($_SESSION['carrito']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
.style511 {font-size: 18}
.style511 {font-size: 18}
#divFiltrar {
    <? $displayDivFiltros = !empty($_POST['buscar']) ? "none": "block";?>
    display: <? echo $displayDivFiltros;?>;
}
-->
</style>
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
    margin-top: -10px;
}
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<!-- colorbox -->
<link rel="stylesheet" href="colorbox.css" />
<link rel="stylesheet" href="divFiltrar.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="colorbox/jquery.colorbox-min.js"></script>

<!--hedaer fixed-->
<script src="colorbox/jquery.fixedheadertable.js"></script>
<!--<script src="colorbox/demo.js"></script>-->
<link href="fix/960.css" rel="stylesheet" media="screen" />
<link href="fix/defaultTheme.css" rel="stylesheet" media="screen" />
<link href="fix/myTheme.css" rel="stylesheet" media="screen" />


<script>

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
$(document).ready(function(){
//fixheader    
    $('#myTable01').fixedHeaderTable({  
        caption : 'My header is fixed !',
        height : parseInt(screen.height * 70/100)
    });

    $('#myTable01').fixedHeaderTable('show', 1000);
//colorbox
    $(".iframe").colorbox({iframe:true,width:"800",transition:"fade", scrolling:true, opacity:0.5});

    //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function(){ 
                $('#click').css({" \"style\"=\"overflow:scroll !important; -webkit-overflow-scoling:touch !important;\"background-color":"#f00",
                    "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
                return false;
        });
});

function cerrarV(){
    $.fn.colorbox.close();
}
function borrar(id_version,id){
    if(confirm("Borrar\u00e1 la cotizaci\u00f3n y sus versiones. Desea continuar?")){
        abrir('generar_cotizacion.php?idVersion=' + id_version + '&idCotizacionEditar=' + id + '&borrar=true');
    }
    
}
function abrir(ir){
$.colorbox({iframe:true,href:""+ir+"",width:"600", height:"353",transition:"fade", scrolling:false, opacity:0.5});
}
function editarCotizacion( id , id_version ,limitInicio){
    var form = document.getElementById('formEditarCotizacion');
    document.getElementById('idCotizacionEditar').value = id;
    document.getElementById('idVersion').value = id_version;
    
    var element = document.createElement('input');
    element.name = 'limitInicio';
    element.type = 'hidden';
    element.value = limitInicio;
    form.appendChild(element);
    
    form.submit();
}
function ordenar(campo,sentido){
    var form = document.getElementById('form1');
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

function setMostrarGanadasPerd(isCheck){
    $('mostrarGanPerd').prop('checked', isCheck);
}
function showFilter(id){
    $("#divFiltrar").slideToggle( "slow" );
 }
</script>
<style type="text/css">
<!--
.style5 {font-size: 12}
.style51 {font-size: 18}
-->
</style>
</head>
<body onload="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<form id="form1" name="form1" method="post" action="">
<table width="890"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <tr>
            <td width="" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3">
                    <div align="left" class="texto_chico_gris">
                    <img src="images/spacer.gif" width="20" height="16" />                    </div>                </td>
              </tr>
              <tr>
                <td width="39%"><table width="146" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="146" height="22" background="images/boton_submenu.jpg"><table width="146" border="0" align="left" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="10"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="10" height="10" /></span></td>
                        <td class="texto_menu_slice"><a href="generar_cotizacion.php" class="texto_menu_slice">NUEVA COTIZACION </a></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
                <td width="39%" align="right">&nbsp;</td>
                <td width="22%" align="right"><button class="texto_info_negro" onclick="showFilter(null)" type="button" >Filtrar</button></td>
              </tr>
              <tr>
                <td colspan="3"><span class="texto_chico_gris" style="float:right"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td colspan="3">
                <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1"  id="myTable01">

             <thead>
              <tr style="background-image: url(images/bkg_1.jpg);">
                      
                    <td width="27"  class="texto_info_blanco">
                        <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/
                            <?
                            if($campo == 'cliente'){ 
                                if($sentido=="ASC") echo "descending"; 
                                else echo "ascending";
                            }
                                ?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('folio','<? if($campo == 'folio'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                      <div align="center">
                        <? if($campo == 'folio'){ ?>
                        <img src="images/<?
                    if($campo == 'folio'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" 
                                        name="filter" width="20" height="20" border="0" id="filter5" />
                        <? }?>
                        Folio</div>
                    </a></td>
                    <td width="92" align="center" >
                        <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'cliente'){ 
                                            if($sentido=="ASC") echo "descending"; 
                                            else echo "ascending";}
                                            ?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('cliente','<? if($campo == 'cliente'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')"><div align="center">
                    <? if($campo == 'cliente'){ ?><img src="images/<?
                    if($campo == 'cliente'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" 
                                        name="filter" width="20" height="20" border="0" id="filter" /><? }?>Cliente</div></a></td>
                    <td width="83" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'costoTotal'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('costoTotal','<? if($campo == 'costoTotal'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                      <div align="center">
                        <? if($campo == 'costoTotal'){ ?>
                        <img src="images/<?
                    if($campo == 'costoTotal'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter2" />
                        <? }?>
                        Costo Total</div>
                    </a></td>
                    <td width="99"  class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('estatus','<? if($campo == 'estatus'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                      <div align="center">
                        <? if($campo == 'estatus'){ ?>
                        <img src="images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter3" />
                        <? }?>
                        Estatus</div>
                    </a></td>
                    <td width="88"  class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('prioridad','<? if($campo == 'prioridad'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                      <div align="center">
                        <? if($campo == 'prioridad'){ ?>
                        <img src="images/<?
                    if($campo == 'prioridad'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter6" />
                        <? }?>
                        Prioridad</div>
                    </a></td>
                    <td width="101"  class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'usuario'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('usuario','<? if($campo == 'usuario'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                      <div align="center">
                        <? if($campo == 'usuario'){ ?>
                        <img src="images/<?
                    if($campo == 'usuario'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter4" />
                        <? }?>
                        Usuario</div>
                    </a></td>
					<td width="101"  class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'contacto'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('contacto','<? if($campo == 'contacto'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                      <div align="center">
                        <? if($campo == 'contacto'){ ?>
                        <img src="images/<?
                    if($campo == 'contacto'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter4" />
                        <? }?>
                        Contacto</div>
                    </a></td>
                    <td width="20"  class="texto_info_blanco">&nbsp;</td>
                  </tr>
                    </thead>
                    <tbody>
                    <?    
    $count=1;
        if(mysql_num_rows($resultado)>0):
    while($res=@mysql_fetch_assoc($resultado)) :
            ?> 
                    
                  <tr bgcolor="<? echo $color;?>">
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'].",".$res['id_version'].",".($count-1);?>);" class="texto_info_negro"><? echo $res['id'];?></a></div></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'].",".$res['id_version'].",".($count-1);?>);" class="texto_info_negro"><? echo $res['nombre_cliente'];?></a></div></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a  href="javascript:editarCotizacion(<? echo $res['id'].",".$res['id_version'].",".($count-1);?>);"class="texto_info_negro">
                    $<? echo getFormatedNumberForMoney($res['total']);?></a></div></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'].",".$res['id_version'].",".($count-1);?>);" class="texto_info_negro"><? echo $res['estatus'];?></a></div></td>
                    <td class="texto_info_negro">
                    <div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'].",".$res['id_version'].",".($count-1);?>);" class="texto_info_negro"><?
                    switch($res['prioridad']){
                        case 1: echo "Petición de una requisición";
                                break;
                        case 2: echo "Presupuesto o requerimiento futuro";
                                break;
                        case 1: echo "Sugerencia mía";
                                break;
                    }
                    ?></a></div>                    </td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                      <?
                    if($res['usuarioAsignado']!="")
                        echo $res['usuarioAsignado'];
                    else { ?> 
                      <input name="asignar" type="button" class="texto_info_negro" onClick="alert('Funcionalidad para la segunda etapa');//asignarUsuarioACotizacion(<? echo $id_cotizacion;?>);" value="Asignar"/><? }?>
                    </div></td>
					<td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'].",".$res['id_version'].",".($count-1);?>);" class="texto_info_negro"><? echo $res['nombre_contacto'];?></a></div></td>
                    <td class="texto_info_negro"><div align="center"><? if($_SESSION['usuario']->id_rol <= $_SESSION['accesos']['supervisor'] ){ ?><a href="#" onclick="return borrar(<? echo $res['id_version'];?>,<? echo $res['id'];?>);" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image<? echo $count;?>','','images/cerrar_r.jpg',1)" > <img src="images/cerrar.jpg" alt="" name="Image<? echo $count;?>" width="17" height="16" border="0" id="Image86" /> </a><? }?>
                    </div></td>
                  </tr>
                    <?
                    $count=$count+1;
                    if($color == '#F2F2F2')
                    $color = "";
                    else $color = "#F2F2F2";
        endwhile;
        else: echo "El cliente no tiene cotizaciones";
        endif;
    ?>
                </table></td>
              </tr>
              <tr>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
              <td colspan="3"></tbody>
            </table></td>
            <td width="14" valign="top"></td>
            <td width="" align="center" valign="top">
                <div id="divFiltrar">
                  <table border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td class="texto_info_negro">Folio</td>
                      <td><input name="folio_buscar" type="text" class="texto_info_negro_forma" id="cliente_buscar3" value="<? echo $_SESSION['buscador']['folio_buscar'];?>" /></td>
                    </tr>
                    <tr>
                      <td class="texto_info_negro">Usuario</td>
                      <td><input name="usuario_buscar" type="text" class="texto_info_negro_forma" id="usuario_buscar" value="<? echo  $_SESSION['buscador']['usuario_buscar'];?>" /></td>
                    </tr>
                    <tr>
                      <td class="texto_info_negro">Contacto</td>
                      <td><input name="contacto_buscar" type="text" class="texto_info_negro_forma" id="contacto_buscar" value="<? echo $_SESSION['buscador']['contacto_buscar'];?>" /></td>
                    </tr>
                    <tr>
                      <td class="texto_info_negro">Cliente</td>
                      <td><input name="cliente_buscar" type="text" class="texto_info_negro_forma" id="cliente_buscar" value="<? echo $_SESSION['buscador']['cliente_buscar'];?>" /></td>
                    </tr>
                    <tr>
                      <td class="texto_info_negro">Estatus</td>
                      <td><span class="style52">
<select name="id_estatus" class="texto_info_negro_forma" id="id_estatus">
                          <option value="">-- TODAS --</option>
                          <?php
        $consulta  = "SELECT * FROM EstatusCotizaciones";
        $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_estatus)>=1){
            while($array=mysql_fetch_assoc($resultado_estatus)) {
                ?>
                          <option <? if($_SESSION['buscador']['id_estatus']==$array['id']) echo 'selected';?> 
                            value="<? echo $array['id'];?>"> <? echo $array['nombre'];?></option>
                          <?
            }
        }
     
          ?>
                        </select>
                      </span></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="texto_info_negro"></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="texto_info_negro"><label>
                        <input name="mostrarMas100" type="checkbox" id="mostrarMas100" value="1"
                             <? echo isset( $_SESSION['mostrarMas100'] ) ?"checked":""?> />
                        Mostrar m&aacute;s de 100 </label></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="texto_info_negro"><label>
                        <input name="mostrarGanPerd" type="checkbox" id="mostrarGanPerd" value="1"
                             <? echo isset( $_SESSION['mostrarGanPerd'] ) ?"checked":""?> />
                        Mostrar ganadas y perdidas</label></td>
                    </tr>
                    <tr>
                      <td class="texto_info_negro"><input type="button" value="Cerrar" onclick="showFilter(null)"  class="texto_info_negro"/></td>
                      <td><input type="submit" name="buscar" id="buscar" value="Buscar" onclick="showFilter(null)"  class="texto_info_negro"/></td>
                    </tr>
                  </table>
            </div></td>
          </tr>
          
  </table>
</form>
        
<form action="generar_cotizacion.php" method="post" name="formEditarCotizacion" id="formEditarCotizacion" style="width:0px">
    <input id="idCotizacionEditar" name="idCotizacionEditar" value="" type="hidden"/>
    <input id="idVersion" name="idVersion" value="" type="hidden"/>
</form>
</body>
</html>