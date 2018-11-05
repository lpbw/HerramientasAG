<?
//ini_set('display_errors', 'On');
include_once "Usuario.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['supervisor']);


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

   $where = " (Usuarios.id = ".$_SESSION['usuario']->id." $vendedoresQuery ) AND ";
}

if($_POST['buscar']!=''){
    $_SESSION['buscador_reporteEjecutivo']['desde_buscar'] = $_POST['desde_buscar'];
    $desde = explode('-',$_POST['desde_buscar']);
    $desde = "$desde[2]-$desde[1]-$desde[0]";
    
    $_SESSION['buscador_reporteEjecutivo']['hasta_buscar'] = $_POST['hasta_buscar'];
    $hasta = explode('-',$_POST['hasta_buscar']);
    $hasta = "$hasta[2]-$hasta[1]-$hasta[0]";
	
    if( $_POST['id_usuario']!=""){
        $_SESSION['buscador_reporteEjecutivo']['id_usuario'] = $_POST['id_usuario']==""?0:$_POST['id_usuario'];
        $id_usuario = $_POST['id_usuario'];
        $where.= " Cotizaciones.id_usuario = ". $_POST['id_usuario']." AND ";
    }
    if( $_POST['id_cartera']!=""){
        $_SESSION['buscador_reporteEjecutivo']['id_cartera'] = $_POST['id_cartera']==""?0:$_POST['id_cartera'];
        $id_cartera = $_POST['id_cartera'];
        $where.= " Clientes.id_cartera = ". $_POST['id_cartera']." AND ";
    }
	
    
    
    if( $desde!="" && $hasta!=""){
	
        $where.= " Cotizaciones.".$_POST['cualfecha']." >= '$desde 00:00:01' AND
            Cotizaciones.".$_POST['cualfecha']." <= '$hasta 23:59:59' AND ";
    }
    
    $_SESSION['WHERE_reporteEjecutivo'] = $where;
    
}
$where = $_SESSION['WHERE_reporteEjecutivo']; 

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
        case 'folio':
            $orderBy = " ORDER BY Cotizaciones.id $sentido ";
            break;
        case 'prioridad':
            $orderBy = " ORDER BY Cotizaciones.prioridad $sentido ";
            break;
        case 'fecha_ultima_modificacion':
            $orderBy = " ORDER BY fecha_ultima_modificacion $sentido ";
            break;
        default:
            $orderBy = "";
            break;
    }
    if($campo != 'folio'){
        $orderBy .= ", Cotizaciones.id DESC ";
    }
    $_SESSION['orderBy_buscador'] = $orderBy;
}
if(is_null($orderBy)){
    $orderBy .= " ORDER BY Cotizaciones.id DESC ";
    $_SESSION['orderBy_buscador'] = $orderBy;
}
$orderBy = $_SESSION['orderBy_buscador'];
$campo = $_SESSION['filter']['campo'];
$sentido = $_SESSION['filter']['sentido'];


$consulta  = "SELECT Cotizaciones.id, CONCAT(alias,' (' ,codigo,')') AS nombre_cliente, 
                IF( Cotizaciones.tipo_moneda = 1, Cotizaciones.total * ".$_SESSION['dollar'].", Cotizaciones.total) AS total,
                Usuarios.nombre AS usuarioAsignado, 
                EstatusCotizaciones.nombre AS estatus, EstatusCotizaciones.id AS id_estatus, 
                Cotizaciones.id_version, Cotizaciones.prioridad, Cotizaciones.tipo_moneda,
                date(Cotizaciones.fecha_ultima_modificacion) AS fecha_ultima_modificacion,
				date(Cotizaciones.fecha_creacion) AS fecha_creacion
            FROM Cotizaciones

            LEFT OUTER JOIN Clientes ON Cotizaciones.id_cliente = Clientes.id
            LEFT OUTER JOIN Usuarios ON Cotizaciones.id_usuario = Usuarios.id
            LEFT OUTER JOIN EstatusCotizaciones ON EstatusCotizaciones.id = Cotizaciones.id_estatus                   
            WHERE Cotizaciones.es_version = FALSE 
              AND $where 1
            GROUP BY Cotizaciones.id
            $orderBy";
			 
