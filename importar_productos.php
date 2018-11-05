<?
include_once 'Usuario.php';
include_once 'Producto.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);

$idProveedorDeVariosFactores = 7;//SNAP ON
$idProveedorDeRecargo = 20;//CAMESA
function compareArray($a, $b) {if ($a == $b) return 0; return ($a < $b) ? -1 : 1; }

function convertToDouble($valorAChecar){
    $x = $valorAChecar;
    $x = explode('$', $x);
    if(count($x)>1){
        $x = $x[1];
    } else
        $x = $valorAChecar;
    
    $aux = $x;
    $x = explode(',', $x);
    if(count($x)>1)
        $x = $x[0].$x[1];
    else 
        $x = $aux;

    $x = doubleval($x);
    return $x;
}

$extraMensaje = "";
$errores = 0;
function errorDeUsuario($errno,$error,$query,$codigo){
    $consulta  = "INSERT INTO errorDeUsuario(id, mysql_errno, mysql_error, query) 
        VALUES (DEFAULT,$errno,'".mysql_real_escape_string($error)."', '".mysql_real_escape_string($query)."')";
    $res = mysql_query($consulta);
    $mensaje = "Los siguientes productos no fueron importados: ";
    global $extraMensaje,$errores;
    if($extraMensaje=="")
        $extraMensaje = $mensaje;
    $extraMensaje.= "$codigo, ";
    
    $errores++;
    if($errores>20)
        print("<script> alert('Operacion no exitosa. Revisa el orden de las columnas de tu archivo.'); 
            window.location = 'importar_productos.php'; 
            </script>");
}

$extensionArchivo = end(explode(".", $_FILES['productos_importacion']["name"]));

if( $_POST["guardar"]=="Importar"){
    if(($extensionArchivo =="csv" || $extensionArchivo=="CSV" )){
        $es_usa = $_POST['tipo_moneda'];
        $id_catalogo = $_POST['id_catalogo'];
        $id_proveedor = $_POST['id_proveedor'];
        /*
         * seleccionando factores de importacion
         */
        $consulta  = "SELECT factor, factor2 FROM CatalogoProductos
                    WHERE id_proveedor= $id_proveedor AND id = $id_catalogo";
		echo"$consulta";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        $factores = mysql_fetch_assoc($resultado);
        $factor = $factores['factor'];
//        $factor2 = $factores['factor2'];
        /*
         * Definiendo el origen del producto
         */
        if($idProveedorDeVariosFactores == $id_proveedor){
            $origen = "SIN_ORIGEN";
            $factor = 0;
            /*
            * Si el proveedor es $idProveedorDeVariosFactores
            * el $factor = 0
            * por lo tanto $precio sera 0
            */
        }
        /*
         * obteniendo el orden de importacion
         */
        $consulta  = "SELECT Tipo_importacion.*
              FROM Tipo_importacion
              INNER JOIN CatalogoProductos ON CatalogoProductos.id_tipo_importacion = Tipo_importacion.id_tipo_importacion
              WHERE CatalogoProductos.id = ".$_REQUEST['id_catalogo']."
                  AND CatalogoProductos.id_proveedor = ".$_REQUEST['id_proveedor'];

          $resultado2 = mysql_query($consulta) or print("La consulta lista: " . mysql_error());

          if(@mysql_num_rows($resultado2)>=1){
                $acomodo=mysql_fetch_array($resultado2,MYSQL_ASSOC);
                unset($acomodo['id_tipo_importacion']); //no son parte del producto, por lo tanto lo eliminamos
                unset($acomodo['nombre_tipo_importacion']); //no son parte del producto, por lo tanto lo eliminamos
                @uasort($acomodo,'compareArray'); // acomodamos de menor a mayor por el valor del atributo
                $array=@array_flip($acomodo); //Exchanges all keys with their associated values in an array. Los que no tienen valor son eliminados 

                /*
                 * subiendo y abriendo el archivo para poder tratarlo
                 */
                $producto = new Producto();
                $file = $producto->uploadFile('productos_a_importar','archivos','productos_importacion');
                $file = fopen($file,"r");

				
				/*
                     * Armando el codigo interno del producto
                     */
                    /* Seleccionar prefijo */
                    
                   
                    $query = "SELECT prefijo FROM Proveedores WHERE id = $id_proveedor";
                    $resultPrefijo = mysql_query($query) or print("<br><br>ERROR en la consulta seleccionando prefijo") and 
                            errorDeUsuario(mysql_errno(),  mysql_error(),$query,$codigoBuscar);
                    if($resultPrefijo){
                        $prefijo = mysql_fetch_assoc ($resultPrefijo);
                        $prefijo = $prefijo['prefijo'];
                        $codigoInterno = $prefijo.$codigoBuscar;
						$hayP=1;
                    }else
						$hayP=0;
				
                $query = "";
                $header = NULL;
                while(! feof($file)){
                    $query = "";    
                    if(!$header){
                        $header = fgetcsv ($file);
                    } else {
                        $valores = fgetcsv($file);
                    }
                    
                     $codigoBuscar = mysql_real_escape_string($valores[$acomodo['codigo']]);
					 if($hayP==1)
						 $codigoInterno = $prefijo.$codigoBuscar;
                    /*
                     * Buscando el Codigo en la tabla Productos
                     */
                    if($codigoBuscar!=""){
                        //echo "<br>buscando codigo $codigoBuscar<br>";
                        $consulta  = "SELECT codigo FROM Productos WHERE codigo = '$codigoBuscar' AND id_proveedor = $id_proveedor";
                        $resultado = mysql_query($consulta) or print("<br><br>ERROR en la consulta buscando el codigo") and 
                            errorDeUsuario(mysql_errno(),  mysql_error(),$query,$codigoBuscar);

                        if(@mysql_num_rows($resultado)>0){
                            /*
                             * Update en caso de encontrar el codigo del producto
                             */
                            //echo "<br>Encontrado codigo $codigoBuscar<br>";
                            $query = "UPDATE Productos SET ";
                            $proveedor = ", id_proveedor = $id_proveedor ";
                            $catalogo = ", id_catalogo_productos = $id_catalogo";
                            
                            for($i=0 ; count($array)>$i ; $i++){
                                /*
                                 * si trae costo lo multiplica   
                                 */
                                if($array[$i]=='costo'){
                                    /*
                                    * Si el proveedor es $idProveedorDeVariosFactores
                                    * el $factor = 0
                                    * por lo tanto $precio sera 0
                                    */
                                    if(is_numeric($valores[$i])){
                                        $costo = doubleval($valores[$i]);
                                        $precio = ", precio = ".round($costo * $factor , 2 );
                                    } else {
                                        $costo = convertToDouble($valores[$i]);
                                        $valores[$i] = $costo;
                                        $precio = ", precio = ". round( $costo * $factor , 2 );
                                    }
                                }
								if($array[$i]=='nombre' || $array[$i]=='descripcion'){
								}
								else
                                $query.= $array[$i]." = '".mysql_real_escape_string($valores[$i])."'";
                                
				
                                if($i!=(count($array)-2))
								{	
                                   if($array[$i]=='nombre' || $array[$i]=='descripcion'){
									}
									else
									$query.=",";
								}
                                else{
									if($id_proveedor=="20")
										$recargo=",recargo=$valores[7] ";
									else
										$recargo="";
                                    $query .= "$proveedor $catalogo $precio, 
                                        tipo_moneda_usa = $es_usa, 
                                        origen = '$origen',
                                        codigo_interno = '$codigoInterno' $recargo 
                                    WHERE codigo = '$codigoBuscar' AND id_proveedor = $id_proveedor";
                                }

                            }

                        } else {
                            /*
                             * Insertando el producto en caso de no encontrar el codigo
                             */
                            //echo "<br>Codigo $codigoBuscar no encontrado. Insertarlo<br>";
                            $noInsert = true;

                            for($i=0 ; count($array)>$i ; $i++){
                                if($array[$i]!=""){
                                    if($array[$i]=='costo' ){
                                        if(is_numeric($valores[$i])){
                                            $costo = doubleval($valores[$i]);
                                            $precio = $costo * $factor;
                                        } else {
                                            $costo = convertToDouble($valores[$i]);
                                            $valores[$i] = $costo;
                                            $precio = $costo * $factor;
                                        }
                                        $precioAttr = ", precio";
                                        $precioValue = ", $precio";
                                        
                                    }
									
                                    $attr.= $array[$i];
                                    $values .= "'".mysql_real_escape_string($valores[$i])."'";
                                    if($i!=(count($array)-1)){
                                        $attr.=", ";
                                        $values .= ", ";
                                        if($values=="" && $noInsert)
                                            $noInsert=true;
                                        else 
                                            $noInsert = false;
                                    }
                                }
								
                            }
							if($id_proveedor=="20")
							{
								$attr.= ",recargo";
								$values .= ",'".mysql_real_escape_string($valores[7])."'";
							}
                            $attr.=", id_proveedor, id_catalogo_productos, tipo_moneda_usa, origen, codigo_interno";
                            $values.=", $id_proveedor, $id_catalogo, $es_usa, '$origen','$codigoInterno'";

                            if(!$noInsert){
                                $query = "INSERT INTO Productos($attr $precioAttr)
                                    VALUES($values $precioValue)";
                            }
                            $attr="";
                            $values="";
                            $precio = "";
                            $precioValue = "";
                            $precioAttr="";  

                        }
                        echo "<br>QUERY test: $factor $query $codigoBuscar<BR>";
                        $resultado = mysql_query($query) or die("Error en operacion registro: ". mysql_error() );
//                            errorDeUsuario(mysql_errno(),  mysql_error(),$query,$codigoBuscar);
                       // print("<h1>".mysql_error()."<h1>");
                    }
                }
                fclose($file);
          }
        if($extraMensaje=="")
            $extraMensaje=="Productos importados exitosamente";?>
            <script>
                alert('Productos importados exitosamente. <? echo $extraMensaje?>');
                parent.jQuery.colorbox.close();</script><?
    }else {?>
        <script>
        alert('Adjunta un archivo en formato csv');
        parent.jQuery.colorbox.close();
        </script><?
    }
}
?>
<html>
<head>
<link href="images/textos.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
function agregarRol(){
		window.location = 'cambia_tipo_usuario.php?atras=cambia_usuario.php';
	}
	function setContra(obj){
		obj.type='text';
		obj.value='';
		obj.name='contrasenia';
	}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
var x = selObj.options[selObj.selectedIndex].value;
//x = x.split( '|' , x );// explode('|',x);
//var prefijoValue = x[1];
//x = x[0];
//document.getElementById('prefijo').value = prefijoValue;
window.location = targ+"?id_proveedor="+selObj.options[selObj.selectedIndex].value
      +"&tipo="+selObj.options[selObj.selectedIndex].innerHTML;
}
function MM_jumpMenuOrden(selObj){
	var val = selObj.options[selObj.selectedIndex].value;
//	document.getElementById('orden').innerHTML = viewOrden(parseInt(val));
	document.getElementById('factor').innerHTML = getFactor(parseInt(val));
}
	
