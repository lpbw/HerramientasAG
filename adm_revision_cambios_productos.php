<?
//ini_set('display_errors', '1');
include_once "Usuario.php";
include_once "Producto.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['todos']);
     

    
if($_POST['submit']!=""){
    if(count($_POST['aprobar'])>0){
        foreach ($_POST['aprobar'] as $key => $idRevision) {
            
            $obj = stripslashes($idRevision);
            $obj = explode('|||', $obj);
            $idRevision = end($obj);
            unset($obj[1]);
            $producto = unserialize($obj[0]);
            $producto->updateOneAttr($producto->attrName, $producto->attrValue, $_SESSION['usuario']->id, $requireRevision=FALSE);

            if($where != "" )
                $where .= " || id = $idRevision ";
            else $where = " id = $idRevision ";
            
        }
        $query = "UPDATE revisionCambios_Productos SET aprobado = TRUE
            WHERE $where";
        mysql_query($query) or print("error");
    }
    $where = "";
    
    if(count($_POST['no_aprobar'])>0){
        foreach ($_POST['no_aprobar'] as $key => $idRevision) {
            if($where != "" )
                $where .= " || id = $idRevision ";
            else $where = " id = $idRevision ";
        }
        $query = "UPDATE revisionCambios_Productos SET aprobado = FALSE
            WHERE $where";
        mysql_query($query) or print("error");
    }
}

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
<script src="colorbox/demo.js"></script>
<link href="fix/960.css" rel="stylesheet" media="screen" />
<link href="fix/defaultTheme.css" rel="stylesheet" media="screen" />
<link href="fix/myTheme.css" rel="stylesheet" media="screen" />

<script>
var obj=null;
var seleccionarFactor = false;
function agregarCarrito( objCantidad , id_prod , selectFactor ){
    obj = objCantidad;
	seleccionarFactor = selectFactor;
    var cantidad = document.getElementById('cantidad'+obj).value;
    var xmlhttp;
    if (window.XMLHttpRequest)  {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else  {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var resp = xmlhttp.responseText;
			var resp = JSON.parse(resp);
            var cantidad = parseInt(resp[0]);
			var productId = resp[1];
            document.getElementById('cantidad'+obj).value="";
            document.getElementById('agregados'+obj).innerHTML=""+cantidad+" agregados";
			if(seleccionarFactor)
	            abrir('seleccionar_origen_producto.php?id='+productId, false);
        }
    }
    xmlhttp.open("GET","agregar_carrito.php?id="+id_prod+"&cantidad="+cantidad,true);
    xmlhttp.send();
}

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
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
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
</script>
<script>
/*function changeColor(isAprobar,id,obj){
	var td = obj.parentNode;
	var color = "";
	if(isAprobar){
		color = "#00C427";
	} else {
		color = "#FF0000";
	} 
	
	if(obj.checked)
		td.style.background-color = color;
	else
		td.bgcolor = color;
}*/
</script>
<style type="text/css">
<!--
.style54 {font-size: 11px}
-->
</style>
</head>

<body onLoad="MM_preloadImages('images/cerrar_r.jpg')">
<form name="form1" method="post" action="">
<table width="977"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="max-width:890px">
  <tr>
    <td width="568" valign="top"><table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="580"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
      </tr>
      <tr>
        <td>
          <?
          
    $consulta  = "
        SELECT 
            Productos.nombre, 
            Productos.id, CONCAT(Proveedores.prefijo, Productos.codigo) AS codigo_interno, 
            DATE_FORMAT(revisionCambios_Productos.fecha,'%d-%m-%Y') as fecha,
            revisionCambios_Productos.id_cotizacion,
            revisionCambios_Productos.id_version_cotizacion,
            revisionCambios_Productos.id_usuario,
            revisionCambios_Productos.valor_propuesto,
            revisionCambios_Productos.valor_actual,
            revisionCambios_Productos.atributo,
            revisionCambios_Productos.id AS idRevision,
			Usuarios.nombre as nom_usu
        FROM Productos
        INNER JOIN revisionCambios_Productos ON revisionCambios_Productos.id_producto = Productos.id
        LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor
		INNER JOIN Usuarios on Usuarios.id=revisionCambios_Productos.id_usuario 
        WHERE revisionCambios_Productos.aprobado IS NULL
        ORDER BY revisionCambios_Productos.fecha ASC";
