<?
include 'Cliente.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";

if($_GET['id']!=""){
    $cliente = new Cliente();
    $cliente->get($_GET['id']);
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
//    $nombre_contacto = $_POST['nombre_contacto']; 
//    $email_contacto = $_POST['email_contacto'];
//    $telefono_contacto = $_POST['telefono_contacto'];
    $id_estado = $_POST['id_estado'];
    $ciudad = $_POST['ciudad'];
    $id_industria = $_POST['id_industria'];
//    $contrasenia = $_POST['contrasenia'];
    $rfc  = $_POST['rfc'];
    $condiciones_pago  = $_POST['condiciones_pago'];
    $moneda_usa  = $_POST['moneda_usa'];
    $alias  = $_POST['alias'];
    $codigo  = $_POST['codigo'];
	
	$tipo_cliente  = $_POST['tipo_cliente'];
	$objetivo  = $_POST['objetivo'];
	
	
    $cliente = new Cliente();
    
    if($cliente->create($id_cartera,$nombre_empresa ,$direccion_empresa ,
            $telefono_empresa, 
//            $nombre_contacto, $email_contacto, $telefono_contacto,
            $id_estado, $ciudad, $id_industria, 
//            $contrasenia,
            $rfc, $condiciones_pago, $moneda_usa, $alias, $codigo, $tipo_cliente, $objetivo)){
        unset($_SESSION['cambiaCliente']);
        ?><script>parent.location.reload();</script><?
    }
}
if($_POST["guardar"]=="Guardar"){
    $id_cartera = $_POST['id_cartera'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $direccion_empresa = $_POST['direccion_empresa'];
    $telefono_empresa = $_POST['telefono_empresa']; 
//    $nombre_contacto = $_POST['nombre_contacto']; 
//    $email_contacto = $_POST['email_contacto'];
//    $telefono_contacto = $_POST['telefono_contacto'];
    $id_estado = $_POST['id_estado'];
    $ciudad = $_POST['ciudad'];
    $id_industria = $_POST['id_industria'];
    $rfc  = $_POST['rfc'];
    $condiciones_pago  = $_POST['condiciones_pago'];
    $moneda_usa  = $_POST['moneda_usa'];
    $alias  = $_POST['alias'];
    $codigo  = $_POST['codigo'];
	
	$tipo_cliente  = $_POST['tipo_cliente'];
	$objetivo  = $_POST['objetivo'];
	
    $cliente = new Cliente();
    
    if($cliente->update($_SESSION['cambiaCliente']->id, $id_cartera ,$nombre_empresa ,$direccion_empresa ,
            $telefono_empresa, 
//            $nombre_contacto, $email_contacto, $telefono_contacto,
            $id_estado, $ciudad, $id_industria, 
//            $contrasenia, 
            $rfc, $condiciones_pago, $moneda_usa, $alias, $codigo, $tipo_cliente, $objetivo)){
        
        unset($_SESSION['cambiaCliente']);
        ?><script>parent.location.reload();</script><?
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cambia Cliente</title>
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
	function agregarCartera(){
		window.location = 'cambia_cartera_cliente.php?atras=cambia_cliente.php';
	}
	
	function validar(){
		//var contra1=document.getElementById('contrasenia');
		//var contra2=document.getElementById('contrasenia2');
		if(document.getElementById('nombre_empresa').value==""){
			alert('Nombre vac\u00edo');
			document.getElementById('nombre_empresa').focus();
			return false;
		} else{ if(document.getElementById('id_cartera').value=="0"){
			alert('La cartera esta vacia');
			document.getElementById('id_cartera').focus();
			return false;
		} }
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
<div style="overflow: inherit; width: 100%; height: 460px; ">
<form action="" method="post" name="form1" id="form1">
  <br />
  <table width="400" border="0" align="center" cellpadding="0">
    <tr>
      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <td colspan="2" valign="top" class="texto_info" scope="row"  background="images/bkg_1.jpg"><div align="center" class="style4 style6"><span class="titulo_tabla">Empresa <input name="Button" type="button" class="texto_info" onClick="window.location='adm_contactos.php'" style="float:right" value="Contactos"/></span>
            
          </div></td>
          </tr>
        <tr>
          <th width="29%" valign="top" class="texto_info_negro" scope="row"><div align="right">Nombre </div></th>
          <td width="71%" class="style5"><input name="nombre_empresa" type="text" class="texto_info_negro" id="nombre_empresa" value="<?php echo $cliente->nombre_empresa; ?>" size="45" maxlength="100" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Alias </div></th>
          <td class="style5"><input name="alias" type="text" class="texto_info_negro" id="alias" value="<?php echo $cliente->alias; ?>" size="45" maxlength="100" /></td>
          </tr>
		  <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Codigo Microsip </div></th>
          <td class="style5"><input name="codigo" type="text" class="texto_info_negro" id="codigo" value="<?php echo $cliente->codigo; ?>" size="45" maxlength="50" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Cartera</div></th>
          <td class="style5"><select name="id_cartera" id="id_cartera" style="width:200px">
            <?
            echo $cliente->id_rol;
            ?>
            <option value="0">-- cartera --</option>
            <?php
	    $consulta  = "SELECT * FROM CarteraClientes";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
            <option <? if($cliente->id_cartera==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
            <?
            }
        }
     
		  ?>
          </select>
            <input name="btnAgregaRol" type="button" class="texto_info_negro"  id="btnAgregaRol" onClick="agregarCartera();" value="nuevo" /></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Direcci&oacute;n</div></th>
          <td class="style5"><input name="direccion_empresa" type="text" class="texto_info_negro" id="direccion_empresa" value="<?php echo $cliente->direccion_empresa; ?>" size="45" maxlength="100" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Tel&eacute;fonos</div></th>
          <td class="style5"><input name="telefono_empresa" type="text" class="texto_info_negro" id="telefono_empresa" value="<?php echo $cliente->telefono_empresa; ?>" size="45" maxlength="100" /></td>
          </tr>
 
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">RFC </div></th>
          <td class="style5"><input name="rfc" type="text" class="texto_info_negro" id="rfc" value="<?php echo $cliente->rfc; ?>" size="45" maxlength="100" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Condiciones de Pago </div></th>
          <td class="style5"><textarea style="width:100%" name="condiciones_pago" id="condiciones_pago" rows="5"><?php echo $cliente->condiciones_pago; ?></textarea></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Moneda</div></th>
          <td class="texto_info_negro"><label>
            <input name="moneda_usa" type="radio" id="tipo_moneda_mx" value="0" <? if($cliente->moneda_usa == 0) echo "checked";?> onChange="changeCurrency(this.value)" />
            MX</label>
            <label>
              <input type="radio" name="moneda_usa" value="1" id="tipo_moneda_usa" <? if($cliente->moneda_usa == 1) echo "checked";?> onChange="changeCurrency(this.value)" />
              USA</label></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Estado</div></th>
          <td class="style5"><select name="id_estado" class="texto_info_negro_forma" id="id_estado" style="width:200px">
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
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Ciudad</div></th>
          <td class="style5"><input name="ciudad" type="text" class="texto_info_negro" id="ciudad" value="<?php echo $cliente->ciudad; ?>" size="45" maxlength="100" /></td>
          </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Industria</div></th>
          <td class="style5"><select name="id_industria" class="texto_info_negro_forma" id="id_industria" style="width:200px">
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
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Tipo Cliente</div></th>
          <td class="style5"><select name="tipo_cliente" class="texto_info_negro_forma" id="tipo_cliente" style="width:200px">
            <option value="0">-- Tipo Cliente --</option>
            <option <? if($cliente->tipo_cliente=="A") echo 'selected';?> value="A">A</option>
			<option <? if($cliente->tipo_cliente=="AA") echo 'selected';?> value="AA">AA</option>
			<option <? if($cliente->tipo_cliente=="AAA") echo 'selected';?> value="AAA">AAA</option>
            </select></td>
          </tr>
		  <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Objetivo</div></th>
          <td class="style5"><input name="objetivo" type="text" class="texto_info_negro" id="objetivo" value="<?php echo $cliente->objetivo; ?>" size="10" maxlength="100" />Por mes</td>
          </tr>
        </table>
        <div align="center"></div></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="<? if($cliente!="") echo "guardar"; else echo "crear";?>" type="submit" class="texto_info" onClick="return validar()" value="Guardar"/>
      </div></td>
    </tr>
  </table>
</form>
</div>
</body>
</html>