function viewOrden(id){
	var orden = Array();
	<?
	
	$consulta  = "SELECT Tipo_importacion.*, CatalogoProductos.id,
		CatalogoProductos.factor, CatalogoProductos.factor2
            FROM Tipo_importacion
            INNER JOIN CatalogoProductos
            ON CatalogoProductos.id_tipo_importacion = Tipo_importacion.id_tipo_importacion";
	$resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
	if(@mysql_num_rows($resultado)>=1){
            $j=0;
            while($array=mysql_fetch_assoc($resultado)) {
                @uasort($array,'compareArray');
                $i=0;
                $orden.= ucwords($array['nombre_tipo_importacion']);
                foreach ($array as $key => $value) {
                    if($value!="")
                        if($key!='id_tipo_importacion'
                                && $key!='nombre_tipo_importacion' 
                                && $key!='factor' 
                                && $key!='factor2')
                            $orden .= " $value)$key ";
                        else if($key=='factor' || $key=='factor2')
                            $factor .= ucfirst($key)." = $value ";
                            
                    $i++;
                }
                echo "orden[".$array['id']."]='$orden <br> $factor';";
                $factor="";
                $orden="";
                $j++;
            }
	}
	?>
	
        return orden[id];
}

</script>

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.numberTiny {	width: 60px;
	text-align: center;
}
-->
</style>

