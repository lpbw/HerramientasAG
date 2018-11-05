<?
//include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #FFFFFF;
	background-image: url(images/bkg_1.jpg);
}
-->
</style>
<script type="text/javascript">
<!--
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
//-->
</script>
<link href="images/textos.css" rel="stylesheet" type="text/css" /><link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
            <script src="colorbox/jquery.colorbox-min.js"></script>
<script>
    $(document).ready(function(){
        //Examples of how to assign the ColorBox event to elements
        $(".iframe").colorbox({iframe:true,width:"600", height:"553",transition:"fade", scrolling:false, opacity:0.5});
        //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function(){ 
                $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
                return false;
        });
    });

    function cerrarV(){
        $.fn.colorbox.close();
    }

    function borrar(id){
        if(confirm("Â¿Esta seguro de borrar este evento?")){
                   $.colorbox({iframe:true,href:"adm_borrar_evento.php?id="+id ,width:"600", height:"453",transition:"fade", scrolling:false, opacity:0.5});
        }
    }
    function abrir(ir){
        $.colorbox({iframe:true,href:""+ir+"",width:"600", height:"353",transition:"fade", scrolling:false, opacity:0.5});
    }
</script>
<style type="text/css">
<!--
.style5 {font-size: 18}
-->
</style>
</head>
<body >
<table width="1024" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="1024" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="276" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="35" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="78" bgcolor="#FFFFFF"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image4','','images/b_inicio_r.jpg',1)"></a></td>
        <td width="167" bgcolor="#FFFFFF"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image5','','images/b_compania_r.jpg',1)"></a></td>
        <td width="161" bgcolor="#FFFFFF"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image6','','images/b_enfoque_r.jpg',1)"></a></td>
        <td width="191" bgcolor="#FFFFFF"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image7','','images/b_programa_r.jpg',1)"></a></td>
        <td bgcolor="#FFFFFF"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image8','','images/b_contacto_r.jpg',1)"></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="1024" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="307" bgcolor="#FFFFFF"><form id="form1" name="form1" method="post" action="myadmin.php">
          <p align="center" class="texto_tit_nuestra">Men&uacute; Administraci&oacute;n</p>
          <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="363" class="texto_tit_fecha">Configuracion</td>
              <td width="337" class="texto_tit_fecha">Consulta de precios por vendedores</td>
            </tr>
            <tr>
              <td class="texto_tit_nuestra"><ul>
                <li><a href="adm_usuarios.php" class="texto_tit_nuestra">Usuarios</a></li>
                <li><a href="adm_tipo_usuario.php" class="texto_tit_nuestra iframe">Tipos de Usuario</a></li>
                <li><a href="adm_productos.php" class="texto_tit_nuestra">Productos</a></li>
                <li><a href="adm_familias_cotizador.php" class="texto_tit_nuestra iframe">Familias Cotizador</a></li>
                <li><a href="adm_proveedores.php" class="texto_tit_nuestra">Proveedores</a></li>
                <li><a href="adm_clientes.php" class="texto_tit_nuestra">Catálogo de Clientes</a></li>
              </ul></td>
              <td class="texto_tit_nuestra"><ul>
                <li></li>
              </ul></td>
            </tr>
            <tr>
              <td class="texto_tit_fecha">Cotizaciones</td>
              <td class="texto_tit_fecha">&nbsp;</td>
            </tr>
            <tr>
              <td class="texto_tit_nuestra"><ul>
                <li><a href="buscar_productos.php" class="texto_tit_nuestra">Cotizar como cliente</a></li>
                <li><a href="adm_cotizaciones.php" class="texto_tit_nuestra">Cotizaciones </a></li>
              </ul></td>
              <td class="texto_tit_nuestra">&nbsp;</td>
            </tr>
            <tr>
              <td class="texto_tit_nuestra">&nbsp;</td>
              <td class="texto_tit_nuestra">&nbsp;</td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </form></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td height="63" valign="top"><table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#3E1C58"><div align="center" class="texto_direccion">Add HR | Cormor&Atilde;&iexcl;n 3, Lomas de las &iuml;&iquest;&frac12;?guilas  | M&Atilde;&copy;xico, D.F. 01730  | T. 2591.8330  | contacto@addhr.com.mx | &Acirc;&copy; Copyright 2013 Add HR</div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
