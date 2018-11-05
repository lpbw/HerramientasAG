<?
include 'Usuario.php';
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
}
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" /><link rel="stylesheet" href="colorbox.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
            <script src="colorbox/jquery.colorbox-min.js"></script>
<script>
    $(document).ready(function(){
        //Examples of how to assign the ColorBox event to elements
        $(".iframe").colorbox({iframe:true,width:"600", height:"553",transition:"fade", scrolling:true, opacity:0.5});
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
        if(confirm("Ã‚Â¿Esta seguro de borrar este evento?")){
                   $.colorbox({iframe:true,href:"adm_borrar_evento.php?id="+id ,width:"600", height:"453",transition:"fade", scrolling:false, opacity:0.5});
        }
    }
    function abrir(ir){
        $.colorbox({iframe:true,href:""+ir+"",width:"600", height:"353",transition:"fade", scrolling:true, opacity:0.5});
    }
</script>
<style type="text/css">
<!--
.style5 {font-size: 18}
-->
</style>
</head>
<body >
<form id="form1" name="form1" method="post" action="myadmin.php">
  <p align="center" class="texto_info_negro">Men&uacute; Administraci&oacute;n</p>
  <table width="363" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#FFFFFF">
    <tr> 
      <td width="363" class="texto_info_negro">
      <div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);"><strong>CATÁLOGOS Y REPORTES</strong></div>
      <ul class="texto_info_negro">
        <li><a href="adm_usuarios.php" class="texto_info_negro">Usuarios</a></li>
        <li><a href="adm_tipo_usuario.php" class="texto_info_negro iframe">Tipos de Usuario</a></li>
        <li><a href="adm_proveedores.php" class="texto_info_negro">Proveedores</a></li>
        <li><a href="reporte_ejecutivo.php" class="texto_info_negro">Reporte Ejecutivo</a></li>
		 <li><a href="adm_departamentos.php" class="texto_info_negro">Departamentos (Contactos)</a></li>
		<li><a href="adm_fletes.php" class="texto_info_negro">Fletes</a></li>
        <? if( $_SESSION['usuario'] -> id_rol == $_SESSION['accesos']['administrador'] ){ ?>
        <li><a href="adm_clientes_exportar.php" class="texto_info_negro">Exportar Clientes</a></li>
        <li><a href="reporte_articulos.php" class="texto_info_negro">Reporte de Articulos</a></li>
        
        <li><a href="adm_familias_cotizador.php" class="texto_info_negro iframe">Familias Cotizador</a></li>
        <li><a href="adm_revision_cambios_productos.php" class="texto_info_negro">Revision de Cambios de Productos</a>
        </li><? }?>
        </ul>
      <div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);"><strong>ACTUALIZACIONES</strong></div><ul>
        <li><a href="cambia_firma_mail.php" class="texto_info_negro iframe">Configurar datos de correo electrónico</a></li>
        <? if( $_SESSION['usuario'] -> id_rol == $_SESSION['accesos']['administrador'] ){ ?>
        <li><a href="leyenda_condiciones_cotizacion.php" class="texto_info_negro iframe">Leyenda Condiciones de Cotizaci&oacute;n</a></li>
        <li><a href="importar_existencias.php" class="texto_info_negro iframe">Actualizar Existencias</a></li>
        <li><a href="cambia_tipo_de_cambio_dolar.php" class="texto_info_negro iframe">Actualización del Dollar</a></li>
        <li><a href="cambia_pin.php" class="texto_info_negro iframe">Actualización del PIN</a></li>
        <li onclick="abrir('cambia_banner_email.php')" class="texto_info_negro" style="cursor: pointer">Actualizar banner de correo electrónico</li>
        <li class="texto_info_negro"><a href="importar_productos_online.php" class="texto_info_negro iframe">Actualizar Productos Online </a></li>
				<li class="texto_info_negro"><a href="importar_descuentos.php" class="texto_info_negro iframe">Actualización de factores y descuentos fuera de cotizador </a></li>
        <? } ?>
      </ul></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
