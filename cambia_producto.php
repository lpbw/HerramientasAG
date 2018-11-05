<?
    include_once 'coneccion.php';
    include_once 'Usuario.php';
    include_once 'Producto.php';
    include_once 'functions_cotizacion.php';
    include_once "checar_sesion_admin.php";
    include_once "checar_acceso.php";
    include_once "checar_permisos.php";
    session_start();
    /*$id_usu=$_SESSION['usuario'];

    echo "<script>alert('$id_usu');</script>";*/
    setAccesoIndividual($_SESSION['accesos']['vendedor'],$_SESSION['accesos']['administrador'],$_SESSION['accesos']['supervisor'],$_SESSION['accesos']['vendedor25'],$_SESSION['accesos']['compras']);

    checarAcceso($_SESSION['accesos']['mostrador']);
    /*
    *PERMISOS DE ESCRITURA TIENEN: Administrador, Supervisor y Compras
    */

    $vistaLectura = tienePermisoEscritura($_SESSION['accesos']['mostrador'],$_SESSION['accesos']['vendedor'],$_SESSION['accesos']['vendedor25']);

    $editarProvYCatal = tienePermisoEscritura($_SESSION['accesos']['administrador'],$_SESSION['accesos']['supervisor'],$_SESSION['accesos']['compras']);

    /*
    * Variable para poner en modo lectura o escritura
    */
 
    function vistaEscritura($esLista)
    {
        global $vistaLectura;
        if($vistaLectura)
        {
            if($esLista)
            {
                return " disabled ";
            }
            else
            {
                return "readonly";
            }          
        }		
    }

    if($_GET['id']!="")
    {
        $producto = new Producto();
        $producto->get($_GET['id']);
        $_SESSION['cambiaProducto']=$producto;
        if($_GET['borrar']=='true')
        {
            $producto->delete();
            unset($_SESSION['cambiaProducto']);
            echo "<script>parent.location.reload();</script>";
        }
    }

    //Actualizar cambios en producto.
    if(isset($_POST["guardar"]))
    {
        $nombre = $_POST['nombre'];
        $id_catalogo_productos = $_POST['id_catalogo_productos'];
        $origen = $_POST['origen'];
        $precio = $_POST['precio'];
        $costo = $_POST['costo'];
        $id_proveedor = $_POST['id_proveedor'];
        $no_actualizado_en_microsip = $_POST['no_actualizado_en_microsip'];
        $archivo_ficha_tecnica = $_POST['archivo_ficha_tecnica'];
        $imagen = $_POST['imagen'];
        $descripcion = $_POST['descripcion'];
        $codigo_familia = $_POST['codigo_familia'];
        $unidad_metrica = $_POST['unidad_metrica'];
        $stock = $_POST['stock'];
        $stock_proveedor = $_POST['stock_proveedor'];
        $codigo_descuento = $_POST['codigo_descuento'];
        $unidad_metrica_ingles = $_POST['unidad_metrica_ingles'];
        $tipo_moneda_usa = $_POST['tipo_moneda'];
        $peso= $_POST['peso'];
        if($peso=="")
        {
            $peso=0;
        }	
	    $stock_fabrica= $_POST['entrega'];
        if($stock_fabrica=="")
        {
            $stock_fabrica=0;
        }
        $tienda= $_POST['tienda'];
        if($tienda=="")
        {
            $tienda=0;
        }
        $descripcion_l= $_POST['descripcion_l'];
        $producto = new Producto();
        $producto->get($_SESSION['cambiaProducto']->id);
        if($no_actualizado_en_microsip==1)
        {
            $modificado=0;
        }
        else 
        {
            $modificado =1;
        }		
        if($vistaLectura && !$editarProvYCatal)
        {
            /* PARA VENDEDOR, VENDEDOR25, MOSTRADOR, ETC. PARA ABAJO */
            //PLOADING IMAGE
            $campo = 'imagen';
            if($_FILES[$campo]['name']!="")
            {
                $uploadImage = $producto->uploadImage($campo);
            }
            else 
            {
                $uploadImage = TRUE;
            }
            //UPLOADING FICHA TECNICA
            $campo = 'archivo_ficha_tecnica';
            if($_FILES[$campo]['name']!="")
            {
                $uploadFichaTecnica = $producto->uploadFichaTecnica($campo);
            }
            else
            {
                $uploadFichaTecnica = TRUE;
            } 
            $updateSuccess = $uploadImage && $uploadFichaTecnica;          
        } 
        else if($editarProvYCatal)
        {
            //UPLOADING PROVEEDOR SOLO UN ATRIBUTO
            $attrName = 'id_proveedor';
            $attrValue = $_POST['id_proveedor'];
            $idUsuario = $_SESSION['usuario']->id; 
            $requireRevision = TRUE;
            $updateSuccess1 = $producto->updateOneAttr($attrName, $attrValue, $idUsuario, $requireRevision,0,0);
			//echo "<script>alert('$idUsuario');</script>";
            //UPLOADING CATALOGO PRODUCTOS SOLO UN ATRIBUTO
            $attrName = 'id_catalogo_productos';
            $attrValue = $_POST['id_catalogo_productos'];
            $idUsuario = $_SESSION['usuario']->id; 
            $requireRevision = TRUE;
            $updateSuccess2 = $producto->updateOneAttr($attrName, $attrValue, $idUsuario, $requireRevision,0,0);
            //NORMAL UPDATE
            $updateSuccess3 = $producto -> update($nombre,'id',$id_catalogo_productos,$precio,$costo,$id_proveedor,$modificado,$_FILES['archivo_ficha_tecnica']['name'],$_FILES['imagen']['name'],$descripcion, $codigo_familia,$unidad_metrica,$exportar_microsip="", $stock, $codigo_descuento,$tipo_moneda_usa,$unidad_metrica_ingles,$recargo=0,$esProductoEspecial = FALSE, $peso, $stock_fabrica, $tienda,$descripcion_l, $stock_proveedor, 0 , 0, 0, 0, 0);          
            //UPLOADING IMAGE
            $campo = 'imagen';
            if($_FILES[$campo]['name']!="")
            {
                echo "<script>alert('".$_FILES[$campo]['name']."');<script>";
                $uploadImage = $producto->uploadImage($campo);
            }
            else
            {
                $uploadImage = TRUE;
            } 
            
            //UPLOADING FICHA TECNICA
            $campo = 'archivo_ficha_tecnica';
            if($_FILES[$campo]['name']!="")
            {
                $uploadFichaTecnica = $producto->uploadFichaTecnica($campo);
            }
            else 
            {
                $uploadFichaTecnica = TRUE;
            }
            
            //UPDATE CODIGO 
            $updateSuccess4 = $producto->updateOneAttr($attrName='codigo', $attrValue=$_POST['codigo'],$_SESSION['usuario']->id, $requireRevision=FALSE,0,0);
            //UPDATE CODIGO INTERNO
            $codigo_interno = $producto->constructCodigoInterno();
            $updateSuccess5 = $producto->updateOneAttr($attrName='codigo_interno', $codigo_interno,$_SESSION['usuario']->id, $requireRevision=FALSE,0,0);
            $updateSuccess = $updateSuccess1 && $updateSuccess2 && $updateSuccess3 && $updateSuccess4  && $updateSuccess5 && $uploadImage && $uploadFichaTecnica ;
        }
        
        if( $updateSuccess )
        {
            if($_POST['guardar']=="Cambiar Origen")
            {
                echo "<script>document.location = 'seleccionar_origen_producto.php?id=$producto->id';</script>";
            }
            else
            {
                unset($_SESSION['cambiaProducto']);
                $pos = strpos($_POST['from'], 'generar_cotizacion.php');
                if($_REQUEST['from']=="adm_revision_cambios_productos.php")
                { 
                    echo "<script>parent.document.getElementById('no_aprobar_'".$producto->id.$_REQUEST['atributo']."').checked = true;</script>";
                    echo "<script>parent.cerrarV();</script>";
                } 
                else if( $pos === false )
                {
                    echo "<script>parent.location.reload();</script>";
                } 
                else
                {
                    echo "<script>parent.location = 'generar_cotizacion.php?reloadCarritoOnId=$producto->id';</script>";
                }
            }
        }
        else
        {
            echo "<script>alert('Verifica tu producto, algunos datos no se cargaron correctamente');</script>";
        }
    }//fin actualilzar producto.

    /*Nuevo producto
        el nombre del boton guardar es crear cuando el producto no tiene id.
    */
    if($_POST['crear']!="")
    {
        $nombre = $_POST['nombre'];//nombre producto
        $descripcion = $_POST['descripcion'];//nombre ingles
        $costo = $_POST['costo'];//precio de lista
        $tipo_moneda_usa = $_POST['tipo_moneda'];//Moneda
        $codigo = $_POST['codigo'];//Codigo de lista
        $unidad_metrica = $_POST['unidad_metrica'];//unidad metrica
        $unidad_metrica_ingles = $_POST['unidad_metrica_ingles'];//unidad_metrica_ingles
        $stock = $_POST['stock'];//Stock
        $stock_proveedor = $_POST['stock_proveedor'];//Stock proveedor
        $codigo_descuento = $_POST['codigo_descuento'];// codigo descuento
        $id_proveedor = $_POST['id_proveedor'];//Proveedor
        $id_catalogo_productos = $_POST['id_catalogo_productos'];//catalogo de producto
        $archivo_ficha_tecnica = $_POST['archivo_ficha_tecnica'];//ficha tecnica
        $codigo_familia = $_POST['codigo_familia'];//familia
        $codigo_microsip = $_POST['codigo_microsip'];//codigo miscrosip
        $descripcion_l= $_POST['descripcion_l'];//descripcion larga
        $peso= $_POST['peso'];//peso web
        $stock_fabrica= $_POST['entrega'];//Tiempo entrega
        $tienda=$_POST['tienda'];//on line
        $precio = $_POST['precio'];//precio input oculto
        $actualizado_en_microsip = $_POST['actualizado_en_microsip'];//modificado
        $imagen = $_POST['imagen'];
        $origen = $_POST['origen'];
        $no_actualizado_en_microsip = $_POST['no_actualizado_en_microsip'];
        $codigo_interno='';
        $numero_consecutivo='';
        $exportar_microsip=0;
        $existencia='';
        if($peso=="")
        {
            $peso=0;
        }
        if($stock_fabrica=="")
        {
            $stock_fabrica=0;
        }
        if($tienda=="")
        {
            $tienda=0;
        }
        $producto = new Producto();
        //Crea el producto con la funcion create en el archivo producto.php
        if($producto->create($nombre,$id_catalogo_productos,$precio, $costo, $id_proveedor, $actualizado_en_microsip,$_FILES['archivo_ficha_tecnica']['name'],$_FILES['imagen']['name'],$descripcion,$codigo,$codigo_familia,$codigo_microsip,$numero_consecutivo,$unidad_metrica, $unidad_metrica_ingles,$origen,$tipo_moneda_usa,$codigo_descuento,$stock,$exportar_microsip,$existencia,$codigo_interno, $recargo=0,$descripcion_l, $peso, $stock_fabrica, $tienda, $stock_proveedor, 0, 0 , 0, 0 ,0))
        {
            //UPLOADING PROVEEDOR SOLO UN ATRIBUTO
            $attrName = 'id_proveedor';
            $attrValue = $_POST['id_proveedor'];
            $idUsuario = $_SESSION['usuario']->id; 
            $requireRevision = TRUE;
            $updateSuccess1 = $producto->updateOneAttr($attrName, $attrValue, $idUsuario, $requireRevision,0,0, 0, 0);
                
            //UPLOADING CATALOGO PRODUCTOS SOLO UN ATRIBUTO
            $attrName = 'id_catalogo_productos';
            $attrValue = $_POST['id_catalogo_productos'];
            $idUsuario = $_SESSION['usuario']->id; 
            $requireRevision = TRUE;
            $updateSuccess2 = $producto->updateOneAttr($attrName, $attrValue, $idUsuario, $requireRevision,0,0);
                
            //NORMAL UPDATE
            $updateSuccess3 = $producto -> update($nombre,'id',$id_catalogo_productos,$precio,$costo,$id_proveedor,$modificado,$_FILES['archivo_ficha_tecnica']['name'],$_FILES['imagen']['name'],$descripcion, $codigo_familia,$unidad_metrica,$exportar_microsip="", $stock, $codigo_descuento,$tipo_moneda_usa,$unidad_metrica_ingles,$recargo=0,$esProductoEspecial = FALSE);
                
            //UPLOADING IMAGE
            $campo = 'imagen';
            if($_FILES[$campo]['name']!="")
            {
                $uploadImage = $producto->uploadImage($campo);
            }  
            else
            {
                $uploadImage = TRUE;
            } 
                
            //UPLOADING FICHA TECNICA
            $campo = 'archivo_ficha_tecnica';
            if($_FILES[$campo]['name']!="")
            {
                $uploadFichaTecnica = $producto->uploadFichaTecnica($campo);
            }
            else
            {
                $uploadFichaTecnica = TRUE;
            } 
                
            //UPDATE CODIGO 
            $updateSuccess4 = $producto->updateOneAttr($attrName='codigo', $attrValue=$_POST['codigo'],$_SESSION['usuario']->id, $requireRevision=FALSE,0,0);
            //UPDATE CODIGO INTERNO
            $codigo_interno = $producto->constructCodigoInterno();
            $codigo_interno="$codigo_interno$codigo";
            $updateSuccess5 = $producto->updateOneAttr($attrName='codigo_interno', $codigo_interno,$_SESSION['usuario']->id, $requireRevision=FALSE,0,0);
            $updateSuccess = $updateSuccess1 && $updateSuccess2 && $updateSuccess3 && $updateSuccess4  && $updateSuccess5 && $uploadImage && $uploadFichaTecnica;
            unset($_SESSION['cambiaProducto']);
            echo "<script>parent.location.reload();</script>";
        }
    }//fin nuevo producto
