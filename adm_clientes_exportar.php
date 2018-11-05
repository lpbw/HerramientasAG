<?
$filename = 'clientes.xls';
header("Content-Type:   application/ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=$filename");
include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <style type="text/css">
            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                background-color: #FFFFFF;
            }
            .style5 {font-size: 18}
            .style6 {color: #FFFFFF}
            .texto_info_blanco_forma {
                font-family: "Helvetica";
                font-size: 14px;
                color: #FFFFFF;
                text-decoration: none;
                line-height: 20px;
            }
        </style>
    </head>

    <body >
        <table width="100%" border="0" cellpadding="0">
            <?
            $consulta = "SELECT
                    Clientes.id,
                    Clientes.codigo,
                    CarteraClientes.nombre AS cartera,
                    nombre_empresa,
                    direccion_empresa,
                    telefono_empresa,
                    contrasenia,
                    nombre_contacto,
                    email_contacto,
                    telefono_contacto,
                    departamento_empresa,
                    Estados.nombre AS estado,
                    ciudad,
                    es_comprador,
                    rfc,
                    condiciones_pago,
                    prefijo,
                    alias,
                    Industrias.nombre AS industria
            FROM Clientes 
            inner join CarteraClientes_Usuarios 
                on Clientes.id_cartera=CarteraClientes_Usuarios.id_cartera_clientes 
            Left outer  join Industrias 
                on Industrias.id=Clientes.industria 
            Left outer  join Estados 
                on Estados.id=Clientes.estado 
            Left outer join CarteraClientes 
                on CarteraClientes.id = CarteraClientes_Usuarios.id_cartera_clientes 
            WHERE 1
            GROUP BY nombre_empresa
            ORDER BY nombre_empresa";

            $resultado = mysql_query($consulta) or print("La consulta fallo lista clientes: " . mysql_error());
            $count = 1;
            while (@mysql_num_rows($resultado)>=$count) {
                $res = mysql_fetch_row($resultado);
				if ($count == 1) {
                    ?>
                    <tr bgcolor="#DD1A22" >
                         <td class="style5">id</td>
						<td class="style5">codigo</td>
						<td class="style5">cartera</td>
						<td class="style5">nombre empresa</td>
						<td class="style5">direccion empresa</td>
						<td class="style5">telefono empresa</td>
						<td class="style5">contraseña</td>
						<td class="style5">nombre contacto</td>
						<td class="style5">email contacto</td>
						<td class="style5">telefono contacto</td>
						<td class="style5">departamento empresa</td>
						<td class="style5">estado</td>
						<td class="style5">ciudad</td>
						<td class="style5">es comprador</td>
						<td class="style5">rfc</td>
						<td class="style5">condiciones de pago</td>
						<td class="style5">prefijo</td>
						<td class="style5">alias</td>
						<td class="style5">industria</td>
                    </tr>
					
                <? } ?>
               
                <?
				$consulta2 = "SELECT * FROM Contactos  WHERE id_cliente=$res[0] ORDER BY nombre_contacto";

            $resultado2 = mysql_query($consulta2) or print("La consulta fallo lista clientes:$consulta2 " . mysql_error());
            $count2 = 1;
            while (@mysql_num_rows($resultado2)>=$count2) {
                $res2 = mysql_fetch_row($resultado2);
          
			?>
			 <tr>                  
                         <td class="style5"><? echo $res[0]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[1]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[2]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[3]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[4]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[5]; ?> &nbsp;</td>
						<td class="style5"> <? echo $res[6]; ?>&nbsp;</td>
						
						<td class="style5"> <? echo $res2[2] ?>&nbsp;</td>
						<td class="style5"> <? echo $res2[3] ?>&nbsp;</td>
						<td class="style5"> <? echo $res2[4] ?>&nbsp;</td>
						<td class="style5"> <? echo $res2[6] ?>&nbsp;</td>
						
						<td class="style5"><? echo $res[11]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[12]; ?> &nbsp;</td>
						
						<td class="style5"> <? echo $res2[7] ?>&nbsp;</td>
						<td class="style5"><? echo $res[14]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[15]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[16]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[17]; ?> &nbsp;</td>
						<td class="style5"><? echo $res[18]; ?> &nbsp;</td>
						
                    
                </tr>
           <? $count2++;  }
		   $count++;  }?>
        </table>
    </body>
</html>
