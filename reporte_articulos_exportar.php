<?
$filename = 'reporte_articulos.xls';
header("Content-Type:   application/ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");
include_once "Usuario.php";
include_once "Producto.php";
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['todos']);


    $nombre = $_SESSION['buscadorReporteArt']['nombre'];
    $familia = $_SESSION['buscadorReporteArt']['familia'];
    $codigo_buscar = $_SESSION['buscadorReporteArt']['codigo_buscar'];
    $proveedor = $_SESSION['buscadorReporteArt']['proveedor'];
    $linea_descuento = $_SESSION['buscadorReporteArt']['linea_descuento'];
    $desde = $_SESSION['buscadorReporteArt']['desde'];
    $hasta = $_SESSION['buscadorReporteArt']['hasta'];


    if ($nombre != "") {
        $where.= " Productos.nombre LIKE '%$nombre%' AND ";
    }
    if ($familia != "") {
        $where.= " FamiliaCotizador.id = '$familia' AND ";
    }
    if ($codigo_buscar != "") {
        $where.= " Productos.codigo LIKE '%$codigo_buscar%' AND ";
    }
    if ($proveedor != "") {
        $where .= " Proveedores.id = $proveedor AND ";
    }
    if ($linea_descuento != "") {
        $where .= " Productos.id_catalogo_productos = $linea_descuento AND ";
    }
    if ($desde != "") {
        $where .= " Cotizaciones.fecha_ultima_modificacion >= \"$desde 00:00:00\" AND ";
    }
    if ($hasta != "") {
        $where .= " Cotizaciones.fecha_ultima_modificacion <= \"$hasta 23:59:59\" AND ";
    }
    
    
    $campo = $_SESSION['filter']['campo'] = $_POST['campo'];
    $sentido = $_SESSION['filter']['sentido'] = $_POST['sentido'];
    if ($sentido == "")
        $sentido = "DESC";

    switch ($campo) {
        case 'codigo_interno': $orderBy = " ORDER BY codigo_interno $sentido ";
            break;
        case 'borrador': $orderBy = " ORDER BY borrador $sentido ";
            break;
        case 'enviada_usuario': $orderBy = " ORDER BY enviada_usuario $sentido ";
            break;
        case 'enviada_compras': $orderBy = " ORDER BY enviada_compras $sentido ";
            break;
        case 'ganada': $orderBy = " ORDER BY ganada $sentido ";
            break;
        case 'perdida_desconocido':$orderBy = " ORDER BY perdida_desconocido $sentido ";
            break;
        case 'perdida_proy':$orderBy = " ORDER BY perdida_proy $sentido ";
            break;
        case 'perdida_tiempo':$orderBy = " ORDER BY perdida_tiempo $sentido ";
            break;
        case 'perdida_precio':$orderBy = " ORDER BY perdida_precio $sentido ";
            break;
        case 'perdidas':$orderBy = " ORDER BY perdidas $sentido ";
            break;
		case 'vivas':$orderBy = " ORDER BY vivas $sentido ";
            break;

        default: $orderBy = "";
            break;
    }
$cotizaciones = 1;
//$cotizaciones = 'CP.cantidad';

$consulta = "SELECT 
            Productos.id,
            CONCAT(Proveedores.prefijo, Productos.codigo) AS codigo_interno,
            SUM(if(Cotizaciones.id_estatus = 1, $cotizaciones, 0)) AS borrador,
            SUM(if(Cotizaciones.id_estatus = 2, $cotizaciones, 0)) AS enviada_usuario,
            SUM(if(Cotizaciones.id_estatus = 3, $cotizaciones, 0)) AS enviada_compras,
            SUM(if(Cotizaciones.id_estatus = 4, $cotizaciones, 0)) AS ganada,
            SUM(if(Cotizaciones.id_estatus = 5, $cotizaciones, 0)) AS perdida_desconocido,
            SUM(if(Cotizaciones.id_estatus = 6, $cotizaciones, 0)) AS perdida_proy,
            SUM(if(Cotizaciones.id_estatus = 7, $cotizaciones, 0)) AS perdida_tiempo,
            SUM(if(Cotizaciones.id_estatus = 8, $cotizaciones, 0)) AS perdida_precio,
            SUM(if(Cotizaciones.id_estatus = 5 || Cotizaciones.id_estatus = 6 || Cotizaciones.id_estatus = 7 || Cotizaciones.id_estatus = 8, $cotizaciones, 0)) AS perdidas,
			SUM(if(Cotizaciones.id_estatus = 1 || Cotizaciones.id_estatus = 2 || Cotizaciones.id_estatus = 3, $cotizaciones, 0)) AS vivas,
            EstatusCotizaciones.nombre,
            Productos.descripcion,
            Productos.stock,
            Productos.costo
    FROM Productos
        LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor 
        LEFT OUTER JOIN CatalogoProductos ON CatalogoProductos.id = Productos.id_catalogo_productos 
        LEFT OUTER JOIN FamiliaCotizador ON FamiliaCotizador.codigo = Productos.codigo_familia 
        INNER JOIN Cotizaciones_Productos AS CP ON CP.id_producto = Productos.id 
        INNER JOIN Cotizaciones ON Cotizaciones.id = CP.id_cotizacion 
        INNER JOIN EstatusCotizaciones ON EstatusCotizaciones.id = Cotizaciones.id_estatus
    WHERE $where 1
    GROUP BY Productos.id
    $orderBy";

