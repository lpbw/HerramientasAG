<?
    /**
     * Test.
     * Developer: Luis perez
     * Company: Bluewolf.
     * Date: 27/07/2018
     */
    include_once "Usuario.php";
    session_start();
    ini_set('session.gc_maxlifetime', 86400);
    //var_dump($_SESSION['usuario']);
    if(!isset($_SESSION['usuario'] ))
    {
        echo "<script>window.location.href='index.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cotizaciones </title>
        <style type="text/css">
            body {
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                background-image: url(images/bkg_1.jpg);
                margin-top: 10px;
            }
        </style>
        <link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
        <link href="images/textos.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="colorbox.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="colorbox/jquery.colorbox-min.js"></script>
        <script type="text/javascript">
            function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

           function resizeIframe(obj) {
	  var div = document.getElementById('divIframe');
	  var height = parseInt(screen.height) * 80/100;
	  div.style.height= (Math.round(parseInt(screen.height) * 80/100 , 0)) + 'px';
	  obj.style.height = (height) + 'px';
  }

             function setFrame(url,idImg){
      document.getElementById('atrasReporteEjecutivo').style.visibility = 'hidden';
      $('#iframe_main').attr('src',url);
//      
//      var src = $('#'+idImg).attr('src');
//      var src = src.split(".");
//      var ext = src[1];
//      var arrayNameImg = src[0].split("_r");
//      var nameBaseImg = arrayNameImg[0];
//      $('#'+idImg).attr('src',nameBaseImg +"_r."+ext);
//      
//      var src =nameBaseImg +"."+ext;
//      $('#'+idImg).parent().attr('onMouseOver',"MM_swapImage('"+idImg+"','','" + src + "',1)");
   }

           function abrir(ir, isSizeMini){
		if(isSizeMini){
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"250",transition:"fade", scrolling:true, opacity:0.5});
		} else {
		$.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
		}
	}