//        echo $consulta;
    $resultado = mysql_query( $consulta ) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error());  
if(mysql_num_rows($resultado)>0){
?>
<div class="container_12">
<div class="grid_8 height750">
<table width="632"   cellpadding="2" cellspacing="0"  id="myTable01">
             <thead>
			  <tr>
                  <th width="128" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Producto</th>
                  <th width="71" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Atributo cambiado</div></th>
                    <th width="141" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Valor propuesto</th>
                    <th width="140" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Usuario</th>
                    <th width="45" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Aprobar</th>
                   <!-- <th width="66" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Descartar</th>-->
                  <th width="93" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Modificar</th>
                   <!-- <th width="34" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/cerrar_r.jpg',1)"></a></th>-->
                </tr>
                 </thead>
    			 <tbody>  
        
                    <?	  

	$count=1;
	$color = 'white';
        
	while( $res = mysql_fetch_assoc($resultado) ){
		?>
                  <tr bgcolor="<?echo"$color";?>" >
                    <td bgcolor="<?echo"$color";?>" class="texto_info_negro"><div align="center" class="texto_info_negro">
                      <div align="left"><span class="style54"><? echo $res['codigo_interno']."-".$res['nombre'];?></span></div>
                    </div></td>
                    <td bgcolor="<?echo"$color";?>" class="texto_info_negro"><div align="center"><span class="texto_info_negro"><?
		$nombre_producto = "";
        foreach (split('_',  $res['atributo'] ) as $value) {
            $nombre_producto .= "$value ";
        }
        echo $nombre_producto == "descripcion " ? "$nombre_producto Ingles" : $nombre_producto;
        $producto = new Producto();
        $producto->get($res['id']);
        $producto->attrName = $res['atributo'];;
        $producto->attrValue = $res['valor_propuesto'];
        ?></span></div></td>
                    <td align="center" bgcolor="<?echo"$color";?>" class="texto_info_negro"><span class="texto_info_negro">
                    <? echo $res['valor_propuesto'];?></span></td>
                    <td align="center" valign="middle" bgcolor="<?echo"$color";?>" class="texto_info_negro" id="td_aprobar_<? echo $res['id'];?><? echo $res['atributo'];?>"><? echo $res['nom_usu']."-".$res['fecha'];?></td>
                    <td align="center" valign="middle" bgcolor="<?echo"$color";?>" class="texto_info_negro" id="td_aprobar_<? echo $res['id'];?><? echo $res['atributo'];?>">
                        <input name="aprobar[]" type="checkbox"  value="<? echo htmlspecialchars(serialize($producto)); echo "|||{$res['idRevision']}";?>"  id="aprobar_<? echo $res['id'];?><? echo $res['atributo'];?>" onClick="changeColor(true,'td_aprobar_<? echo $res['id'];?><? echo $res['atributo'];?>',this);"></td>
                    <!--<td align="center" valign="middle" class="texto_info_negro">
                      <input name="no_aprobar[]" type="checkbox"value="<? echo $res['idRevision'];?>" id="no_aprobar_<? echo $res['id'];?><? echo $res['atributo'];?>" ></td>-->
                    <td align="center" valign="middle" bgcolor="<?echo"$color";?>" class="texto_info_negro">
                    <a href="cambia_producto.php?id=<? echo $res['id'];?>&atributo=<? echo $res['atributo'];?>" class="texto_info_negro iframe"> <span class="texto_chico_rojo">Modificar</span> <img src="images/edit.png" alt="" name="warning" width="24" height="24" style="border:none"/></a></td>
                  </tr>
                  
            <?
            $count=$count+1;
            if($color == 'white'){
                $color = '#E3E3E3';
            } else $color = 'white';
	}
	?>
              </tbody>
			  </table>
			  </div></div>
			 
<? } ?>
          </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    <td width="14" valign="top"><img src="images/sombra_productos_gris.jpg" alt="" width="14" height="553" /></td>
    <td width="290px" align="center" valign="top" bgcolor="#e5e5e6"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
      </tr>
      <tr>
        <td align="center">
          <label>
            <input type="submit" name="submit" id="submit" value="Aplicar Cambios">
          </label></td>
      </tr>
    </table></td>
  </tr>
</table>
        </form>
</body>
</html>