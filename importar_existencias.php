<?
$_TEST = FALSE;

include_once 'Usuario.php';
include_once 'Producto.php';
session_start();
include_once "checar_sesion_admin.php";
include_once "coneccion.php";
include_once "checar_acceso.php";
checarAcceso($_SESSION['accesos']['supervisor']);
$extraMensaje = "";
$errores = 0;

$extensionArchivo = end(explode(".", $_FILES['archivo']["name"]));

if( $_POST["guardar"]=="Importar"){
    if(($extensionArchivo =="csv" || $extensionArchivo=="CSV" )){
        /*
        * subiendo y abriendo el archivo para poder tratarlo
        */
        $producto = new Producto();
        $file = $producto->uploadFile('actualizar_existencias','archivos','archivo');
        $file = fopen($file,"r");
        
        /*
         * BORRAR TODAS LAS EXISTENCIAS
         */
        $query = "UPDATE Productos SET stock = 0 WHERE stock != ''";
        if(!$_TEST) mysql_query($query);

        $valores = "";
        $productosNoAgregados = array();
        $codigoConMasDeUnProducto = array();
        $productosAgregados = array();
        while(! feof($file)){
            $query = "";
            $valores = fgetcsv($file);

            if($valores[0]!=""){
                $consulta  = "SELECT id FROM Productos WHERE codigo_interno = '".mysql_escape_string($valores[0])."'";
				//echo"$consulta";
                $resultado = mysql_query($consulta) or die(mysql_error());
                if(mysql_num_rows($resultado) == 1){
                    $idP = mysql_fetch_assoc($resultado);
                    $producto = new Producto();
                    $producto->get($idP['id']);
                   if(!$_TEST ) $producto->updateOneAttr('stock', $valores[6]); 
				   				$producto->updateOneAttr('stock_proveedor', $valores[10]);
                    array_push($productosAgregados, $producto);
                
                } else if(mysql_num_rows($resultado)>1){
                    array_push($codigoConMasDeUnProducto, $producto);//array($consulta,$valores)

                } else {
                    array_push($productosNoAgregados, $valores);
                }
                $producto=null;
                $valores=null;
            }
        }
        fclose($file);?> 
            <script>
                alert('Existencias actualizadas exitosamente.');</script><?
    }else {?>
        <script>
        alert('Adjunta un archivo en formato csv');
        </script><?
    }
    
   $_SESSION['codigoConMasDeUnProducto'] = $codigoConMasDeUnProducto;
   $_SESSION['productosAgregados'] = $productosAgregados;
   $_SESSION['productosNoAgregados'] = $productosNoAgregados;
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

<style type="text/css">
<!--
.style6 {color: #FFFFFF}
.numberTiny {	width: 60px;
	text-align: center;
}
-->
</style>
<script type="text/javascript">
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

<body >
<?

        if(count($productosNoAgregados)>0){
			?>
      <h1>Productos no agregados</h1>
            <div style="height: 470px;overflow-y: scroll;">
    <table width="503"   cellpadding="0" cellspacing="0" >
      <thead>
        <tr>
          <th width="63" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <div align="center">
          Codigo Interno</div></th>
          <th width="438" align="center" valign="middle" bgcolor="#DD1A22" class="texto_info_blanco"> <div align="center"> 
          Nombre</div></th>

        </tr>
      </thead>
      <tbody>
          <?	  
	$count=1;
	$color = 'white';
	$tm="";
	   
            foreach ($productosNoAgregados AS $producto){
                if($producto[1]!=""){?>
                    <tr bgcolor="<? echo"$color";?>" >
                      <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                        <div align="left">
                <? echo $producto[0]; ?>
                        </div></div>
                      </td>
                      <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                        <div align="left">
                <? echo $producto[1]; ?>
                        </div></div>
                      </td>
                <?
                
                }
				
                $count=$count+1;
                if($color == 'white')
                $color = '#E3E3E3';
                else $color = 'white';
	}
	 foreach ($codigoConMasDeUnProducto AS $producto){
                if($producto[1]!=""){?>
                    <tr bgcolor="<? echo"$color";?>" >
                      <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                        <div align="left">
                <? echo $producto[0]; ?>
                        </div></div>
                      </td>
                      <td class="texto_info_negro"><div align="center" class="texto_info_negro">
                        <div align="left">
                <? echo $producto[1]; ?>
                        </div></div>
                      </td>
                <?
                
                }
				
                $count=$count+1;
                if($color == 'white')
                $color = '#E3E3E3';
                else $color = 'white';
	}
	?>
        </tbody>
        </table>
        </div>
                  <?
        } else {
        ?>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <br />
  <table width="100%" border="0" align="center" cellpadding="0">
    <tr>
                  <td bgcolor="#999999" class="style8" scope="row"><div align="center" class="texto_info_blanco" style="
	background-image: url(images/bkg_1.jpg);">Actualizar Existencias</div></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="0" bordercolor="#CCCCCC">        
        <tr>
          <th width="87" valign="top" class="texto_info_negro" scope="row"><div align="right">Archivo...</div>            <!--<div align="right">Extra&iacute;do de microsip 
            <input name="extraido_microsip" type="checkbox" id="extraido_microsip" value="1">
          </div>--></th>
          <td width="476" class="style5"><input name="archivo" type="file" class="texto_info_negro_forma" id="archivo">
            <br>
            <em><span class="texto_chico_gris">(Recuerda solo .csv)</span></em></td>
        </tr>
      </table></td>
    </tr>
		
  </table>
          <table width="450" border="0" align="center">
    <tr>
      <td class="style8"><div align="center">
        <input name="guardar" type="submit" class="texto_info" value="Importar"  onClick="return validar();"/>
      </div></td>
    </tr>
  </table>
</form><?}?>
</body>
</html>
