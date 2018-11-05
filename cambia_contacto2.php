<?
include_once 'Usuario.php';
include_once 'Producto.php';
include_once 'Cotizacion.php';
include_once 'Contacto.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
//print_r($contacto);

//print_r($_REQUEST);

$id_cliente=$_GET['id_cliente'];

if($_POST['crear']!=""){
    $nombre_contacto = $_POST['nombre_contacto']; 
    $email_contacto = $_POST['email_contacto'];
    $telefono_contacto = $_POST['telefono_contacto'];
    $contrasenia = $_POST['contrasenia'];
    $departamento_empresa  = $_POST['departamento_empresa'];
    $es_comprador = $_POST['es_comprador'];
	
    $contacto = new Contacto();
    
    if($contacto->create($id_cliente,
            $nombre_contacto, $email_contacto, $telefono_contacto, 
            $contrasenia, $departamento_empresa, $es_comprador)
            ){
		
        unset($_SESSION['cambiaContacto']);
        ?><script>window.location = 'adm_contactos.php';</script><?
    } else {
        ?><script>alert('Creacion no exitosa');</script><?        
		?><script>window.location = 'adm_contactos.php';</script><?
	}
}
i
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cambia Contacto</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}

.numberTiny {
	width: 60px;
	text-align: center;
}
.numberMedium{
	width: 80px;
	text-align: center;
}
-->
</style>
<script>
	function validar(){
/*		var contra1=document.getElementById('contrasenia');
		var contra2=document.getElementById('contrasenia2');*/
		if(document.getElementById('nombre_contacto').value==""){
			alert('Nombre vac\u00edo');
			document.getElementById('nombre_contacto').focus();
			return false;
		}/* else if(contra1.value== "" || contra1.value!=contra2.value){
			alert('Contrase\u00f1a no coincide');
			contra1.value=""; contra2.value="";
			contra1.focus();
			return false;
		}*/ else if(document.getElementById('email_contacto').value==""){
			alert('Correo no vac\u00edo');
			document.getElementById('email_contacto').focus();
			return false;
		} else if(verifyEmail(document.getElementById('email_contacto').value)){
			alert("Porfavor ingresa una direccion de email valida.");
			document.getElementById('email_contacto').focus();
			return false;
		}else if(document.getElementById('departamento_empresa').value==""){
			alert("Porfavor ingresa departamento.");
			document.getElementById('departamento_empresa').focus();
			return false;
		}
		return true;
	}
	
	function verifyEmail(value){
		var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		if (value.search(emailRegEx) != -1)
			return false;
		else 
			return true;
	}
</script>
</head>

<body>
<div style="overflow: auto; width: 100%; height: 460px; ">
<form action="" method="post" name="form1" id="form1">
  <br />
  <table width="400" border="0" align="center" cellpadding="0">
    <tr>
      <td>
	  <table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <td colspan="2" valign="top" class="texto_info" scope="row"  background="images/bkg_1.jpg"><div align="center" class="style4 style6"><span class="titulo_tabla">Contacto </span></div></td>
          </tr>
        <tr>
          <th width="29%" valign="top" class="texto_info_negro" scope="row"><div align="right">*Nombre  </div></th>
          <td width="71%" class="style5"><input name="nombre_contacto" type="text" class="texto_info_negro" id="nombre_contacto" value="<?php echo $contacto->nombre_contacto; ?>" size="45" maxlength="100" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">*Email</div></th>
          <td class="style5"><input name="email_contacto" type="text" class="texto_info_negro" id="email_contacto" value="<?php echo $contacto->email_contacto; ?>" size="45" maxlength="100" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Tel&eacute;fono</div></th>
          <td class="style5"><input name="telefono_contacto" type="text" class="texto_info_negro" id="telefono_contacto" value="<?php echo $contacto->telefono_contacto; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">*Departamento</div></th>
          <td class="style5"><!--<input name="departamento_empresa" type="text" class="texto_info_negro" id="departamento_empresa" value="<?php //echo $contacto->departamento_empresa; ?>" size="45" maxlength="100" />--> <select name="departamento_empresa" id="departamento_empresa" style="width:200px">
            <option value="">--Departamento--</option>
            <?php
	    $consulta87  = "SELECT * FROM departamentos where industria>0 ORDER BY nombre ASC";
        $resultado87 = mysql_query($consulta87) or print("La consulta $consulta87" . mysql_error());
        if(@mysql_num_rows($resultado87)>=1){
            while($array=mysql_fetch_assoc($resultado87)) {
                ?>
            <option <? if($contacto->departamento_empresa==$array['nombre']) echo 'selected';?> value="<? echo $array['nombre'];?>"><? echo $array['nombre'];?></option>
            <?
            }
        }
     
		  ?>
          </select></td>
          </tr>
        <!--<tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Contrase&ntilde;a</div></th>
          <td class="style5"><input name="contrasenia" type="password" class="texto_info_negro" id="contrasenia" value="" size="45" maxlength="100" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Repetir Contrase&ntilde;a</div></th>
          <td class="style5"><input name="contrasenia2" type="password" class="texto_info_negro" id="contrasenia2" value="" size="45" maxlength="100" /></td>
          </tr>-->
        <tr>
          <th align="right" valign="top" class="texto_info_negro" scope="row"><label id="es_comprador">
            
            Es comprador</label></th>
          <th align="left" valign="top" class="texto_info_negro" scope="row"><input name="es_comprador" type="checkbox" id="es_comprador" value="1"
          <? if($contacto->es_comprador) echo "checked";?> /></th>
          </tr>
        <tr>
          <th colspan="2" align="right" valign="top" class="texto_info_negro" scope="row"><div align="center">* Datos obligatorios </div></th>
          </tr>   
        </table>
	</td>
    </tr>
		
  </table>
   <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="crear" type="submit" class="texto_info" 
               onClick="return validar()" value="Guardar"/>
      </div></td>
    </tr>
  </table>
</form>
<? if($contacto != "") {?>
<form action="" method="get"><div align="center"><input name="borrar_contacto" type="submit" value="Borrar Contacto" />
<input type="hidden" value="<? echo $contacto->id;?>" name="id" />
<input type="hidden" value="true" name="borrar" /></div>
</form>
<? }?>
</div>
</body>
</html>
