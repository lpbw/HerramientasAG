<?

include_once 'Usuario.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
include_once "getFormatedNumberForMoney.php";
include_once 'coneccion.php';
checarAcceso($_SESSION['accesos']['vendedor25']);
if(isset($_POST['guardar'])){
    $_SESSION['usuario']->updateMailSettings(mysql_real_escape_string($_POST['de']), 
            mysql_real_escape_string($_POST['saludo']),
            mysql_real_escape_string($_POST['firma']));
    
//    updateMailSettings(mysql_real_escape_string($_POST['de']), 
//        mysql_real_escape_string($_POST['saludo']),
//        mysql_real_escape_string($_POST['firma']));
    
    ?><script>parent.cerrarV();</script><?
}
if(isset($_GET['id'])){
    $_SESSION['usuario']->getMailSettings();
//    getMailSettings();
}

//function getMailSettings(){
//    $id = $_SESSION['usuario']->id;
//    $consulta = "SELECT deMail, saludoMail, firmaMail FROM Usuarios WHERE id=$id";
//    $resultado = mysql_query($consulta) or print("No se ha podido obtener $consulta<br>");
//    $usuario = mysql_fetch_assoc($resultado);
//    $_SESSION['usuario']->saludoMail =$usuario['saludoMail'] ;
//    $_SESSION['usuario']->firmaMail = $usuario['firmaMail'];
//    $_SESSION['usuario']->deMail = $usuario['deMail'] ;
//}
//
//function updateMailSettings($de=NULL,$saludo=NULL,$firma=NULL){
//    $id = $_SESSION['usuario']->id;
//    $consulta = "UPDATE Usuarios SET deMail = '$de', saludoMail= '$saludo', firmaMail='$firma'
//        WHERE id=$id";
//    $resultado = mysql_query($consulta) or print("No se ha podido actualizar $consulta<br>");
//}
//
//function updateBannerMail($nombreCampo=NULL){
//    //---------------------------
//    if(!is_null($nombreCampo) || !empty($nombreCampo) || $nombreCampo!=""){
//        include_once 'UploadFiles.php';
//        $nombreArchivoSistema = "banner_mail";
//        $nombreArchivo = $_FILES[$nombreCampo]['name'];
//        
////        $archivo_location = $this->uploadFile($nombreArchivoSistema , 'archivos' , $nombreCampo);
//        $archivo_location = uploadFile($nombreArchivoSistema , 'archivos' , $nombreCampo);
//        if($archivo_location)
//            return TRUE;
//        else 
//            return FALSE;
//    }
//}

?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">
    bkLib.onDomLoaded(nicEditors.allTextAreas);
    function format(){
        $('textarea').each(function(){ 
            nicEditors.findEditor($(this).attr('name')).saveContent();
        });
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
  
  <table width="90%" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">OPCIONES DE CORREO</div></td>
      </tr>
    <tr>
      <td width="54" align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">De</td>
      <td width="219" align="left" valign="middle" class="texto_info_negro">
          <input name="de" type="text" class="texto_info_negro_forma" id="de"  
            value="<? if(!is_null($_SESSION['usuario']->deMail) || !empty($_SESSION['usuario']->deMail) || $_SESSION['usuario']->deMail!=""){
              echo $_SESSION['usuario']->deMail;
          } else echo $_SESSION['usuario']->nombre;?>" size="35" maxlength="100">
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Saludo</td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro">
          <textarea name="saludo" rows="4" class="texto_info_negro_forma"  id="saludo" style="width:100%"><?
          if(!is_null($_SESSION['usuario']->saludoMail) || !empty($_SESSION['usuario']->saludoMail) || $_SESSION['usuario']->saludoMail!="")
              echo $_SESSION['usuario']->saludoMail;
          else echo "Estimado Cliente.<div>Buen dia, le enviamos un cordial saludo.</div>
              <div>En relación a su requerimiento, le envío en el archivo anexo la cotización de los productos que nos solicitó.</div>";?>
          </textarea></td>
      </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" bgcolor="#E3E3E3" class="texto_info_negro">Firma</td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro">
          <textarea name="firma" rows="4" class="texto_info_negro_forma" id="firma" style="width:100%"><?
          if(!is_null($_SESSION['usuario']->firmaMail) || !empty($_SESSION['usuario']->firmaMail) || $_SESSION['usuario']->firmaMail!="")
              echo $_SESSION['usuario']->firmaMail;
          else echo "Saludos cordiales";?>
          </textarea></td>
      </tr>
    <tr>
        <td colspan="2" align="center" valign="top" class="texto_info_negro"><input name="guardar" type="submit" onClick="format();" class="texto_info_negro" value="Guardar" /></td>
    </tr>
    </table>
</div>
</form>
</body>
</html>
