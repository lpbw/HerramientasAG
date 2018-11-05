<?
include "Cliente.php";
session_start();
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
	background-image: url(images/bkg_1.jpg);
}
-->
</style>
<link href="images/textos.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #104352; }
-->
</style>
</head>
<?

$login=$_REQUEST["login"];
$pass=$_REQUEST["pass"];

if($login!=""){
    $cliente = new Cliente();
    if($cliente->login($login,$pass)){
		$_SESSION['cliente'] = $cliente;
		if($_SESSION['cliente']){
			?><script> window.location="<? echo $_REQUEST['atras']?>";</script><?    
    	}
	} else {
		?><script> alert('Usuario o contrase\u00f1a inv\u00e1lidos');</script><?  
	}
}
/*
$login=$_REQUEST["login"];
if($login!=""){
    $pass=$_REQUEST["pass"];
    $consulta  = "SELECT id from usuarios where usuario='$login' and contra='$pass' ";
    $resultado = mysql_query($consulta) or print("La consulta fall&oacute;P1: " );//. mysql_error()
    
    if(@mysql_num_rows($resultado)>=1){
        $res=mysql_fetch_row($resultado);
        $id=$res[0];
	$_SESSION['idU']=$id;
        echo"<script>window.location=\"menu.php\"</script>";
        
        }else {
            echo"<script>alert(\"Usuario o password invalido\");</script>";
        }
}*/

?>
<body ><form id="form1" name="form1" method="post" action="">
          <p align="center" class="texto_tit_nuestra">Login </p>
  <table width="53%" border="0" align="center">
            <tr>
              <td width="30%"><div align="right" class="style3"><span class="texto_contenido">usuario</span>:</div></td>
              <td colspan="2"><input name="login" type="text" id="login" size="20" maxlength="30" />              </td>
    </tr>
            <tr>
              <td><div align="right" class="style3"><span class="texto_contenido">password</span>:</div></td>
              <td colspan="2"><input name="pass" type="password" id="pass" size="20" maxlength="16" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3"><div align="center">
                  <input type="submit" name="submit" value="Entrar" />
              </div></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td><p align="center" class="texto_tit_nuestra">&nbsp;</p></td>
              <td width="35%">&nbsp;</td>
              <td width="35%" align="right"><a href="registra_cliente.php" target="_self"><img src="images/signup.png" alt="" name="singup" width="85" height="77" id="singup" /></a></td>
            </tr>
          </table>
  <p align="center" class="texto_tit_nuestra">&nbsp;</p>
</form>
</body>
</html>