?>

<!DOCTYPE HTML>
<html>
    <head>
        <link href="images/textos.css" rel="stylesheet" type="text/css" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Cambia Producto</title>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://api.jquery.com/resources/events.js"></script>
        <style type="text/css">
            body
            {
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: -10px;
                background-color: #FFFFFF;
                margin-top: -10px;
            }
            .style6 {color: #FFFFFF}
            .style5 {font-size: 18}
            .style51 {font-size: 12}
        </style>
        <script>    
            function validar()
            {
                var returnn=true;
                $( "select" ).each(function( index )
                {
                    console.log($(this));
                    if( ($(this).val() == "" || $(this).val() == undefined || $(this).val() == 0 || $(this).val() == "0" ) && $(this).attr('name')!= 'codigo_familia' && $(this).attr('name')!= 'entrega')
                    {
                        $(this).focus();
                        var nameA = $(this).attr('name').split('_');
                        var name = "";
                        for(var i=0;i<nameA.length;i++)
                        {
                            if(nameA[i] != "id")
                                name += nameA[i]+ ' ';
                        }       
                        alert('Selecciona ' + name);
                        returnn = false;
                        return false;
                    }
                });
                return returnn;
            }

            function viewOrigen(obj)
            {
                
                var select = document.getElementById('id_catalogo_productos');
                JSONDoc(obj.value,select);
                if(obj.value==7)
                {
                    element = document.getElementById('bttnOrigen');
                    element.style.visibility = 'inherit';
                    element.style.width='';
                    element.style.height='';
                }
                else 
                {
                    element = document.getElementById('bttnOrigen');
                    element.style.visibility = 'hidden';
                    element.style.width='0px';
                    element.style.height='0px';
                }
            }

            function JSONDoc(idProv,obj)
            {
                var xmlhttp;
                var idProveedor= idProv;
                var object = obj;
                object.innerHTML = "";
                var optionCargando = document.createElement('option');
                optionCargando.value = "";
                optionCargando.text = "Cargando...";
            
                if (window.XMLHttpRequest)
                {
                    // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
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
                        console.log(xmlhttp.responseText);
                        var array = JSON.parse(xmlhttp.responseText);
                        object.innerHTML = "";
                    
                        /*
                        * INSERTANDO LA PRIMERA OPCION 
                        */
                        var option = document.createElement('option');
                        option.value = "0";
                        option.text = "-SELECCIONA-";
                        object.add(option);

                        for(var i=0;i<array.length;i++)
                        {
                            var option = document.createElement('option');
                            option.value = array[i].id;
                            option.text= array[i].nombre;
                            option.onchange="window.location = window.location + '&idCat='+this.value";
                            object.add(option);
                        }
                    } 
                    else
                    {
                        if(object.innerHTML=="")
                        {
                            object.add(optionCargando);
                        }
                    }
                }
                xmlhttp.open( "GET" , "WebServiceOf.php?tipo=catalogo&idProveedor=" + idProveedor , true );
                xmlhttp.send();
            }

            function cambia_mayusculas(campo)
            {
                campo.value=campo.value.toUpperCase();
                //alert(campo.value);
            }
        </script>
    </head>
