<?

include_once "coneccion.php";


     $consulta  = "select codigo, nombre, descripcion  from Productos_respaldo where id_proveedor=1 and nombre<>''";
	$resultado = mysql_query($consulta) or die("La consulta fall&oacute;P1:$consulta " . mysql_error());
	$count=1;
	$conta=0;
	while(@mysql_num_rows($resultado)>=$count)
	{
	
		$res=mysql_fetch_row($resultado);//EFPHANNIBALB
		$codigo=$res[0];
		$nombre=$res[1];
		$descricpion=$res[2];
		$codigo=str_replace("'","\\'","$codigo");
		$nombre=str_replace("'","\\'","$nombre");
		$descricpion=str_replace("'","\\'","$descricpion");
		$consulta2  = "update Productos set nombre='$nombre' where id_proveedor=1 and codigo='$codigo'";//, descripcion='$descricpion'
		if($count>=1)
		{
		$resultado2 = mysql_query($consulta2) or die("La consulta fall&oacute;P1:$consulta2 " . mysql_error());
		echo"$count $consulta2<br>";
		}
		$count++;
	}
    
  echo"$count";

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
</form>
</body>
</html>