$resultado = mysql_query($consulta) or print("<h1>Error en buscador</h1>");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
.style511 {font-size: 18}
.style511 {font-size: 18}
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
/*	background-image: url(images/bkg_1.jpg);*/
	margin-top: -10px;
}
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<!-- colorbox -->
<link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="colorbox/jquery.colorbox-min.js"></script>

<!--hedaer fixed-->
<script src="colorbox/jquery.fixedheadertable.js"></script>
<script src="colorbox/demo.js"></script>
<link href="fix/960.css" rel="stylesheet" media="screen" />
<link href="fix/defaultTheme.css" rel="stylesheet" media="screen" />
<link href="fix/myTheme.css" rel="stylesheet" media="screen" />

<!--datePicker-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 
<script>
/*  $(function() {
    $( "#desde_buscar" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      dateFormat: "dd-mm-yy",
      onClose: function( selectedDate ) {
        $( "#hasta_buscar" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#hasta_buscar" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      dateFormat: "dd-mm-yy",
      onClose: function( selectedDate ) {
        $( "#desde_buscar" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
//  });*/
$(function() {
                $( "#desde_buscar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                $( "#hasta_buscar" ).datepicker({ dateFormat: 'dd-mm-yy' });
            });  
  function validar(){
      var returnn = true;
      if($('#hasta_buscar').val()==""){
          alert('Falta una fecha');
          $('#hasta_buscar').focus();
          returnn = false;
      } else{ if($('#desde_buscar').val()==""){
          alert('Falta una fecha');
          $('#desde_buscar').focus();
          returnn = false;
      } else{ if(document.form1.cualfecha[0].checked==false && document.form1.cualfecha[1].checked==false){
          alert('Falta seleccionar tipo fecha');
          document.form1.cualfecha[0].focus();
          returnn = false;
      } }}
      return returnn;
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
  </script>
  

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
        //Examples of how to assign the ColorBox event to elements

        $(".iframe").colorbox({iframe:true,width:"800",transition:"fade", scrolling:true, opacity:0.5});

        //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function(){ 
                $('#click').css({" \"style\"=\"overflow:scroll !important; -webkit-overflow-scoling:touch !important;\"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
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
function editarCotizacion( id , id_version ){
    parent.document.getElementById('atrasReporteEjecutivo').style.visibility = 'inherit';
    var form = document.getElementById('formEditarCotizacion');
	document.getElementById('idCotizacionEditar').value = id;
	document.getElementById('idVersion').value = id_version;
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
            <td width="568" valign="top"><table width="568" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div align="left" class="texto_chico_gris"><img src="images/spacer.gif" width="20" height="16" /></div></td>
              </tr>
              <tr>
                <td ><div align="left"></div>
                  <label></label>
                  <div align="left" class="texto_info_blanco">
                    <div align="center"></div>
                  </div></td>
              </tr>
              <tr>
                <td align="center">
                <table width="90%" border="0" cellpadding="2" cellspacing="2" class="fht-thead">
  <tr class="texto_info_blanco_forma" style="background-image: url(images/bkg_1.jpg);">
    <th align="left" scope="col">Estatus</th>
    <th scope="col">#</th>
    <th scope="col">Monto</th>
  </tr>     
        
        <?php

        if($_POST['buscar']!="" || $_POST['campo']!=""):

  	    $consulta  = "SELECT EstatusCotizaciones.*, COUNT(id_usuario) AS numCotizaciones, 
        SUM(IF( Cotizaciones.tipo_moneda = 1, Cotizaciones.total * ".$_SESSION['dollar'].", Cotizaciones.total)) AS monto
                  FROM EstatusCotizaciones
                  LEFT OUTER JOIN Cotizaciones ON Cotizaciones.id_estatus = EstatusCotizaciones.id
                  LEFT OUTER JOIN Clientes ON Cotizaciones.id_cliente = Clientes.id
                  LEFT OUTER JOIN Usuarios ON Cotizaciones.id_usuario = Usuarios.id
                  WHERE Cotizaciones.es_version = FALSE AND $where 1
                  GROUP BY EstatusCotizaciones.id";

        $resultado_estatus = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        $sum = 0;
        $count = 0;
            while($array=mysql_fetch_assoc($resultado_estatus)):
                $sum+=doubleval($array['monto']);
                $count+=$array['numCotizaciones'];
        ?>
        <tr>
    <th align="left" scope="row"><? echo $array['nombre']; ?></th>
    <td align="center"><? echo $array['numCotizaciones']; ?></td>
    <td align="right">$<? echo getFormatedNumberForMoney($array['monto']);?></td>
  </tr>
        <? endwhile;
        endif;?>
  <tr> 
    <th align="right" scope="row">TOTAL</th>
    <td align="center"><? echo $count;?></td>
    <td align="right">$<? echo getFormatedNumberForMoney($sum);?></td>
  </tr>
</table>

                </td>
              </tr>
              <tr>
                <td><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="16" /></span></td>
              </tr>
              <tr>
                <td><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              <tr>
                <td><table width="560" border="0" align="center" cellpadding="3" cellspacing="1"  id="myTable01">

             <thead>
			  <tr>
			    <td bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/
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
			    <td align="center" bgcolor="#DD1A22" ><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'cliente'){ 
                                            if($sentido=="ASC") echo "descending"; 
                                            else echo "ascending";}
                                            ?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('cliente','<? if($campo == 'cliente'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
			      <div align="center">
			        <? if($campo == 'cliente'){ ?>
			        <img src="images/<?
                    if($campo == 'cliente'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" 
                                        name="filter" width="20" height="20" border="0" id="filter" />
			        <? }?>
			        Cliente</div>
			      </a></td>
			    <td bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'costoTotal'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('costoTotal','<? if($campo == 'costoTotal'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
			      <div align="center">
			        <? if($campo == 'costoTotal'){ ?>
			        <img src="images/<?
                    if($campo == 'costoTotal'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter2" />
			        <? }?>
			        Total</div>
			      </a></td>
			    <td bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('estatus','<? if($campo == 'estatus'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
			      <div align="center">
			        <? if($campo == 'estatus'){ ?>
			        <img src="images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter3" />
			        <? }?>
			        Estatus</div>
			      </a></td>
			    <td bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'estatus'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('prioridad','<? if($campo == 'prioridad'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
			      <div align="center">
			        <? if($campo == 'prioridad'){ ?>
			        <img src="images/<?
                    if($campo == 'prioridad'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter6" />
			        <? }?>
			        prioridad</div>
			      </a></td>
			    <td bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'usuario'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('usuario','<? if($campo == 'usuario'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
			      <div align="center">
			        <? if($campo == 'usuario'){ ?>
			        <img src="images/<?
                    if($campo == 'usuario'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter4" />
			        <? }?>
			        Usuario</div>
			      </a></td>
                      
                    <td width="20" bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('filter','','images/<?
                    if($campo == 'fecha_ultima_modificacion'){ if($sentido=="ASC") echo "descending"; else echo "ascending";}?>.png',1)" class="texto_info_blanco"
                    onclick="ordenar('fecha_ultima_modificacion','<? if($campo == 'fecha_ultima_modificacion'){ if($sentido=="ASC") echo "DESC"; else echo "ASC";}?>')">
                    <div align="center">
                      <? if($campo == 'fecha_ultima_modificacion'){ ?>
                      <img src="images/<?
                    if($campo == 'fecha_ultima_modificacion'){ if($sentido=="ASC") echo "ascending"; else echo "descending";}?>.png" alt="" name="filter" width="20" height="20" border="0" id="filter7" />
                      <? }?>
                      Última Modificación</div>
                    </a></td>
                    <td width="20" bgcolor="#DD1A22" class="texto_info_blanco">Creación</td>
			  </tr>
                    </thead>
                    <tbody>
                    <?	  
	$count=1;
        if(isset($_SESSION['WHERE_reporteEjecutivo'])):
	while($res=@mysql_fetch_assoc($resultado)) :
            ?> 
                    
                  <tr bgcolor="<? echo $color;?>">
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'];?>,<? echo $res['id_version'];?>);" class="texto_info_negro"><? echo $res['id'];?></a></div></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'];?>,<? echo $res['id_version'];?>);" class="texto_info_negro"><? echo $res['nombre_cliente'];?></a></div></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a  href="javascript:editarCotizacion(<? echo $res['id'];?>,<? echo $res['id_version'];?>);"class="texto_info_negro">
                    $<? echo getFormatedNumberForMoney($res['total']);?></a></div></td>
                    <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'];?>,<? echo $res['id_version'];?>);" class="texto_info_negro"><? echo $res['estatus'];?></a></div></td>
                    <td class="texto_info_negro">
                    <div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id'];?>,<? echo $res['id_version'];?>);" class="texto_info_negro"><?
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
                      <? if($res['usuarioAsignado']!="")
                          echo $res['usuarioAsignado'];
                        else { ?> 
                      <input name="asignar" type="button" class="texto_info_negro" onClick="alert('Funcionalidad para la segunda etapa');//asignarUsuarioACotizacion(<? echo $id_cotizacion;?>);" value="Asignar"/><? }?>
                    </div></td>
                    <td class="texto_info_negro"><div align="center" style="width: 80px">
                    <? echo $res['fecha_ultima_modificacion']; ?>
                    </div></td>
                    <td class="texto_info_negro"><div align="center"><span style="width: 80px"><? echo $res['fecha_creacion']; ?></span></div></td>
                  </tr>
                    <?
                    $count=$count+1;
					if($color == '#F2F2F2')
					$color = "";
					else $color = "#F2F2F2";
        endwhile;
        endif;
	?>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr></tbody>
            </table></td>
            <td width="14" valign="top"><img src="images/sombra_productos_gris.jpg" width="14" height="553" /></td>
            <td width="290px" align="center" valign="top" bgcolor="#e5e5e6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><img src="images/spacer.gif" width="20" height="16" /></td>
              </tr>
              <tr>
                <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="left">Buscar:</div>
                  <label></label>
                  <div align="left" class="texto_info_blanco">
                    <div align="center"></div>
                  </div></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" align="center" cellpadding="0" cellspacing="2">
                  <tr>
                    <td class="texto_info_negro">Usuario</td>
                    <td><span class="style52">
                      <select name="id_usuario" class="texto_info_negro_forma">
                        <option value="">- TODOS -</option>
                        <?php
	    $consulta  = "SELECT id,nombre FROM Usuarios WHERE 1";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
                        <option <? if($_SESSION['buscador_reporteEjecutivo']['id_usuario']==$array['id']) echo 'selected';?> 
                            value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                        <?
            }
        }
     
		  ?>
                      </select>
                    </span></td>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">Cartera</td>
                    <td><span class="style52">
                      <select name="id_cartera" class="texto_info_negro_forma" id="id_cartera">
                        <option value="">- TODAS -</option>
                        <?php
	    $consulta  = "SELECT id,nombre FROM CarteraClientes WHERE 1";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
                        <option <? if($_SESSION['buscador_reporteEjecutivo']['id_cartera']==$array['id']) echo 'selected';?> 
                            value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                        <?
            }
        }
     
		  ?>
                      </select>
                    </span></td>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">Desde</td>
                    <td><input name="desde_buscar" type="text" class="texto_info_negro_forma" id="desde_buscar" value="<? echo $_SESSION['buscador_reporteEjecutivo']['desde_buscar'];?>" /></td>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">Hasta</td>
                    <td><input name="hasta_buscar" type="text" class="texto_info_negro_forma" id="hasta_buscar" value="<? echo $_SESSION['buscador_reporteEjecutivo']['hasta_buscar'];?>" /></td>
                  </tr>
                  <tr>
                    <td class="texto_info_negro">&nbsp;</td>
                    <td><input type="radio" name="cualfecha" id="cualfecha" value="fecha_creacion" <? if($_POST['cualfecha']=="fecha_creacion") echo 'checked';?>> Fecha Creacion<br/><input type="radio" name="cualfecha" id="cualfecha" value="fecha_ultima_modificacion"  <? if($_POST['cualfecha']=="fecha_ultima_modificacion") echo 'checked';?>> Fecha Ultima Modificacion</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><div align="center">
                        <input type="submit" name="buscar" id="buscar" value="Buscar"  class="texto_info_negro" onclick="return validar();"/>
                </div></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          
  </table>
</form>
        
<form action="generar_cotizacion.php" method="post" name="formEditarCotizacion" id="formEditarCotizacion" style="width:0px">
	<input id="idCotizacionEditar" name="idCotizacionEditar" value="" type="hidden"/>
	<input id="idVersion" name="idVersion" value="" type="hidden"/>
</form>
</body>
</html>