<body>
    <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
        <div style="height: 453px">
            <div align="center" style="margin:20px">
                <table width="695" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
                    <tr>
                        <td colspan="3">
                            <div align="center" class="texto_info_blanco" style="background-image: url(images/bkg_1.jpg);">
                                PRODUCTO
                                <input name="from" type="hidden" id="from" value="<? echo end(explode('/',$_SERVER['HTTP_REFERER']));?>">
                                <input name="atributo" type="hidden" id="atributo" value="<? echo $_REQUEST['atributo'];?>">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="127" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">
                            Nombre Espa&ntilde;ol
                        </td>
                        <td width="219" bordercolor="#CCCCCC" class="texto_info_negro">
                            <?
                                if($vistaLectura)
                                { 
			                        echo $producto->nombre; 
                                }
                                else
                                {
                            ?>
                                    <input name="nombre" type="text" class="texto_info_negro_forma" id="nombre"  onchange="cambia_mayusculas(this);" value="<?php echo htmlspecialchars($producto->nombre); ?>" size="30" maxlength="43"/>
                            <? 
                                }
                            ?>
                        </td>
                        <td width="284" rowspan="13" class="texto_info_negro">
                            <table border="0" cellspacing="0" cellpadding="1" bordercolor="#CCCCCC">
                                <!-- Cargar la imagen con el input -->
                                <tr>
                                    <td>
                                        Imagen
                                    </td>
                                    <td>
                                        <span class="style5">
                                            <input name="imagen" type="file" class="texto_info_negro" id="imagen" style="max-width:200px">
                                        </span>
                                    </td>
                                </tr>

                                <!-- mostrar imagen del producto -->
                                <tr>
                                    <td colspan="2">
                                        <div align="center" id="imagen">
                                            <?
                                                if($producto->imagen!="")
                                                {
                                            ?>
                                                    <a href="<? echo $producto->imagen?>" target="_blank">
                                                        <img src="<?php echo $producto->imagen;?>" alt="" name="imagenMostrar" height="150" id="imagenMostrar">
                                                    </a>
                                            <?
                                                }
                                                else
                                                {
                                                    echo "Sin imagen";
                                                }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
        <tr>
          <td colspan="2" class="texto_info_negro">(Tama&ntilde;o 202px x 180px JPG)</td>
          </tr>
      </table></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Nombre Ingles</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><?
    if($vistaLectura) {
        echo $producto->descripcion;
    } else {
		    ?>
        <input name="descripcion" type="text" class="texto_info_negro_forma" id="descripcion" onchange="cambia_mayusculas(this);" value="<? echo htmlspecialchars($producto->descripcion);?>" size="30" maxlength="43"/>
        <?
    }
    ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Precio de Venta</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro">$ <? echo $producto->precio; ?>
        <input type="<? // echo $vistaLectura ? "hidden" : "text";?>hidden" name="precio" id="precio" value="<?php echo $producto->precio; ?>" class="texto_info_negro"/>        </td>
      </tr>
    <tr>
      <td align="right" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro"><div align="right">Precio de Lista</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro">$
        <? if($vistaLectura) echo $producto->costo; else { ?>
        <input type="<? echo $vistaLectura ? "hidden" : "text";?>" name="costo" id="costo" value="<?php echo $producto->costo; ?>" class="texto_info_negro"/> <? } ?></td>
      </tr>
    <tr>
      <td align="right" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro">Moneda</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><table width="150" border="0" cellspacing="2" cellpadding="0">
        <tr>
          <td width="20"><input name="tipo_moneda" type="radio" id="tipo_moneda_mx" value="0" <? 
		  if($producto!=""){
			 if($producto->tipo_moneda_usa == 0) echo "checked";
		} else if($_SESSION['cotizacion'] ->tipo_moneda == 0) echo "checked";?> /></td>
          <td width="21" class="texto_info_negro">MX</td>
          <td width="21"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
          <td width="20"><input type="radio" name="tipo_moneda" value="1" id="tipo_moneda_usa" <?  if($producto!=""){
			 if($producto->tipo_moneda_usa == 1) echo "checked";
		}else if($_SESSION['cotizacion'] ->tipo_moneda == 1) echo "checked";?>  /></td>
          <td width="30" class="texto_info_negro">USA</td>
          <td width="74" class="texto_info_negro">(<? echo $_SESSION['dollar']?>)</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="right" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro"><div align="right">Código de Lista</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><?
    if($vistaLectura) {
        echo $producto->codigo;
    } else {
		    ?>
        <input name="codigo" type="text" class="texto_info_negro_forma" id="codigo" value="<? echo htmlspecialchars($producto->codigo);?>" maxlength="45" />
        <?
    }
    ?></td>
    </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Unidad Métrica</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) {
		  echo $producto->unidad_metrica;
		  } else {?>
        <span class="style51">
        <select name="unidad_metrica" class="texto_info_negro_forma" id="unidad_metrica"  >
          <option value="">Unidad Metrica</option>
          <?php
    $consulta  = "SELECT id, nombre from medidas order by nombre";
    $resultado_clientes= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
    while($array=mysql_fetch_assoc($resultado_clientes)) {
        ?>
          <option value="<? echo $array['nombre'];?>" <? if(strtolower($array['nombre']) == strtolower($producto->unidad_metrica))echo"selected";?>><? echo $array['nombre'];?></option>
          <?
    }
    ?>
        </select>
        </span><?
		  } ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Unidad Métrica Inglés</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) {
		  echo $producto->unidad_metrica_ingles;
		  } else {?>
        <input name="unidad_metrica_ingles" type="text" class="texto_info_negro_forma" id="unidad_metrica_ingles" value="<?php echo $producto->unidad_metrica_ingles; ?>" maxlength="50" />
        <?
		  } ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Stock</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) echo $producto->stock; ?>        <input name="stock" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro_forma" id="stock" value="<?php echo $producto->stock; ?>" maxlength="30" <? echo vistaEscritura(false);?> /></td>
      </tr>
	  <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Stock Proveedor</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) echo $producto->stock_proveedor; ?>        <input name="stock_proveedor" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro_forma" id="stock_proveedor" value="<?php echo $producto->stock_proveedor; ?>" maxlength="30" <? echo vistaEscritura(false);?> /></td>
      </tr>
	  
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">Codigo Descuento</td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) echo $producto->codigo_descuento; ?>        <input name="codigo_descuento" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro_forma" id="codigo_descuento" value="<?php echo $producto->codigo_descuento;?>" maxlength="30" <? echo vistaEscritura(false);?> /></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Proveedor</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? 
      if( $vistaLectura && !$editarProvYCatal) {
        $consulta  = "SELECT * FROM Proveedores WHERE id = ".$producto->id_proveedor;
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        $array = @mysql_fetch_assoc($resultado);
        echo $array['nombre'];

        } else {
            ?>
        <select name="id_proveedor" class="texto_info_negro_forma" id="id_proveedor" 
                style="width:200px"
                onchange="viewOrigen(this)">
          <?
            echo $producto->id_rol;
            ?>
          <option value="0">- -</option>
          <?
	    $consulta  = "SELECT * FROM Proveedores WHERE id != 8";
            $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
            if(@mysql_num_rows($resultado)>=1){
                while($array=mysql_fetch_assoc($resultado)) {
                ?>
          <option value="<? echo $array['id'];?>" <? if($array['id']==$producto->id_proveedor) echo "selected";?>><? echo $array['nombre'];?></option>
          <?
                }
            }
            ?>
          </select>
         <?
        }
    ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">
          <div align="right">Cat&aacute;logo de productos</div>        </td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><span class="style5"><span style="float:left">