function cerrarV(){
	$.fn.colorbox.close();
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
        </script>
    </head>
    <body onLoad="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_industrias_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg','images/b_cotizaciones_r.jpg','images/b_clientes_r.jpg','images/b_productos_r.jpg','images/b_salir_r.jpg')">
        <table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="14" valign="top">
                    <img src="images/sombra_izq.png" width="14" height="805" />
                </td>
                <td valign="top" bgcolor="#FFFFFF">
                    <table width="977" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="106">
                                            <img src="images/logo_AG.jpg" height="56" />
                                        </td>
                                        <td width="867" style="background-image: url('images/bkg_header_1.jpg');background-size: 7px;background-repeat: repeat-x;">
                                            <div style="float:left; padding:5px">
                                                <!-- Buscar cotizacion -->
                                                <div align="center" class="texto_chico_gris" style="margin:0px 5px 0px 5px; float:left">
                                                    <input name="busca_cotizacion" type="text" id="busca_cotizacion" style="width:100px" size="20" maxlength="15" list="buscarList"/>
                                                    <datalist id="buscarList">
                                                    <?
                                                        $query = "SELECT id FROM Cotizaciones WHERE es_version!=0 group by id";
                                                        $result = mysql_query($query) or die();
                                                        while($row = mysql_fetch_assoc($result) )
                                                        {
                                                    ?>
                                                            <option value="<? echo $row['id']?>"></option>
                                                    <?
                                                        }
                                                    ?>
                                                    </datalist>
                                                    <input name="buscar" type="button" class="style1" id="buscar" value="Buscar" onClick="resizeIframe(document.getElementById('iframe_main'));"/>
                                                </div>
                                            </div>
                                            <div style=" padding:0px 5px; float:right; width:630px">
                                                <div>
                                                    <div align="right" style="float:right; margin:5px 5px 0px 5px">
                                                        <a href="#" onClick="<? echo intval($_SESSION['usuario']->id_rol) <= intval($_SESSION['accesos']['supervisor'])?"setFrame('control_panel.php');":"abrir('cambia_firma_mail.php?id={$_SESSION['usuario']->id}',false);";?>"> 
                                                            <img src="images/control_panel.png" alt="" width="28" height="28" border="0" />
                                                        </a>
                                                    </div>
                                                    <div align="center" style="float: right;margin:5px 5px 0px 5px">
                                                        <a href="#" class="texto_info" onClick="abrir('adm_tareas.php',false);" >
                                                            <img src="images/tarea.png" alt="" name="Image86" width="25" height="25" border="0" id="Image86"/>
                                                            <span id="totalTareas">
                                                                <?php 
                                                                    echo count($_SESSION['usuario']->getTareas());
                                                                ?>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div>
                                                    <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','images/b_cotizaciones_r.jpg',1)" onClick="setFrame('adm_cotizaciones_p.php','Image1');">
                                                        <img src="images/b_cotizaciones.jpg" alt="" name="Image1" height="50" border="0" id="Image1" />
                                                    </a>
                                                    <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image21','','images/b_clientes_r.jpg',1)" onclick="setFrame('adm_clientes.php','Image21');">
                                                        <img src="images/b_clientes.jpg" alt="" name="Image21" height="50" border="0" id="Image21" />
                                                    </a>
                                                    <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image31','','images/b_productos_r.jpg',1)" onclick="setFrame('adm_productos.php','Image31');">
                                                        <img src="images/b_productos.jpg" alt="" name="Image31" height="50" border="0" id="Image31" />
                                                    </a>
                                                    <a href="logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image51','','images/b_salir_r.jpg',1)">
                                                        <img src="images/b_salir.jpg" alt="" name="Image51" height="50"  border="0" id="Image51" />
                                                    </a>
                                                    <div align="right">
                                                        <a href="v_menu.php" target="_blank" class="texto_chico_gris_bold">
                                                            VISITAS
                                                        </a>
                                                    </div>
                                                    <div align="right">
                                                        <a target="_self" href="http://customsoftware.com.mx/hag/Vendedores/home.php" target="_blank" style="font-family: Helvetica; font-size: 13px; font-weight: bold; color: blue;" >
                                                            SEGUIMIENTO A PEDIDOS
                                                        </a>
                                                    </div>				   
                                                    <div align="right" id="atrasReporteEjecutivo" style="visibility: hidden; margin: -10px 0px 0px 0px; float:right">
                                                        <a href="#" onClick="setFrame('reporte_ejecutivo.php');" class="texto_chico_gris">
                                                            REGRESAR
                                                        </a> 
                                                    </div>
                                                </div>
                                            </div>
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                <tr></tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2">
                                <div style="!important; -webkit-overflow-scrolling:touch !important; width:973px;" id="divIframe">
                                    <iframe id="iframe_main" src="adm_cotizaciones.php" width="973" onload='resizeIframe(this);' style="border:none; margin:0px 0px 0px 0px;overflow:hidden; -webkit-overflow-scrolling:touch !important;">
                                    </iframe>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#F2F2F2">
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="14" valign="top">
                    <img src="images/sombra_der.png" width="14" height="805" />
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <img src="images/sombra_abajo.png" width="951" height="18" />
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <img src="images/spacer.gif" width="10" height="10" />
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table width="930" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="159" class="texto_copy">
                                © Copyright 2013
                            </td>
                            <td width="618">
                                <table width="530" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="44" class="texto_copy">
                                            <div align="center">INICIO</div>
                                        </td>
                                        <td width="19" class="texto_copy">
                                            <div align="center"><img src="images/separador_menu_abajo.png" width="19" height="15" /></div>
                                        </td>
                                        <td width="140" class="texto_copy">
                                            <div align="center">NUESTRA EMPRESA</div>
                                        </td>
                                        <td width="19" class="texto_copy">
                                            <div align="center">
                                                <img src="images/separador_menu_abajo.png" width="19" height="15" />
                                            </div>
                                        </td>
                                        <td width="93" class="texto_copy">
                                            <div align="center">PRODUCTOS</div>
                                        </td>
                                        <td width="19" class="texto_copy">
                                            <div align="center"><img src="images/separador_menu_abajo.png" width="19" height="15" /></div></td>
                                        <td width="90" class="texto_copy">
                                            <div align="center">INDUSTRIAS</div>
                                        </td>
                                        <td width="19" class="texto_copy"><div align="center">
                                            <img src="images/separador_menu_abajo.png" width="19" height="15" /></div>
                                        </td>
                                        <td width="95" class="texto_copy">
                                            <div align="center">CONTACTO</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="159">
                                <a href="http://www.lacocinaestudio.com" target="_blank">
                                    <img src="images/logo_la_cocina.png" width="159" height="27" border="0" />
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3"><img src="images/spacer.gif" width="10" height="10" /></td>
            </tr>
        </table>
    </body>
</html>
