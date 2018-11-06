<?
    include_once "Usuario.php";
    include_once 'Producto.php';
    include_once "getFormatedNumberForMoney.php";
    include_once "checar_sesion_admin.php";
    include_once "coneccion.php";
    session_start();
    $idcontacto = $_GET['idcontacto'];
    //var_dump($idcontacto);
    if($_REQUEST['buscar']!='' || $_REQUEST['codigo_buscar']!='' || $_REQUEST['familia']!='' || $_REQUEST['proveedor']!='' || $_SESSION['buscadorCotizaciones']['codigo_buscar']!='' || $_SESSION['buscadorCotizaciones']['familia']!='' || $_SESSION['buscadorCotizaciones']['proveedor']!='' || $_REQUEST['submit'])
    {
        $_SESSION['buscadorCotizaciones']['nombre'] = isset($_POST['nombre']) ? $_POST['nombre'] : $_SESSION['buscadorCotizaciones']['nombre'];
        $_SESSION['buscadorCotizaciones']['familia'] = isset($_POST['familia']) ? $_POST['familia'] : $_SESSION['buscadorCotizaciones']['familia'];
        $_SESSION['buscadorCotizaciones']['proveedor'] = isset($_POST['proveedor']) ? $_POST['proveedor'] : $_SESSION['buscadorCotizaciones']['proveedor'];
        $_SESSION['buscadorCotizaciones']['codigo_buscar'] = isset($_POST['codigo_buscar']) ? $_POST['codigo_buscar'] : $_SESSION['buscadorCotizaciones']['codigo_buscar'];
	
        if($_SESSION['buscadorCotizaciones']['nombre']!="")
        {
            $where.= " Productos.nombre LIKE '%".$_SESSION['buscadorCotizaciones']['nombre']."%' AND ";
        }
        if($_SESSION['buscadorCotizaciones']['familia']!= "")
        {
            $where.= "  FamiliaCotizador.id = '".$_SESSION['buscadorCotizaciones']['familia']."' AND ";
        }
        if($_SESSION['buscadorCotizaciones']['proveedor']!="")
        {
            $where .= "  Proveedores.id = ".$_SESSION['buscadorCotizaciones']['proveedor']." AND ";
        }
        if($_SESSION['buscadorCotizaciones']['codigo_buscar']!= "")
        {
            $where.= "  Productos.codigo_interno LIKE '%".$_SESSION['buscadorCotizaciones']['codigo_buscar']."%' AND ";
        }

        if($_SESSION['buscadorCotizaciones']['nombre']!="" || $_SESSION['buscadorCotizaciones']['familia']!= "" || $_SESSION['buscadorCotizaciones']['proveedor']!="" || $_SESSION['buscadorCotizaciones']['codigo_buscar']!="")
        {
            $consulta  = "SELECT Productos.nombre, Productos.descripcion AS nombre_ingles, Productos.id, CatalogoProductos.nombre AS catalogo, precio, 
            archivo_ficha_tecnica, Productos.precio, Proveedores.nombre AS proveedor, FamiliaCotizador.nombre AS familia,
			Productos.id_proveedor, 
                CONCAT(Proveedores.prefijo, Productos.codigo) AS codigo_interno, Productos.tipo_moneda_usa, Productos.stock
            FROM Productos
            LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor
            LEFT OUTER JOIN CatalogoProductos ON CatalogoProductos.id = Productos.id_catalogo_productos
            LEFT OUTER JOIN FamiliaCotizador ON FamiliaCotizador.codigo = Productos.codigo_familia
            WHERE  $where Productos.id_proveedor<>8 and Productos.id_proveedor<>7
            ORDER BY Productos.nombre LIMIT 0,100";
        
        if($debug)
            echo $consulta;
        
        $resultado = mysql_query($consulta) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error());    
    }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cotizaciones </title>
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
    .agregados {
      font-family: sans-serif;
      font-size: x-small;
      background-color: #7FFF00;
    }
    .numberTiny {	
      width: 60px;
      text-align: center;
    }
    .numberMedium{	
        text-align: center;
    }
    .style6 {color: #FFFFFF}
  </style>
      <script type="text/javascript">
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

        var objNum=0;
        var seleccionarFactor = false;
        /**
            Recibe (contador,id producto,true o falase)
        */
        function agregarCarrito(objCantidadNum , id_prod , selectFactor)
        {
            objNum = objCantidadNum;//58
            seleccionarFactor = selectFactor;//false    
            var objCantidad = document.getElementById('cantidad' + objNum);
            var cantidad = objCantidad.value;//1
        
            if(seleccionarFactor)
            {
                abrir('seleccionar_origen_producto.php?id='+id_prod+"&objNum="+objNum, false);
            }
            else 
            {
                if(cantidad > 0 && cantidad != "" && !isNaN( cantidad ))
                {
                    var xmlhttp;
                    if (window.XMLHttpRequest)
                    {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                    } 
                    else 
                    {
                        // code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }

                    xmlhttp.onreadystatechange=function()
                    {
                        if (xmlhttp.readyState==4 && xmlhttp.status==200)
                        {
                            var resp = xmlhttp.responseText;
                            try
                            {
                                resp = JSON.parse(resp);
                                var cantidad = parseInt(resp[0]);
                                var productId = resp[1];
                                document.getElementById('cantidad' + objNum).focus();
                                document.getElementById('cantidad' + objNum).value="";
                                document.getElementById('agregados' + objNum).innerHTML="" + cantidad + " agregados";
                                alert(cantidad + ' productos agregados');
                            }
                            catch(e)
                            {
                                alert(resp);
                                console.log(e.message);
                            }
                        }
                    }
                    xmlhttp.open("GET","agregar_carrito.php?id="+id_prod+"&cantidad="+cantidad,true);
                    xmlhttp.send();
                } 
                else 
                {
                    alert('Escribe cantidad en numeros');
                    objCantidad.value = '';
                    objCantidad.focus();
                }       
            }
        
        }

        $(document).ready(function(){
                    //Examples of how to assign the ColorBox event to elements

                    $(".iframe").colorbox({iframe:true,width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
            
                    $(".iframeMini").colorbox({iframe:true,width:"400", height:"250",transition:"fade", scrolling:true, opacity:0.5});

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
          $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"250",transition:"fade", scrolling:true, opacity:0.5});
          } else {
          $.colorbox({iframe:true,href:""+ir+"",width:"800", height:"553",transition:"fade", scrolling:true, opacity:0.5});
          }
        }
        function cerrarV(){
          $.fn.colorbox.close();
        }
      </script>
  </head>
  <body onload="MM_preloadImages('images/b_inicio_r.jpg','images/b_empresa_r.jpg','images/b_productos_r.jpg','images/b_industrias_r.jpg','images/b_contacto_r.jpg','images/cerrar_r.jpg','images/carrito_r.jpg')">
<table width="890px"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="max-width:890px">
          <tr>
            <td width="568" valign="top"><table width="568" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div align="left" class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="16" /></div></td>
              </tr>
              <tr>
                <td><div align="left"><img src="images/tit_cotizaciones.jpg" alt="" width="221" height="28" /></div></td>
              </tr>
              <tr>
                <td><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
              </tr>
              
              <tr>
                <td>
                 <form id="formBuscar" name="formBuscar" method="post" action="">
                <table width="580" border="0" align="center" cellpadding="2" cellspacing="2">
                  <tr>
                    <td bgcolor="#DD1A22" class="texto_info_blanco"><div align="left">Buscar por:</div>
                      <label></label>
                      <div align="left" class="texto_info_blanco">
                        <div align="center"></div>
                      </div></td>
                  </tr>
                  <tr>
                    <td valign="top" class="texto_info_negro"><table width="550" border="0" align="center" cellpadding="0" cellspacing="2">
                      <tr>
                        <td class="texto_info_negro">Codigo</td>
                        <td><span class="style9">
                          <input name="codigo_buscar" type="text" class="texto_info_negro_forma" id="codigo_buscar" value="<? echo $_SESSION['buscadorCotizaciones']['codigo_buscar'];?>" />
                        </span></td>
                        <td class="texto_info_negro">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="129" class="texto_info_negro">Nombre del producto</td>
                        <td width="162"><span class="style9">
                          <input name="nombre" type="text" class="texto_info_negro_forma" value="<? echo $_SESSION['buscadorCotizaciones']['nombre'];?>" />
                        </span></td>
                        <td width="111" class="texto_info_negro">&nbsp;</td>
                        <td width="138">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="texto_info_negro">Codigo de familia</td>
                        <td><span class="style51">
                          <select name="familia" class="texto_info_negro_forma" id="familia" style="width:200px">
                            <option value="">- -</option>
                            <?php
	    $consulta  = "SELECT * FROM FamiliaCotizador";
        $resultado_familia= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_familia)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_familia)) {
                ?>
                            <option <? if($_SESSION['buscadorCotizaciones']['familia']==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre']." (".$array['codigo'].")";?></option>
                            <?
            }
        }
     
		  ?>
                          </select>
                        </span></td>
                        <td colspan="2"><div align="center"></div></td>
                      </tr>
                      <tr>
                        <td class="texto_info_negro">Proveedor</td>
                        <td><span class="style51">
                          <select name="proveedor" class="texto_info_negro_forma" id="proveedor" style="width:200px">
                            <option value="">- -</option>
                            <?php
	    $consulta  = "SELECT * FROM Proveedores where Proveedores.id<>7 ORDER BY nombre";
        $resultado_proveedor = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado_proveedor)>=1){
            while($array=mysql_fetch_assoc($resultado_proveedor)) {
                ?>
                            <option <? if($_SESSION['buscadorCotizaciones']['proveedor']==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
                            <?
            }
        }
     
		  ?>
                          </select>
                        </span></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="texto_info_negro">&nbsp;</td>
                        <td align="center"><span class="style9">
                          <input name="buscar" type="submit" class="texto_info_negro" id="buscar" value="Buscar" />
                        </span></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4" align="right" class="texto_info_negro"><span class="style6">
                          <input name="comprar" type="button" class="texto_info" id="comprar" onclick="window.location = 'generar_cotizacion_p.php?g=1&idcontacto=<?echo  $idcontacto?>'" value="Aceptar" />
                        </span></td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                </form></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>
                
          <form method="POST" name="formAgregarProducto" id="formAgregarProducto">
            <table width="778" border="0" align="center" cellpadding="0" cellspacing="2">
                <thead>
                    <tr>
                        <td width="101" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Codigo Interno</div></td>
                        <td width="261" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Nombre</div></td>
                        <td width="60" align="center" bgcolor="#DD1A22" class="texto_info_blanco"><p>Stock</p></td>
                        <td width="60" align="center" bgcolor="#DD1A22" class="texto_info_blanco">Precio AG </td>
                        <td width="94" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Proveedor</div></td>
                        <td width="82" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Ficha técnica</div></td>
                        <td width="68" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Cantidad</div></td>
                        <td width="34" bgcolor="#DD1A22" class="texto_info_blanco"><a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image22','','images/cerrar_r.jpg',1)"></a></td>
                    </tr>
                </thead>
                <tbody>   
            <?	  
	            $tm="";
	            $count=1;
                $color = 'white';
	            while(@mysql_num_rows($resultado)>=$count)
	            {
		            $res=mysql_fetch_assoc($resultado);
                    if($res['tipo_moneda_usa']=="1")
                    {
                        $tm="USD";
                    }	
		            //else
		    ?>
                    <tr bgcolor="<?echo"$color";?>">
                        <td class="texto_info_negro"><div align="center"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['codigo_interno'];?></a></div></td>
                        <td class="texto_info_negro"><div align="center"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['nombre']=="" ? $res['nombre_ingles'] : $res['nombre'];?></a></div></td>
                        <td align="center" valign="middle" class="texto_info_negro"><? echo $res['stock']?></td>
                        <td class="texto_info_negro">$<a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe" id="precioProd<? echo $count?>"><? echo	getFormatedNumberForMoney($res['precio']);?> <? echo"$tm";?></a></td>
                        <td class="texto_info_negro"><div align="center"><a href="cambia_producto.php?id=<? echo $res['id'];?>" class="texto_info_negro iframe"><? echo $res['proveedor'];?></a></div></td>
                        <td class="texto_info_negro">
                            <div align="center">
            <? 
                            if($res['archivo_ficha_tecnica']!="")
                            {
            ?>
                                <a href="<? echo $res['archivo_ficha_tecnica'];?>" target="_blank" class="texto_info_negro">
                                    <img src="images/pdf.ico" alt="pdf" width="20" />
                                </a>
            <? 
                            }
            ?>
                            </div>
                        </td>

                        <td class="texto_info_negro">
                            <div align="center">
                                <input name="cantidad<? echo $count?>" type="text" id="cantidad<? echo $count?>" size="3"/>
                                <div class="agregados" id="agregados<? echo $count?>"></div>
                            </div>
                        </td>

                        <td align="center" valign="middle" class="texto_info_negro">
                            <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image<? echo $res['id'];?>','','images/carrito_r.jpg',1)" onclick="agregarCarrito(<? echo "$count , ".$res['id']." , ".( ($res['precio']==0 && $res['id_proveedor']==7)?'true':'false' )."";?> )"> 
                                <img src="images/carrito.jpg" alt="" name="Image1" width="29" height="23" border="0" id="Image<? echo $res['id'];?>" />
                            </a>
                        </td>
                    </tr>
                  
            <?
                    $count=$count+1;
                    if($color == 'white')
                    {
                        $color = '#E3E3E3';
                    }
                    else 
                    {
                        $color = 'white';
                    }
	            }
	        ?>
    </tbody>
                </table>
                
                </form></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
            <td width="14" valign="top"><img src="images/sombra_productos_gris.jpg" width="14" height="553" /></td>
            <td valign="top" bgcolor="#e5e5e6" width="290px" style="max-width:290px"><table width="290" border="0" cellspacing="0" cellpadding="0" style="max-width:290px">
              <tr width="290px" style="max-width:290px">
                <td width="290"><img src="images/spacer.gif" width="20" height="16" /></td>
              </tr>
              <tr>
                <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
              </tr>
              <tr>
                <td class="texto_info_blanco" style="background-image:url(images/bkg_1.jpg); padding:5px; font-weight:bold">PRODUCTOS AGREGADOS</td>
              </tr>
              <tr>
                <td><img src="images/spacer.gif" alt="" width="20" height="16" /></td>
              </tr>
              <tr>
                <td><table width="220" border="0" align="center" cellpadding="0" cellspacing="5">
                  
                  
                <?
if(isset($_SESSION['carrito'])){
	foreach ($_SESSION['carrito'] as $n => $producto) {
		?>
                  <tr>
                    <td><div align="left" class="texto_chico_gris"><? echo $producto->cantidad." ". $producto->codigo_interno; ?></div></td>
                  </tr>
                  
              
  <? 
	}
}
?>
                </table></td>
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
</body>
</html>