$resultado = mysql_query($consulta) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error());
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Cotizaciones </title>
        <style type="text/css">
            body {
                margin-left: 0px;
                margin-right: 0px;
                margin-bottom: -10px;
                background-color: #FFFFFF;
                /*	background-image: url(images/bkg_1.jpg);*/
                margin-top: -10px;
            }
            .style51 {font-size: 18}
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
            .style54 {font-size: 11px}
            .style52 {font-size: 12}
            .style52 {font-size: 12}
.texto_info_blanco {
	font-family: "Helvetica";
	font-size: 13px;
	color: #FFFFFF;
	text-decoration: none;
	line-height: 20px;
}
        </style>
    </head>

    <body>
    <table width=""   cellpadding="0" cellspacing="0"  id="myTable01">
      <thead>
        <tr>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <div align="center"> Codigo Interno</div></th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Descr</div></th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Precio de lista</div></th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"><div align="center">Stock</div></th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> Borrador
            </div>
          </th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Enviada a usuario</th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Enviada a compras</th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">yGanada</th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Perdida: ?</th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> Perdida: Proyecto cerrado</th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <div align="center"> Perdida: Tiempo</div></th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Perdida: Precio</th>
          <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Total Perdidas</th>
		  <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">Vivas</th>
        </tr>
      </thead>
      <tbody>
        <?
                                            $count = 1;
                                            $color = 'white';
                                            $tm = "";

                                            $nombreEstatus = array(1 => 'borrador', 2 => 'enviada_usuario', 3 => 'enviada_compras', 4 => 'ganada',
                                                5 => 'perdida_desconocido', 6 => 'perdida_proy', 7 => 'perdida_tiempo', 8 => 'perdida_precio', 9 => 'perdidas', 10 => 'vivas');

                                            while (@mysql_num_rows($resultado) >= $count) {
                                                $res = mysql_fetch_assoc($resultado);
                                                ?>
        <tr bgcolor="<? echo"$color"; ?>" >
          <td class="texto_info_negro"><div align="center" class="texto_info_negro">
            <div align="left"><? echo $res['codigo_interno']; ?></div>
          </div></td>
          <td align="center" class="texto_info_negro"><? echo $res['descripcion'];?></td>
          <td align="center" class="texto_info_negro"><? echo $res['costo'];?></td>
          <td align="center" class="texto_info_negro"><? echo $res['stock'];?></td>
          <?
                                                    $var = 'variables';
                                                    reset($nombreEstatus);
                                                    foreach ($nombreEstatus as $value) {
                                                        $var = $value;
                                                        $$var+=floatval($res[$value]);
                                                        ?>
          <td align="center" class="texto_info_negro"><? echo $res[$value]; ?></td>
          <? } ?>
        </tr>
        <?
                                                $count = $count + 1;
                                                if ($color == 'white')
                                                    $color = '#E3E3E3';
                                                else
                                                    $color = 'white';
                                            }
                                            ?>
        <!--                                        <tfoot>
                                            <tr>
                                                <th align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">TOTAL</th>
                                                <?
                                                $consulta = "SELECT SUM(EstatusCotizaciones.id) AS total, EstatusCotizaciones.id 
    FROM EstatusCotizaciones LEFT OUTER JOIN Cotizaciones ON Cotizaciones.id_estatus = EstatusCotizaciones.id 
        LEFT OUTER JOIN Cotizaciones_Productos AS CP ON CP.id_cotizacion = Cotizaciones.id 
        LEFT OUTER JOIN Productos ON Productos.id = CP.id_producto 
        LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor 
        LEFT OUTER JOIN CatalogoProductos ON CatalogoProductos.id = Productos.id_catalogo_productos 
        LEFT OUTER JOIN FamiliaCotizador ON FamiliaCotizador.codigo = Productos.codigo_familia
    WHERE $where 1
    GROUP BY Cotizaciones.id
    $orderBy";

                                                $totales = mysql_query($consulta) or print("La consulta fallo lista productos: <Br> $consulta <br> " . mysql_error());

                                                $row = mysql_fetch_assoc($totales);
                                                foreach ($nombreEstatus as $key => $value) {
                                                    if (intval($row['id']) == $key) {
                                                        $total = $row['total'];
                                                        $row = mysql_fetch_assoc($totales);
                                                    } else
                                                        $total = 0;
                                                    ?>
                                                    <td align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco">
                                                        <label id="total_borrador"><? echo $total; ?></label>
                                                    </td><?
                                                    $count++;
                                                }
                                                ?> </tr>
                                        </tfoot>-->
      </tbody>
    </table>
    </body>
</html>