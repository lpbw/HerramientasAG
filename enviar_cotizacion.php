<?
ini_set('display_errors','1');
//ini_set('display_errors', 'On');
include_once 'coneccion.php';
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Cliente.php';
session_start();
include_once 'Contacto.php';
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
checarAcceso($_SESSION['accesos']['vendedor25']);
date_default_timezone_set('America/Chihuahua');
include_once 'guardarCotizacion.php';

if(isset($_REQUEST['contactos']))
{
	if($_REQUEST['contactos']!=""){
		$enviar_a_temporal = $_SESSION['enviar_a_temporal'];
		unset($_SESSION['enviar_a_temporal']);
	}
}else
{
$enviar_a_temporal['destinatarios'] ="";
$enviar_a_temporal['mensaje'] ="";
$enviar_a_temporal['subject'] ="";
}
if(isset($_POST['nuevo_contacto']))
if($_POST['nuevo_contacto']!=""){
    $_SESSION['enviar_a_temporal'] = array();
    foreach ($_POST as $key => $value) {
        $_SESSION['enviar_a_temporal'][$key]=$value;
    }
    ?><script>window.location = 'cambia_contacto.php?nuevo=true';</script><?
}
if(isset($_POST['genera']))
if($_POST['genera']=="Generar PDF"){
	date_default_timezone_set('America/Chihuahua');
	if(isset($_SESSION['cotizacion'])){
		include_once 'mailCotizacion.php';
		require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
		require_once ("dompdf/include/style.cls.php");
		
        $cliente = new Cliente();
		$cliente -> get($_SESSION['cotizacion'] -> id_cliente);
        $cotizacion = new Cotizacion();
        $cotizacion = $_SESSION['cotizacion'];
		$cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
		$html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false);
			$pdf = new DOMPDF();
			$pdf ->load_html($html);
			$pdf ->render();
			$archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
			file_put_contents($archivo,$pdf->output());
			$pdf ->stream("cotizacion_".$cotizacion->id.".pdf");
	}
}

if(isset($_POST['enviar'])){
//genrar pdf
	date_default_timezone_set('America/Chihuahua');
	if(isset($_SESSION['cotizacion'])){
            include_once 'mailCotizacion.php';
            require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
            require_once ("dompdf/include/style.cls.php");
		
            $cliente = new Cliente();
            $cliente -> get($_SESSION['cotizacion'] -> id_cliente);
            $cotizacion = $_SESSION['cotizacion'];

            /*
             * CREANDO LA COTIZACION EN PDF Y SUBIENDOLA AL SERVIDOR
             */
            $cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
            $html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false, $_POST['enviar']=="Enviar sin codigos");
            /*
             * CODIGO QUE CREA EL PDF Y LO SUBE
             */
            $pdf = new DOMPDF();
            $pdf ->load_html($html);
            $pdf ->render();
            $archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
            $archivo_name="cotizacion_".$cotizacion->id.".pdf";
            file_put_contents($archivo,$pdf->output());
        }

    if(isset($_SESSION['cotizacion'])){
        
        /* guardando para, de y firma */
    $_SESSION['usuario']->updateMailSettings(mysql_real_escape_string($_POST['de']), 
            mysql_real_escape_string($_REQUEST['saludo']),
            mysql_real_escape_string($_REQUEST['firma']));
    
        $cliente = new Cliente();
        $contacto  = new Contacto();
        $cotizacion = $_SESSION['cotizacion'];
        
//        switch ( $_POST['enviar_a'] ) {
//            case 'cliente':

				foreach ($_POST['contactos'] as $n=>$idContacto) {
                    if($n!=0){
					
					}
				}
				
                
                
                date_default_timezone_set('America/Chihuahua');
                $cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
                
                $cliente -> get($_SESSION['cotizacion'] -> id_cliente);
                $cc = "";
                $i=0;
				$es_compras=2;
                foreach ($_POST['contactos'] as $n=>$dato) {
					$da=explode("|",$dato);
					$idContacto=$da[0];
					
					if($da[1]=="1")
						$es_compras=3;
                    if($n!=0){
                        $contacto -> get($idContacto , $_SESSION['cotizacion']->id_cliente);
                        $cc.=$contacto->email_contacto ;
                        if(count($_POST['contactos'])>$i)
                            $cc .=",";
                    }
                    $i++;
                }
                if($_SESSION['cotizacion'] -> id_estatus < 2 ){
                    $_SESSION['cotizacion'] -> id_estatus = $es_compras;
                    $_SESSION['cotizacion']->update($_SESSION['cotizacion']);
                }
				
                if($_POST['destinatarios']!="") $cc.=",".$_POST['destinatarios'];
                
                $contacto->get($_POST['contactos'][0], $_SESSION['cotizacion']->id_cliente);
                
                $cotizacion -> notificarCliente( $cliente, $sender = $_SESSION['usuario'],$_POST['subject'], 
                        "{$_REQUEST['saludo']} <br/>{$_POST['mensaje']}<br/>", $contacto,$cc, 
                                $archivo_name , $_REQUEST['firma'] ,isset($_POST['enviarCotEnCorreo']) );
                                
                                
                                
//                break;
//                
//            case 'compras':
//                if($_SESSION['cotizacion']->id_estatus < 4 ){
//                    $_SESSION['cotizacion'] -> id_estatus = 4;
//                    $_SESSION['cotizacion']->update($_SESSION['cotizacion']);
//                }
//                
//                $vendedor = $_SESSION['usuario'];
//                $compras = $vendedor->getCompras();
//                $_SESSION['cotizacion']->notificarUsuario( $cliente,
//                        $sender = $_SESSION['usuario'], $_POST['subject'],
//                        $_POST['mensaje'], $compras );
//                break;
//                
//            case 'supervisor':
//                $vendedor = $_SESSION['usuario'];
//                $supervisor = $vendedor->getSupervisor();
//                $_SESSION['cotizacion']->notificarUsuario( 
//                        $cliente, $sender = $_SESSION['usuario'],
//                        $_POST['subject'], $_POST['mensaje'],
//                        $supervisor, $asuntoSupervisor = "Revisi�n Supervisor" );
//                break;
//            
//            default:
//                break;
//        }

        ?><script>
            alert('Cotizacion enviada');
            parent.cerrarV();</script><?
    } else {
        ?><script>
            alert('Primero crea la cotizacion antes de enviarla.');
        parent.cerrarV();</script><?
    }
}


