<?
include 'Usuario.php';
session_start();
include "checar_sesion_admin.php";
include "coneccion.php";
include "checar_acceso.php";
//checarAcceso($_SESSION['accesos']['supervisor']);

if($_GET['new'] == 'true'){
    unset($_SESSION['cambiaUsuario']);
}
if($_GET['id']!=""){
    $usuario = new Usuario();
    $usuario->getUser($_GET['id']);
    $_SESSION['cambiaUsuario'] = $usuario;
    if($_GET['borrar']=='true'){
        $usuario->deleteUser();
        unset($_SESSION['cambiaUsuario']);
        ?><script>parent.location.reload();</script><?
    }
    
} else if(isset($_SESSION['cambiaUsuario'])){
	$usuario = $_SESSION['cambiaUsuario'];
}

$guardar= $_POST["guardar"];
if($guardar=="Guardar"){
    $nombre= $_POST["nombre"];
    $email=$_POST["email"];
    $id_rol= $_POST["id_rol"];
    $contrasenia= $_POST["contrasenia"];
    $id_supervisor = $_POST['id_supervisor'];
    $carteras = $_POST['id_cartera'];
    
    $usuario = new Usuario();
    $usuario->getUser($_SESSION['cambiaUsuario']->id);
    if( $usuario->update($nombre,$email,$contrasenia,$id_rol,$_SESSION['cambiaUsuario']->id, $id_supervisor,$carteras) ){
        unset($_SESSION['cambiaUsuario']);
        ?><script>parent.location.reload();</script><?
    }
}

if($_POST['crear']!=""){
    $nombre= $_POST["nombre"];
    $email=$_POST["email"];
    $id_rol= $_POST["id_rol"];
    $contrasenia= $_POST["contrasenia"];
    $id_supervisor = $_POST['id_supervisor'];
    $carteras = array();//$_POST['id_cartera'];
    $usuario = new Usuario();
    if($usuario->createUser($nombre,$email,$contrasenia,$id_rol,$id_supervisor,$carteras)){
        $_SESSION['cambiaUsuario'] = $usuario;
        var_dump($_SESSION['cambiaUsuario']);
        $endPoint = explode('?',$_SERVER['REQUEST_URI']);
        $endPoint = $endPoint[count($endPoint)-2];
        ?><script>window.location = '<? echo $endPoint;?>'</script><?
    }
}
if($_GET['id']!=""){
    $usuario = new Usuario();
    $usuario->getUser($_GET['id']);
    $_SESSION['cambiaUsuario']=$usuario;
}
?>
<html>
<head>
<link href="images/texto.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>

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

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script>
function agregarRol(){
		window.location = 'cambia_tipo_usuario.php?atras=cambia_usuario.php';
	}
	function setContra(obj){
		obj.type='text';
		obj.value='';
		obj.name='contrasenia';
	}
function agregarCartera(selObj){ //v3.0
	var nameCartera = selObj.options[selObj.selectedIndex].text;
	var idCartera = selObj.options[selObj.selectedIndex].value;
	
	document.getElementById('divContainerCarteras').innerHTML+=constructDivCartera(idCartera,nameCartera);
        selObj.options[selObj.selectedIndex].remove();
}

function constructDivCartera(idd,nombre){
	return "<div class=\"divCartera\" id=\"divCartera" + idd + "\" style=\"width: 230px;\">" +
              	nombre + "<input type=\"hidden\" id=\"id_cartera" + 
                idd + "\" name=\"id_cartera[]\" value=\""+ 
                idd + "\"><a href=\"#\" style=\"float:right\" onClick=\"borrarCartera(" + 
                idd + ",'" + nombre + "');\"><img src=\"images/close.gif\" alt=\"borrar\" width=\"15\" height=\"13\" border=\"0\" /></a></div>";
}
function borrarCartera(idCarteraBorrar,nombre){
    var divToErase = document.getElementById('divCartera'+idCarteraBorrar);
    document.getElementById('divContainerCarteras').removeChild(divToErase);
    agregarOption(idCarteraBorrar,nombre);
}

function agregarOption(id,name){
    var option = document.createElement('option');
    option.value = id;
    option.text = name;
    document.getElementById("carteras").appendChild(option);
}

</script>
<script language="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>

