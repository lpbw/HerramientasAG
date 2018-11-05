<?
include 'Cliente.php';
session_start();
//include "checar_sesion_admin.php";
include "coneccion.php";

if($_GET['id']!=""){
    $cliente = new Cliente();
    $cliente->get($_GET['id']);
//  print_r($cliente);
    $_SESSION['cambiaCliente']=$cliente;
    if($_GET['borrar']=='true'){
        $cliente->delete();
        unset($_SESSION['cambiaCliente']);
        ?><script>parent.location.reload();</script><?
    }
} else if( isset($_SESSION['cambiaCliente']) && $_GET['nuevo']!='true' ){
    $cliente = $_SESSION['cambiaCliente'];
} else {
    unset($_SESSION['cambiaCliente']);
    $cliente="";
}

if($_POST['crear']!=""){
    $id_cartera = $_POST['id_cartera'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $direccion_empresa = $_POST['direccion_empresa'];
    $telefono_empresa = $_POST['telefono_empresa']; 
    $nombre_contacto = $_POST['nombre_contacto']; 
    $email_contacto = $_POST['email_contacto'];
    $telefono_contacto = $_POST['telefono_contacto'];
    $id_estado = $_POST['id_estado'];
    $ciudad = $_POST['ciudad'];
    $id_industria = $_POST['id_industria'];
    $password = $_POST['contrasenia'];
	
    $cliente = new Cliente();
    
    if($cliente->create($id_cartera,$nombre_empresa ,$direccion_empresa ,
            $telefono_empresa, $nombre_contacto, $email_contacto, $telefono_contacto,
            $id_estado, $ciudad, $id_industria, $password)){
        
	    $cliente->get($_GET['id']);
        ?><script>window.location="login.php?login=<? echo $email_contacto;?>&pass=<? echo $password
        ?>&atras=ver_carrito.php";</script><?
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cambia Cliente</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
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
<script>
function agregarCartera(){
	window.location = 'cambia_cartera_cliente.php?atras=cambia_cliente.php';
}
function validar(){
	if( document.getElementById('contrasenia').value !=
		document.getElementById('contrasenia2').value){
		alert('contrasenia no coincide');
		document.getElementById('contrasenia').value="";
		document.getElementById('contrasenia2').value="";
		document.getElementById('contrasenia').focus();
	}
}
</script>
</head>

<body>
<form action="" method="post" name="form1" id="form1">
  <br />
  <table width="400" border="0" align="center" cellpadding="0">
    <tr>
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="style4 style6"><span class="style7 style6">Cliente </span></div>                    <div align="center"></div></td>
    </tr>
    <tr>
      <td><table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <th width="29%" valign="top" class="texto_info" scope="row"><div align="right">Nombre  </div></th>
          <td width="71%" class="style5"><input name="nombre_contacto" type="text" class="texto_verde" id="nombre_contacto" value="<?php echo $cliente->nombre_contacto; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Email</div></th>
          <td class="style5"><input name="email_contacto" type="text" class="texto_verde" id="email_contacto" value="<?php echo $cliente->email_contacto; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Contrasenia</div></th>
          <td class="style5"><input name="contrasenia" type="password" class="texto_verde" id="contrasenia" value="<?php echo $cliente->email_contacto; ?>" size="45" maxlength="16" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Repite Contrasenia</div></th>
          <td class="style5"><input name="contrasenia2" type="password" class="texto_verde" id="contrasenia2" value="<?php echo $cliente->email_contacto; ?>" size="45" maxlength="16" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Tel&eacute;fono</div></th>
          <td class="style5"><input name="telefono_contacto" type="text" class="texto_verde" id="telefono_contacto" value="<?php echo $cliente->telefono_contacto; ?>" size="45" maxlength="100" /></td>
        </tr>
          
          
          
        <tr>
          <td colspan="2" valign="top" bgcolor="#999999" class="texto_info" scope="row"><div align="center" class="style4 style6"><span class="style7 style6">Empresa </span></div></td>
          </tr>
        <tr>
          <th width="29%" valign="top" class="texto_info" scope="row"><div align="right">Nombre  </div></th>
          <td width="71%" class="style5"><input name="nombre_empresa" type="text" class="texto_verde" id="nombre_empresa" value="<?php echo $cliente->nombre_empresa; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Direcci&oacute;n</div></th>
          <td class="style5"><input name="direccion_empresa" type="text" class="texto_verde" id="direccion_empresa" value="<?php echo $cliente->direccion_empresa; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Tel&eacute;fono</div></th>
          <td class="style5"><input name="telefono_empresa" type="text" class="texto_verde" id="telefono_empresa" value="<?php echo $cliente->telefono_empresa; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Estado</div></th>
          <td class="style5"><select name="id_estado" id="id_estado" style="width:200px">
            <?
            echo $cliente->id_rol;
            ?>
            <option value="0">-- estado --</option>
            <?php
	    $consulta  = "SELECT * FROM Estados";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
            <option <? if($cliente->id_estado==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
            <?
            }
        }
     
		  ?>
          </select></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Ciudad</div></th>
          <td class="style5"><input name="ciudad" type="text" class="texto_verde" id="ciudad" value="<?php echo $cliente->ciudad; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info" scope="row"><div align="right">Industria</div></th>
          <td class="style5"><select name="id_industria" id="id_industria" style="width:200px">
            <?
            echo $cliente->id_rol;
            ?>
            <option value="0">-- industria --</option>
            <?php
	    $consulta  = "SELECT * FROM Industrias";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
            <option <? if($cliente->id_industria==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
            <?
            }
        }
     
		  ?>
          </select></td>
        </tr>        
		        
      </table>
          <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
      </table>
          <div align="center"></div></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input type="submit" name="<? if($cliente!="") echo "guardar"; else echo "crear";?>" onClick="validar()" value="Registrar" />
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>