</head>

<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="100%" border="0" align="center" cellpadding="0">
    <tr>
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">Importar Productos</div></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">
        <tr>
          <th width="87" align="right" valign="top" class="texto_info_negro" scope="row">Proveedor</th>
          <td width="476" class="style5"><select  name="id_proveedor" class="texto_info_negro_forma" id="id_proveedor" style="width:200px" onChange="MM_jumpMenu('importar_productos.php',this,0)">
            <option value="0">-- proveedor --</option>
                         <?//BkUp
	    $consulta  = "SELECT * FROM Proveedores";
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
            <option <? if($_GET['id_proveedor']==$array['id']) echo 'selected';?> value="<? echo $array['id']?>"><? echo $array['nombre'];?></option>
            <?
            }
        }
     
		  ?>
            </select></td>
        </tr>
        <? if($_GET['id_proveedor']){?>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Cat&aacute;logo de productos</div></th>
          <td class="style5"><span style="float:left">
            <select  name="id_catalogo" class="texto_info_negro_forma" id="id_catalogo" style="width:200px" onChange="MM_jumpMenuOrden(this);">
              <option value="0">-- catalogo --</option>
              <?
	    $consulta  = "SELECT nombre,id 
		FROM CatalogoProductos
		WHERE id_proveedor = ".$_GET['id_proveedor'];
        $resultado = mysql_query($consulta) or print("La consulta lista roles: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            while($array=mysql_fetch_assoc($resultado)) {
                ?>
              <option value="<? echo $array['id'];?>"><? echo $array['nombre'];?></option>
              <?
            }
        }
     
		  ?>
            </select>
          </span></td>
        </tr>
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Tipo de moneda del costo</div></th>
          <td class="texto_info_negro"><label>
            <input name="tipo_moneda" type="radio" id="tipo_moneda_mx" value="0" <? if($_SESSION['cotizacion']->tipo_moneda == 0) echo "checked";?> >
            MX</label>
            <label>
              <input type="radio" name="tipo_moneda" value="1" id="tipo_moneda_usa" <? if($_SESSION['cotizacion']->tipo_moneda == 1) echo "checked";?> >
              USA            </label></td>
        </tr>
        <? if($_GET['id_proveedor']!=""){?>
        
        <tr>
          <th valign="top" class="texto_info_negro" scope="row"><div align="right">Archivo...</div>            <!--<div align="right">Extra&iacute;do de microsip 
            <input name="extraido_microsip" type="checkbox" id="extraido_microsip" value="1">
          </div>--></th>
          <td class="style5"><input name="productos_importacion" type="file" class="texto_info_negro_forma" id="productos_importacion">
            <br>
            <em><span class="texto_chico_gris">(Recuerda solo .csv)</span></em></td>
        </tr>
        <? }?>
      </table></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="guardar" type="submit" class="texto_info" value="Importar"  onClick="return validar();"/>
      </div></td>
    </tr>
    <? } ?>
  </table>
</form>
</body>
</html>