?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(function(){
        new nicEditor().panelInstance('saludo');
        new nicEditor().panelInstance('firma');
        new nicEditor().panelInstance('mensaje');});
    function format(){
        nicEditors.findEditor('saludo').saveContent();
        nicEditors.findEditor('firma').saveContent();
        nicEditors.findEditor('mensaje').saveContent();
    }
</script>

<script>
function validar(){
    var returnn = false;
    if(parent.validar()){
        $(":checkbox").each(function (){
            console.log($(this).attr('checked'));
            if($(this).attr('checked') && $(this).attr('name')!='enviarCotEnCorreo'){
                returnn = true;
                return false;
            }
        });
    }
    if(!returnn)
        alert('Escoge un destinatario');
    
    return returnn;
}
</script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.style5 {font-size: 18}
-->
</style>

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-right: 0px;
	margin-bottom: -10px;
	background-color: #FFFFFF;
	margin-top: -10px;
}
.style51 {font-size: 12}
</style>

<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style52 {font-size: 12}
.style52 {font-size: 12}
-->
</style>
</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px">
  
  <table width="700" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="3" align="right" valign="top" class="texto_info_negro">
          <!--<input name="ver_historial2" onClick="window.location='historial_correo.php';" type="button" class="texto_info_negro" id="ver_historial2" value="Ver Historial" />-->
        <input name="nuevo_contacto" type="submit" class="texto_info_negro" id="nuevo_contacto" value="Agregar Nuevo Contacto" /></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">ENVIAR</div></td>
      </tr>
    <tr>
      <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">De:</td>
      <td width="457" align="left" valign="middle" class="texto_info_negro"><input name="de" style="width:100%" type="text" class="texto_info_negro_forma" id="de"  
            value="<? if(!is_null($_SESSION['usuario']->deMail) || !empty($_SESSION['usuario']->deMail) || $_SESSION['usuario']->deMail!=""){
              echo $_SESSION['usuario']->deMail;
          } else echo $_SESSION['usuario']->nombre;?>" maxlength="100"></td>
      <td width="158" align="center" valign="middle" class="texto_info_negro">&nbsp;</td>
    </tr>
    <tr>
      <td width="65" align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Para:</td>
      <td align="left" class="texto_info_negro">
        <!--Contacto-->
        <p><?php
            $consulta  = "SELECT * FROM Contactos WHERE id_cliente = ".$_SESSION['cotizacion']->id_cliente;
            $resultado_estatus = mysql_query($consulta) or print(mysql_error()." EN SELECT CONTACTO");
            if(@mysql_num_rows($resultado_estatus)>=1){
                while($array=mysql_fetch_assoc($resultado_estatus)) { ?>
          <div  style="width:100%;"><label class="texto_info_negro_chico">
            <input type="checkbox" name="contactos[]" value="<? echo $array['id'];?>|<? echo $array['es_comprador'];?>">
            
            <? echo $array['nombre_contacto']." (".$array['email_contacto'].")";?>
            </label>
            <input name="editarContacto" type="button" onClick="window.location='cambia_contacto.php?id=<? echo $array['id'];?>'" class="texto_info_negro" id="editarContacto" value="Editar" />
          </div>
          <? }
            }?>