</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<div align="center" style="margin:20px; overflow:auto">
  
  <table border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">USUARIO</div></td>
      </tr>
    <tr bordercolor="#CCCCCC">
      <th width="63" valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Nombre </div></th>
      <td width="281" class="style5"><input name="nombre" type="text" class="texto_info_negro_forma" id="nombre" value="<?php echo $usuario->nombre; ?>" size="45" maxlength="100" /></td>
    </tr>
    <tr bordercolor="#CCCCCC">
      <th valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Email</div></th>
      <td class="style5"><input name="email" type="text" class="texto_info_negro_forma" id="email" value="<?php echo $usuario->email; ?>" size="45" maxlength="100" /></td>
    </tr>
    <tr bordercolor="#CCCCCC">
      <th valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Tipo</div></th>
      <td class="style5"><select name="id_rol" class="texto_info_negro_forma" id="id_rol" style="width:200px" ><!-- onChange="checkSupervisor(this)" -->
        <?
            echo $usuario->id_rol;
            ?>
        <option value="0">-- tipo --</option>
        <?php
	    $consulta  = "SELECT * FROM Roles";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
        <option <? if($usuario->id_rol==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre']." ".($array['limite_descuento']*100)."%";?></option>
        <?
            }
        }
     
		  ?>
      </select>
        <input name="btnAgregaRol" type="button" class="texto_info_negro"  id="btnAgregaRol" onClick="agregarRol();" value="nuevo"></td>
    </tr>
    <tr bordercolor="#CCCCCC">
        <?php 
        if (isset($usuario->id)) { ?>
      <th colspan="2" align="center" valign="top" class="texto_info" scope="row"> 
    <table width="80%" border="0" cellpadding="1" cellspacing="0" bgcolor="#CCCCCC" id="tablaCartera">

        <tr>
          <th width="31%" bgcolor="#E3E3E3" scope="row">Agregar Cartera</th>
          <td width="69%" bgcolor="#E3E3E3"><select name="carteras" class="texto_info_negro_forma" id="carteras" style="width:200px" onChange="agregarCartera(this)">
            <option value="0">-- agregar cartera --</option>
            <?
        $consulta  = "SELECT * FROM CarteraClientes 
            WHERE id NOT IN(
                                SELECT id_cartera_clientes FROM CarteraClientes_Usuarios WHERE id_usuario = {$usuario->id}
                            )
            ORDER BY nombre";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
            <option value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
            <?
            }
        }
     
		  ?>
          </select></td>
        </tr>
        <tr>
          <th bgcolor="#E3E3E3" scope="row">&nbsp;</th>
          <td bgcolor="#E3E3E3"><div id="divContainerCarteras">
          
          
            <?
        if(isset($usuario)){
            $consulta  = "SELECT CarteraClientes.* FROM CarteraClientes
		INNER JOIN CarteraClientes_Usuarios AS CCU 
		ON CCU.id_cartera_clientes = CarteraClientes.id
		INNER JOIN Usuarios ON Usuarios.id = CCU.id_usuario 
                WHERE CCU.id_usuario = ".$usuario->id."
		ORDER BY nombre";
            
            $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error()."<br>$consulta");
            if(@mysql_num_rows($resultado)>=1){
                while($array=mysql_fetch_assoc($resultado)) {
                    $idCartera = $array['id'];
                    $carteraNombre = $array['nombre'];
                ?>
            <div class="divCartera" id="divCartera<? echo $idCartera?>" style="width:100%; height:20px"><div style="float:left"> 
                    <a href="#" class="texto_info" onClick="borrarCartera(<? echo $idCartera?>,'<? echo $carteraNombre?>');"
                       onMouseOver="MM_swapImage('Image86','','images/cerrar_r.jpg',1)" onMouseOut="MM_swapImgRestore()">
                        <img src="images/cerrar.jpg" alt="" name="Image86" width="17" height="16" border="0" id="Image86" /></a></div>
              <div style="float:left; padding-left:10px"> <? echo $carteraNombre?>
                <input type="hidden" id="id_cartera<? echo $idCartera?>" name="id_cartera[]" value="<? echo $idCartera?>">
              </div>
              
            </div>
            <? 
                }
            }
        }?>        
          </div></td>
        </tr>
      </table>
  </th>
        <?php } ?>
    </tr>
    <tr bordercolor="#CCCCCC">
      <th valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Contrase&ntilde;a</div></th>
      <td class="style5"><input name="c" type="password" class="texto_info_negro_forma" id="contrasenia" onClick="setContra(this);" value="<?php echo $usuario->contrasenia; ?>" size="45" maxlength="100" /></td>
    </tr>
    <tr bordercolor="#CCCCCC">
      <th valign="top" bgcolor="#E3E3E3" class="texto_info_negro" scope="row"><div align="right">Supervisor</div></th>
      <td class="style5"><select name="id_supervisor" class="texto_info_negro_forma" id="id_supervisor" style="width:200px">
        <option value="0">-- Supervisor --</option>
        <?php
	    $consulta  = "SELECT Usuarios.id, Usuarios.nombre FROM Usuarios
		INNER JOIN Roles ON Roles.id = Usuarios.id_rol
		WHERE Usuarios.id_rol in(2, 7)";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
        <option <? if($usuario->id_supervisor==$array['id']) echo 'selected';?> value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
        <?
            }
        }
     
		  ?>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="center" valign="top" bordercolor="#CCCCCC" class="texto_info_negro" scope="row"><input name="<? if($usuario!="") echo "guardar"; else echo "crear";?>" type="submit" class="texto_info_negro" value="Guardar" /></td>
    </tr>
    
    </table>
</div>
</form>
</body>
</html>
