<?
include "coneccion.php";
//mysqli_query("SET NAMES 'utf8'");
header("Content-Type: text/html;charset=utf-8");
$i=$_GET['i'];
$marca=$_GET['marca'];

	if($i=="1")
	{
		$option="";
		if($marca!="0")
			{
				$option="<option value=\"0\">SELECCIONE LINEA</option>";
				$consulta="select * from datos where marca='$marca'";
				$resultado = mysql_query($consulta) or print("La consulta linea: " . mysql_error());
				while($res=mysql_fetch_assoc($resultado))
				{
					$linea=$res['id'];
					$nomlinea=$res['linea'];
					$option=$option."<option value=\"$linea\">$nomlinea</option>";
				}
				
			}	
		else
			{
				$option="<option value=\"0\">SELECCIONE LINEA</option>";
				
			}
			echo $option;
	}
	
	if($i=="2")
	{
		$linea=$_GET['linea'];
		$factor=0;
		$notas="";
		$desc_frontera=0;
		$desc_cuu=0;
		$desc_jrz=0;
		$desc_max=0;
		if($linea!="0")
		{	
		$consulta="select * from datos where id='$linea'";
		$resultado = mysql_query($consulta) or print("La consulta linea: " . mysql_error());
		$res=mysql_fetch_assoc($resultado);
		$factor=$res['factor'];
		$notas=$res['notas'];
		$notas_adicionales=$res['notas_adicionales'];
		$desc_frontera=$res['descuento_frontera'];
		$desc_cuu=$res['descuento_cuu'];
		$desc_jrz=$res['descuento_jrz'];
		$desc_max=$res['descuento_maximo'];
		$id_l=$res['marca'];
		
		echo "<tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\" width=\"300px\">FACTOR</th><td class=\"style5\" width=\"300px\" align=\"center\">$factor</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\" >NOTAS</th><td class=\"style5\" align=\"center\">$notas <input name=\"id_l\" type=\"hidden\" id=\"id_l\" value=\"$id_l\"></td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCTO FRONTERA</th><td class=\"style5\" align=\"center\">$desc_frontera</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCTO CUU</th><td class=\"style5\" align=\"center\">$desc_cuu</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCT JRZ</th><td class=\"style5\" align=\"center\">$desc_jrz</td>
</tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCT MAXIMO</th><td class=\"style5\" align=\"center\">$desc_max</td>
</tr>
<tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">NOTAS ADICIONALES</th><td class=\"style5\" align=\"center\"><textarea name=\"notas_adicionales\" id=\"notas_adicionales\" rows=\"3\" cols=\"35\">$notas_adicionales</textarea></td>
</tr>
";
	    }
		else
		{
			echo "<tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\" width=\"300px\">FACTOR</th><td class=\"style5\" width=\"300px\" align=\"center\">$factor</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\" >NOTAS</th><td class=\"style5\" align=\"center\">$notas</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCTO FRONTERA</th><td class=\"style5\" align=\"center\">$desc_frontera</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCTO CUU</th><td class=\"style5\" align=\"center\">$desc_cuu</td></tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCT JRZ</th><td class=\"style5\" align=\"center\">$desc_jrz</td>
</tr><tr><th align=\"left\" valign=\"top\" class=\"texto_info\" scope=\"row\">% DSCT MAXIMO</th><td class=\"style5\" align=\"center\">$desc_max</td>
</tr>";
		}
	}
?>