<? if($vistaLectura &&  !$editarProvYCatal) { 
        $consulta  = "SELECT nombre FROM CatalogoProductos WHERE id = ".$producto->id_catalogo_productos;
        $resultado = mysql_query($consulta) or print("La consulta CatalogoProductos: " . mysql_error());
        $array = @mysql_fetch_assoc($resultado);
        echo $array['nombre'];

} else { ?>
        <select  name="id_catalogo_productos"  id="id_catalogo_productos" 
                 class="texto_info_negro_forma" style="width:200px">
          <option value="0">-- Cat&aacute;logos --</option>
          <?
	    $consulta  = "SELECT nombre,id 
		FROM CatalogoProductos
		WHERE id_proveedor = ".$producto->id_proveedor;
        $resultado = mysql_query($consulta) or print("La consulta Proveedor: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
    while($array=mysql_fetch_assoc($resultado)): ?>
          <option value="<? echo $array['id'];?>" <? 
          if($array['id'] == $producto->id_catalogo_productos) echo "selected";?> onchange="window.location = window.location + '&idCat='+this.value"><? echo $array['nombre'];?></option><?
    endwhile;
        }
     
		  ?>
          </select>
<? }?>
                  <div id="bttnOrigen" 
                         style="<? if($producto->id_proveedor!=7){
                             echo 'visibility: hidden; width:0px; height: 0px;';
                         }else echo 'width:200px';?>
                         ">
              <? $origen = explode('_',$producto->origen);
 foreach ($origen as $value) {
     echo "$value ";
 }?>
        <input name="guardar" type="submit" id="origen" value="Cambiar Origen"/>
        </div>