<!--          <input name="contactos[]" type="checkbox" id="contactoAdicional" value="">
          Otro 
          <input name="" style="width:400px" type="email" class="texto_info_negro_forma" id="contactos_email"  
            value="" maxlength="100" onChange="$('#contactoAdicional').val(this.value);$('#contactoAdicional').attr('checked',true);">-->
        </p></td>
      <td align="center" valign="top" class="texto_info_negro">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Cc:</td>
      <td align="left" valign="middle" class="texto_info_negro"><textarea name="destinatarios" rows="1" class="texto_info_negro_chico" id="destinatarios" style="width:100%"><? echo $enviar_a_temporal['destinatarios'];?></textarea></td>
      <td align="left" valign="middle" class="texto_info_negro"><em><span class="texto_info_negro_chico">Separar direcciones con coma </span></em></td>
    </tr>
    <tr>
      <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Asunto:</td>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro_chico">Herramientas AG Cotizacion #<? echo $_SESSION['cotizacion']->id;?>:
        <input name="subject" type="text" class="texto_info_negro_chico" id="subject" value="<? echo $enviar_a_temporal['subject'];?>" style="width:315px" maxlength="100"/>      </td>
      </tr>
	   <tr>
      <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Mensaje:</td>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro"><textarea name="mensaje" rows="2" id="mensaje" style="width:100%"><? echo $enviar_a_temporal['mensaje'];?></textarea></td>
      </tr>
       <tr>
         <td colspan="3" align="right" valign="top" class="texto_info_negro"><div align="center">
           <p>
             <input name="enviar" type="submit" class="texto_info_negro" onClick="format(); return validar();" value="Enviar" />
             <input name="enviar" type="submit" class="texto_info_negro" onClick="format(); return validar();" value="Enviar sin codigos" />
           </p>
           <p>
             <input type="checkbox" name="enviarCotEnCorreo" checked="checked" value="1">
Cotizacion en cuerpo del mensaje </p>
         </div></td>
       </tr>
      <tr>
      <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Saludo:</td>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro"><textarea name="saludo" rows="2"  id="saludo" style="width:100%"><?
          if(!is_null($_SESSION['usuario']->saludoMail) || !empty($_SESSION['usuario']->saludoMail) || $_SESSION['usuario']->saludoMail!="")
              echo $_SESSION['usuario']->saludoMail;
          else echo "Estimado Cliente.<div>Buen dia, le enviamos un cordial saludo.</div>
              <div>En relación a su requerimiento, le envío en el archivo anexo la cotización de los productos que nos solicitó.</div>";?>
          </textarea></td>
    </tr>
   
    <tr>
      <td align="right" valign="top" bgcolor="#E3E3E3" class="texto_info_negro">Firma:</td>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro"><textarea name="firma" rows="2" id="firma" style="width:100%"><?
          if(!is_null($_SESSION['usuario']->firmaMail) || !empty($_SESSION['usuario']->firmaMail) || $_SESSION['usuario']->firmaMail!="")
              echo $_SESSION['usuario']->firmaMail;
          else echo "Saludos cordiales";?>
          </textarea></td>
      </tr>
    
    <tr>
      <td colspan="3" align="center" valign="middle" class="texto_info_negro"><label></label></td>
    </tr>
    <tr><td><input type="submit" name="genera" id="genera" class="texto_info_negro" value="Generar PDF" /></td></tr>
    <tr>
      <td colspan="3" align="center" valign="middle" class="texto_info_negro">&nbsp;</td>
      </tr>
    </table>
</div>
</form>
</body>
</html>
