<?php
session_start();
//include "checar_sesion_admin.php";
include "coneccion.php";
include 'Usuario.php';

var_dump($_SESSION['usuario']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Herramientas AG </title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #FFFFFF;
	background-image: url(images/bkg_1.jpg);
}
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #104352; }
.style4 {color: #104352}
-->
</style>
</head>
<?php
$login=$_POST["login"];
$pass=$_POST["pass"];
$pin=$_POST["pin"];

if($login!=""){
    $usuario = new Usuario();
    $consulta  = "SELECT id
            FROM Usuarios
            WHERE email='{$_REQUEST['login']}'";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " );
       $result = mysql_fetch_assoc($resultado);
       $usuario->getUser($result['id']);
       $_SESSION['usuario']=$usuario;
	
}


?>
<body >
<table width="1024" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="1024" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="307" bgcolor="#FFFFFF"><form id="form1" name="form1" method="post" action="">
          <p align="center" class="texto_tit_nuestra">Administración</p>
          <table width="53%" border="0" align="center">
            <tr>
              <td width="30%" class="texto_info_negro_forma"><div align="right" class="style3"><span class="texto_contenido">usuario</span>:</div></td>
              <td width="70%"><input name="login" type="email" id="login" size="20" maxlength="50" />              </td>
            </tr>
            <tr>
              <td class="texto_info_negro_forma"><div align="right" class="style3"><span class="texto_contenido">password</span>:</div></td>
              <td><input name="pass" type="password" id="pass" size="20" maxlength="10" /></td>
            </tr>
            <tr>
              <td class="texto_info_negro_forma"><div align="right" class="style4">PIN</div></td>
              <td><input name="pin" type="password" id="pin" size="10" maxlength="4" /></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center">
                  <input type="submit" name="Submit" value="Entrar" />
              </div></td>
            </tr>
          </table>
        </form></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td height="63" valign="top">&nbsp;</td>
  </tr>
</table>
</body>
</html>