</span></span></td>
    </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Ficha T&eacute;cnica</div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro">
          <input name="archivo_ficha_tecnica" type="file" class="texto_info_negro" id="archivo_ficha_tecnica" style="max-width: 200px"></td>
      <td width="284" rowspan="3" align="left" class="texto_info_negro">
        
        
        <? if($producto->archivo_ficha_tecnica!=""){?>
        <a href="<? echo $producto->archivo_ficha_tecnica;?>" target="_blank"><? echo $producto->archivo_ficha_tecnica;?>
            <img src="images/pdf.ico" alt="pdf" width="40" height="40" border="0" /></a>
        <? }?></td>
    </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Familia </div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) {
			if($producto->codigo_familia!=""){ 
        $consulta  = "SELECT * FROM FamiliaCotizador WHERE id = ".$producto->codigo_familia;
        $resultado = mysql_query($consulta) or print("La consulta lista familias: " . mysql_error());
        $array=mysql_fetch_assoc($resultado);
        echo $array['nombre']." (".$array['codigo'].")";
			} else echo "Sin Familia";

        } else {
            ?>
        <select name="codigo_familia" class="texto_info_negro_forma" id="codigo_familia" style="width:200px" <? echo vistaEscritura(true);?>>
          <option value="">- -</option>
          <?php
	    $consulta  = "SELECT * FROM FamiliaCotizador ORDER BY nombre";
        $resultado_familia= mysql_query($consulta) or print("La consulta familia: " . mysql_error());
        if(@mysql_num_rows($resultado_familia)>=1){
			echo ">1";
            while($array=mysql_fetch_assoc($resultado_familia)) {
                ?>
          <option <? if($producto->codigo_familia==$array['codigo']) echo 'selected';?> value="<? echo $array['codigo'];?>"><? echo $array['nombre']." (".$array['codigo'].")";?></option>
          <?
            }
        }
     
	  ?>
          </select>
        <?
	  }
	  ?></td>
      </tr>
    <tr>
      <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Codigo Microsip </div></td>
      <td bordercolor="#CCCCCC" class="texto_info_negro"><? if($vistaLectura) { 
			echo $producto->codigo_microsip; 
			} else {
				?>
        <? if($vistaLectura) echo $producto->codigo_microsip; ?>
        <input name="codigo_microsip" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro" id="codigo_microsip" value="<? echo $producto->codigo_microsip;?>" <? if($producto!="") echo "readonly";?> <? echo vistaEscritura(false);?>>
        <? }?></td>
      </tr>
    <tr>
      <td align="center" valign="top" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="texto_info_negro" scope="row"><div align="right">Descripcion Larga </div></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><? if($vistaLectura) { 
			echo $producto->nombre; 
			} else { ?>
        <textarea name="descripcion_l" cols="30" rows="5" class="texto_info_negro_forma" id="descripcion_l" onChange="cambia_mayusculas(this);"><?php echo htmlspecialchars($producto->descripcion_l); ?></textarea>
        <? 	}?></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" valign="top" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="texto_info_negro" scope="row"><div align="right">Peso Web </div></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><div align="left">
        <? if($vistaLectura) echo $producto->peso; ?>
        <input name="peso" type="<? echo $vistaLectura ? "hidden" : "text";?>" class="texto_info_negro_forma" id="peso" value="<?php echo $producto->peso; ?>" maxlength="30" <? echo vistaEscritura(false);?> />
      </div></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" valign="top" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="texto_info_negro" scope="row"><div align="right">Tiempo Entrega </div></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><div align="left">
        <select name="entrega" class="texto_info_negro_forma" id="entrega" 
                style="width:200px"
               >
          
          <option value="0">- -</option>
          <?
	    $consulta  = "SELECT * FROM tiempo_entrega ";
            $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
            if(@mysql_num_rows($resultado)>=1){
                while($array=mysql_fetch_assoc($resultado)) {
                ?>
          <option value="<? echo $array['id'];?>" <? if($array['id']==$producto->stock_fabrica) echo "selected";?>><? echo $array['nombre'];?></option>
          <?
                }
            }
            ?>
        </select>
      </div></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" valign="top" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="texto_info_negro" scope="row"><div align="right">On line</div></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><table width="150" border="0" align="left" cellpadding="0" cellspacing="2">
        <tr>
          <td width="20"><input name="tienda" type="radio" id="radio" value="1" <? 
		  if($producto!=""){
			 if($producto->tienda == 1) echo "checked";
		} ?> /></td>
          <td width="21" class="texto_info_negro">Si</td>
          <td width="21"><span class="texto_chico_gris"><img src="images/spacer.gif" alt="" width="20" height="10" /></span></td>
          <td width="20"><input type="radio" name="tienda" value="0" id="radio2" <?  if($producto!=""){
			 if($producto->tienda == 0) echo "checked";
		}?>  /></td>
          <td width="30" class="texto_info_negro">No</td>
          <td width="74" class="texto_info_negro">&nbsp;</td>
        </tr>
      </table></td>
      <td align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><?// if( !$vistaLectura ){ ?>
        <input type="submit" name="<? if($producto!="") echo "guardar"; else echo "crear";?>" value="Guardar" onclick="return (validar())"/>
        <?// } ?></td>
    </tr>
    </table>
    </div>
</div>
</form>
</body>
</html>