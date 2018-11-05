<?

include_once 'Usuario.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "checar_acceso.php";
include_once 'coneccion.php';
checarAcceso($_SESSION['accesos']['administrador']);

if(isset($_POST['guardar'])){
    var_dump($_FILES);
    if($_FILES['banner']['error']==0){
        include_once 'UploadFiles.php';
        $nombreArchivo = "bannerMail";
        $ubicacion = "images";
        $campo = "banner";
        $width = 800;
        try {
            $UploadFiles = new UploadFiles();
//            $result = $UploadFiles->uploadFile($nombreArchivo,$ubicacion,$campo);
            $result = $UploadFiles->resizeImageAndUpload($nombreArchivo,$ubicacion,$campo, $width);
            if($result){
                $query = "UPDATE bannerMail SET rutaCompleta = '$result', carpeta = '$ubicacion'";
                $result = mysql_query($query) or print(mysql_error());
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
            die();
        }
        }  else { ?><script>alert('Agrega un archivo');</script><?}
}
/*
 * getting banner
 */
include_once 'mailCotizacion.php';
$banner = getBannerMail();


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
        <input type="file" name="banner" id="banner"></td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="middle" class="texto_info_negro"><input name="guardar" type="submit" class="texto_info_negro" value="Guardar" /></td>
      </tr>
      <td colspan="2" align="left" valign="middle" class="texto_info_negro">
          <img src="<? echo $banner['rutaCompleta'];?>"  alt="banner"  width="100%"></td>
    </tr>
    
    </table>
</div>
</form>
</body>
</html>
