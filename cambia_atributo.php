<?
    include 'Usuario.php';
    include 'Producto.php';
    include 'Cotizacion.php';
    session_start();
    include "checar_sesion_admin.php";
    include "coneccion.php";
    $idcontacto = $_GET['idcontacto'];

    if($_REQUEST['id']!="")
    {
        $producto = new Producto();
        $producto->get($_REQUEST['id']);
        $producto->attrName = $_REQUEST['attrName'];
            
        foreach ($producto as $key => $value) 
        {
            if($key == $producto->attrName)
            {
                $producto->actualValue = $value;
            }
        }
        $_SESSION['cambia_atributo_producto'] = $producto;
    }

    if($_POST["guardar"]=="Guardar")
    {
        $producto = $_SESSION['cambia_atributo_producto'];
    
        if($_SESSION['cotizacion']->id!="")
        {
            $id_cotizacion = $_SESSION['cotizacion']->id;
            $id_version_cotizacion = $_SESSION['cotizacion']->id_version;
        }
        else
        {
            $id_cotizacion != "";
            $id_version_cotizacion !="";
        }    
    
        if($producto->updateOneAttr($producto->attrName,$_REQUEST['attrValue'], $_SESSION['usuario']->id,$requireRevision = TRUE, $id_cotizacion,$id_version_cotizacion))
        {
            
            if($_SESSION['cotizacion']->id!="")
            {
                echo "<script>alert('aqui 1');</script>";
                //echo "<script>parent.location = 'generar_cotizacion_p.php?idcontacto=$idcontacto';</script>";
                echo "<script>parent.location = 'generar_cotizacion_p.php?reloadCarritoOnId=$producto->id&idcontacto=$idcontacto';</script>";
            }
            else
            {
                echo "<script>parent.location.reload();</script>";
            }
            
        } 
        else 
        {
            echo "<script>alert('Error actualizando el comentario');</script>";
            echo "<script>parent.location.reload();</script>";
        }
    
    }


?>
<html>
    <head>
        <link href="images/textos.css" rel="stylesheet" type="text/css" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Untitled Document</title>
        <style type="text/css">
            .style6 {color: #FFFFFF}
            .style5 {font-size: 18}
            body {
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: -10px;
                background-color: #FFFFFF;
                margin-top: -10px;
            }
            .style51 {font-size: 12}
        </style>
        <script>
            function cambia_mayusculas(campo)
            {
                campo.value=campo.value.toUpperCase();
            }
        </script>
        <link href="images/textos.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <div align="center" style="margin:20px">   
                <table border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
                    <tr>
                        <td colspan="2" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">
                            <div align="center"><? echo $producto->codigo; ?>
                                <p>
                                    <? 
                                        $consulta  = "SELECT * FROM Proveedores WHERE id != 8";
                                        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
                                        if(@mysql_num_rows($resultado)>=1)
                                        {
                                            while($array=mysql_fetch_assoc($resultado)) 
                                            {
                                                if($array['id']==$producto->id_proveedor) echo $array['nombre'];
                                            }
                                        }
                                    ?>
                                </p>
                            </div>        
                        </td>
                    </tr>
                    <tr>
                        <td width="64" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">
                            Nombre Espa&ntilde;ol
                        </td>
                        <td width="218" bordercolor="#CCCCCC" class="texto_info_negro">
                            <? 
                                echo $producto->nombre; 
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">
                            <div align="right">
                                Nombre Ingles
                            </div>
                        </td>
                        <td bordercolor="#CCCCCC" class="texto_info_negro">
                            <?
                                echo $producto->descripcion;  
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" bordercolor="#CCCCCC" bgcolor="#E3E3E3" class="texto_info_negro" scope="row">
                            Unidad Métrica
                        </td>
                        <td bordercolor="#CCCCCC" class="texto_info_negro">
                            <?
                                echo $producto->unidad_metrica;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div align="center" class="texto_info_blanco" style="background-image: url(images/bkg_1.jpg);">
                                Cambiar
                                <?
                                    if($producto->attrName=='nombre')
                                        $nombre_producto = 'nombre en español';
                                    else if($producto->attrName=='descripcion')
                                        $nombre_producto  = 'nombre en inglés';
                                    else {
                                        foreach (split('_',  $producto->attrName) as $value) {
                                            $nombre_producto .= "$value ";
                                        }
                                    }
                                    echo ucwords($nombre_producto);
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr bordercolor="#CCCCCC">
                        <th colspan="2" valign="top" class="texto_info_negro" scope="row">
                            <input name="attrValue" type="text" id="attrValue"  onchange="cambia_mayusculas(this);" value="" size="43" maxlength="43">
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row">
                            <input name="guardar" type="submit" class="texto_info_negro" value="Guardar" />
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>
