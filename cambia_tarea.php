<?
include_once 'coneccion.php';
include_once 'Tarea.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Cliente.php';
include_once 'Usuario.php';
session_start();
include_once 'Contacto.php';
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";


function getJSTotalTareasInMain($totalTareas){
    return "<script>
        parent.parent.totalTareas.innerHTML = $totalTareas;
        parent.totalTareas.innerHTML = $totalTareas;
            </script>";
}
if($_REQUEST['new']=="true"){
	unset( $_SESSION['cambiaTarea'] );
}

if($_GET['id']!=""){
    $tarea = new Tarea();
    $tarea->get( $_GET['id'] );
    $_SESSION['cambiaTarea'] = $tarea;
    if($_GET['borrar']=='true'){
        $tarea->delete();
        unset($_SESSION['cambiaTarea']);
        echo getJSTotalTareasInMain(count($_SESSION['usuario']->getTareas())); 
        ?><script>parent.cerrarV();</script><?
    }
} else if(isset($_SESSION['cambiaTarea'])){
	unset($_SESSION['cambiaTarea']);
}
$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
    
    $_SESSION['cambiaTarea']->id_usuario    = $_SESSION["usuario"]->id;
    $_SESSION['cambiaTarea']->fecha_inicio  = $_REQUEST["inicio"];
    $_SESSION['cambiaTarea']->fecha_vencimiento  = $_REQUEST["vencimiento"];
    $_SESSION['cambiaTarea']->fecha_recordatorio = $_REQUEST["recordatorio"];
    $_SESSION['cambiaTarea']->completada    = $_REQUEST["completada"];
    $_SESSION['cambiaTarea']->descripcion   = $_REQUEST["descripcion"];
    $_SESSION['cambiaTarea']->asunto        = $_REQUEST['asunto'];
    
        
    if( $_SESSION['cambiaTarea'] -> update( $_SESSION['cambiaTarea'] ) ){
        unset($_SESSION['cambiaTarea']);
        echo getJSTotalTareasInMain(count($_SESSION['usuario']->getTareas())); 
        ?><script>parent.cerrarV();</script><?
    } else {
        echo getJSTotalTareasInMain(count($_SESSION['usuario']->getTareas())); 
        ?><script>alert('error');
            parent.cerrarV();</script><?
    }
}

if($_POST['crear']!=""){
	
    $id = 'DEFAULT';
	
	if($_POST['new']=="true")
		$id_cotizacion 	= "";
	else
    	$id_cotizacion  = $_SESSION['cotizacion']->id;
		
    $id_usuario     = $_SESSION["usuario"]->id;
    $fecha_creacion = 'DEFAULT';
    $fecha_inicio   = $_REQUEST["inicio"];
    $fecha_vencimiento  = $_REQUEST["vencimiento"];
    $fecha_recordatorio = $_REQUEST["recordatorio"];
    $completada     = $_REQUEST["completada"];
    $descripcion    = $_REQUEST["descripcion"];
    $asunto = $_REQUEST['asunto'];
    
    $tarea = new Tarea();
    if($tarea->create($id, $id_cotizacion, $id_usuario, 
            $asunto, $fecha_inicio, $fecha_vencimiento, 
            $fecha_recordatorio, $completada, $descripcion )){
		unset($_SESSION['cambiaTarea']);
                echo getJSTotalTareasInMain(count($_SESSION['usuario']->getTareas())); 
		?><script>parent.cerrarV();</script><?
    } else {
        echo getJSTotalTareasInMain(count($_SESSION['usuario']->getTareas())); 
        ?><script>alert('error');
            parent.cerrarV();</script><?
    }
}



?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cambia Tarea</title>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<!--<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>-->
    
<!-- FOR DATEPICKER-------------------------------->
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  
<script>
  $(function() {
    $( "#inicio" ).datepicker({
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        showButtonPanel: true,
        onClose: function( selectedDate ){
          $( "#vencimiento" ).datepicker( "option" , "minDate" , selectedDate );
          $( "#recordatorio" ).datepicker( "option" , "minDate" , selectedDate );
      }
    });
    $( "#vencimiento" ).datepicker({
        numberOfMonths: 2,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        onClose: function( selectedDate ){
            $( "#inicio" ).datepicker( "option" , "maxDate" , selectedDate );
            $( "#recordatorio" ).datepicker( "option" , "maxDate" , selectedDate );
      }
    });
    $( "#recordatorio" ).datepicker({
        numberOfMonths: 2,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        onClose: function () {
            $( "#inicio" ).datepicker( 
                "option", "minDate",
                $( "#recordatorio" ).datepicker( "option" , "minDate" )
            );
            $( "#inicio" ).datepicker( 
                "option", "maxDate",
                $( "#recordatorio" ).datepicker( "option" , "maxDate" )
            );
            $( "#vencimiento" ).datepicker( 
                "option", "minDate",
                $( "#recordatorio" ).datepicker( "option" , "minDate" )
            );
            $( "#vencimiento" ).datepicker( 
                "option", "maxDate",
                $( "#recordatorio" ).datepicker( "option" , "maxDate" )
            );
			if ( $( "#recordatorio" ).value != "" )
				$("#activar_recordatorio").prop('checked', true);;
                
        }
    });
  });
  </script>
<script>
function validar(){
    var returnn = true;
	if($('#asunto').val()==""){
		returnn = false;
		alert('Falta asunto');
		$('#asunto').focus();
	} 
	return returnn;
		
}
function toggle_reminder(obj){
	if(obj.checked)
		document.getElementById('recordatorio').value='<? echo $tarea->fecha_recordatorio;?>';
	else
		document.getElementById('recordatorio').value='';
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
    <tr class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">
      <td valign="middle"> <input name="completada"  type="checkbox" class="texto_info_negro" id="completada" onClick="agregarIva(this)" value="1"  
                            <? if( intval($tarea->completada) == 1 ) echo "checked"; ?> />
        completada </td>
      <td colspan="2" align="center"><input type="hidden" value="<? echo $_REQUEST['new'];?>" name="new" id="new"/>TAREA</td>
      </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Asunto</td>
      <td width="230" align="left" class="texto_info_negro"><input name="asunto" type="text" class="texto_info_negro_forma" id="asunto" value="<? echo $tarea->asunto;?>" maxlength="100"/></td>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Tarea</td>
      </tr>
    <tr>
      <td width="104" align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro"> Inicio</td>
      <td width="230" align="left" valign="middle" class="texto_info_negro"><input name="inicio" type="text" class="texto_info_negro_forma" id="inicio" value="<? echo $tarea->fecha_inicio;?>" maxlength="100"/></td>
      <td width="346" rowspan="3" align="center" valign="top" class="texto_info_negro"><textarea name="descripcion" rows="6" class="texto_info_negro_forma" id="descripcion" style="width:275px"><? echo $tarea->descripcion;?></textarea></td>
    </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Vencimiento</td>
      <td align="left" valign="middle" class="texto_info_negro"><input name="vencimiento" type="text" class="texto_info_negro_forma" id="vencimiento" value="<? echo $tarea->fecha_vencimiento;?>" maxlength="100"/></td>
      </tr>
    <tr>
      <td align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro"><label>
        <input name="activar_recordatorio" type="checkbox" id="activar_recordatorio" onClick="toggle_reminder(this);"  
		<? if($tarea->fecha_recordatorio!="") echo "checked";?>>
        Recordatorio</label></td>
      <td align="left" valign="middle" class="texto_info_negro"><input name="recordatorio" type="text" class="texto_info_negro_forma" id="recordatorio" value="<? echo $tarea->fecha_recordatorio;?>" maxlength="100"/></td>
    </tr>
    <tr>
      <td colspan="3" align="center" valign="middle" class="texto_info_negro">
          <input name="<? if($tarea!="") echo "guardar"; else echo "crear";?>" type="submit" 
                 class="texto_info_negro" onClick="return validar()" value="Guardar" /></td>
    </tr>
    </table>
</div>
</form>
</body>
</html>
