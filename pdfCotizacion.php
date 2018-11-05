<?

function getBodyCotizacion($cotizacion, $productos , $cliente , $esParaCliente){
//    $productos viene vacio

	$query="SELECT nombre FROM Usuarios where id=".$cotizacion->id_usuario_ultima_modificacion;
	$resultado=mysql_query($query)or die ("Error al consultar vendedor".mysql_error());
	$re=mysql_fetch_row($resultado);
	$usuario=$re[0];

	$ruta="http://www.herramientasag.com.mx/cotizador/";
    $rows=array();
    $color ="#CCCCCC";
    $cuantos=0;
	if($cotizacion->tipo_moneda=="0")
	{
		$tipo_moneda="M.N.";
		$moneda="pesos";
	}
	else
	{
		$tipo_moneda="USD";
		$moneda="dolares";
	}
    foreach ($productos as $n => $producto) {
		$cuantos++;
        if($color!="#CCCCCC")
            $color="#CCCCCC";
        else $color="";
        if($cotizacion->idioma == 'ESP')
            $nombre = $producto->nombre;
        else $nombre = $producto->descripcion;
		if($producto->comentario!="")
			$extra="(".$producto->comentario.")<br>";
		else
			$extra="";
		$extra=str_replace("\n",", ",$extra);
		if($producto->imagen!="")
			$imagen=$producto->imagen;
		else
			$imagen="archivos/spacer.gif";
		list($rut, $arch) = split('/', $imagen);
        $row = "
			<tr>
				<td valign=\"top\" class=\"texto_info_negro_c\"><div align=\"center\">". $cuantos ."</div></td>
				<td valign=\"top\" width=\"10%\" class=\"texto_info_negro_c\"><div align=\"center\"><img src=\"".$ruta."archivos/ch_".$arch."\" width=\"92\"  /></div></td>
				<td valign=\"top\" width=\"43%\" class=\"texto_info_negro_c\"><div align=\"left\">". $nombre ."<br><span class=\"style10\">".$extra."</span></div></td>
				<td valign=\"top\" class=\"texto_info_negro_c\"><div align=\"center\">". $producto->unidad_metrica ."</div></td>
				<td valign=\"top\" class=\"texto_info_negro_c\"><div align=\"center\">". $producto->cantidad ."</div></td>
				<td valign=\"top\" class=\"texto_info_negro_c\"><div align=\"right\">$". getFormatedNumberForMoney((((1-($producto->descuento))*$producto->precio)+$producto->recargo) ) ."</div></td>
				
				<td valign=\"top\" class=\"texto_info_negro_c\"><div align=\"right\">$".getFormatedNumberForMoney((((1-($producto->descuento))*$producto->precio)+$producto->recargo) * $producto->cantidad)."</div></td> 												  
			  </tr>";
			array_push($rows,$row);
    }
	
	$header2="<table width=\"720px\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"
border:1px solid black;
padding: 2px;
page-break-before:always;\">
  <tr>
    <td><table style=\"width:100%\">
  <tr>
    <td align=\"left\"><h2 style=\"margin-left:15px\">COTIZACIÓN</h2></td>
    <td align=\"center\">&nbsp;</td>
    <td align=\"center\">HERRAMIENTAS AG SA DE CV<br />
RFC: HAG920225FM7<br />
CALLE MIGUEL BARRAGAN 6915<br />+52 614 417.6565</td>
    <td align=\"center\">&nbsp;</td>
    <td align=\"center\"><span style=\"float:right;\"><img src=\"".$ruta."images/logo_AG.jpg\" width=\"150\" /></span></td>
  </tr></table></td>
  </tr>
  <tr>
    <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
        <td height=\"40\" class=\"style1\"></td>
        <td valign=\"top\"><table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
          <tr  bgcolor=\"#7F8084\">
            <td width=\"59%\" class=\"texto_info_blanco_forma\"><div align=\"center\">FECHA</div></td>
            <td width=\"41%\" class=\"texto_info_blanco_forma\"><div align=\"center\">FOLIO</div></td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_c\"><div align=\"center\">".$cotizacion->enviada_cliente_en_fecha."</div></td>
            <td class=\"texto_info_negro_c\"><div align=\"center\">".$cotizacion->id ."</div></td>
          </tr>
        </table></td>
      </tr>
	</table></td>
  </tr>";
	
    $body = "
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Cotización</title>
<style type=\"text/css\">
.style1 {
	font-size: xx-large;
	font-weight: bold;
.style3 {font-size: 10px}
.style10 {font-size: x-small}
}
.footer { position: fixed; bottom: 0px; }
.pagenum:before { content: counter(page); }
</style>
</head>
<link href=\"".$ruta."images/textos.css\" rel=\"stylesheet\" type=\"text/css\" />

<body>
<div class=\"footer\" align=\"center\">Página <span class=\"pagenum\"></span></div>
<table width=\"720px\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"
border:1px solid black;
padding: 4px;\">
  <tr>
    <td><table style=\"width:100%\">
  <tr>
    <td align=\"left\"><h2 style=\"margin-left:15px\">Cotización</h2></td>
    <td align=\"center\">&nbsp;</td>
    <td align=\"center\">HERRAMIENTAS AG SA DE CV<br />
RFC: HAG920225FM7<br />
CALLE MIGUEL BARRAGAN 6915<br />+52 614 417.6565</td>
    <td align=\"center\">&nbsp;</td>
    <td align=\"center\"><span style=\"float:right;\"><img src=\"".$ruta."images/logo_AG.jpg\" width=\"150\" /></span></td>
  </tr></table></td>
  </tr>
  <tr>
    <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     
      <tr>
        <td width=\"65%\"><table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
          <tr bgcolor=\"#7F8084\">
            <td><div align=\"center\" class=\"texto_info_blanco_forma\">CLIENTE</div></td>
          </tr>
          <tr>
            <td height=\"25\" valign=\"top\" class=\"texto_info_negro_c\"><p>".$cliente->id ." ".$cliente->nombre_empresa ."</p>              </td>
          </tr>
          <tr>
            <td height=\"25\" valign=\"top\" class=\"texto_info_negro_c\">".$cliente->direccion_empresa ."</td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_c\">".$cliente->estado ." ".$cliente->ciudad ."</td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_c\">RFC: ".$cliente->rfc ." </td>
          </tr>
        </table></td>
        <td width=\"35%\" valign=\"top\"><table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
          <tr  bgcolor=\"#7F8084\">
            <td width=\"59%\" class=\"texto_info_blanco_forma\"><div align=\"center\">FECHA</div></td>
            <td width=\"41%\" class=\"texto_info_blanco_forma\"><div align=\"center\">FOLIO</div></td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_c\"><div align=\"center\">".$cotizacion->enviada_cliente_en_fecha."</div></td>
            <td class=\"texto_info_negro_c\"><div align=\"center\">".$cotizacion->id ."</div></td>
          </tr>
        </table>
          <table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
            <tr  bgcolor=\"#7F8084\">
              <td class=\"texto_info_blanco_forma\"><div align=\"center\">AT'N</div></td>
            </tr>
            <tr>
              <td height=\"60\" class=\"texto_info_negro_c\">".$cotizacion->atencion ." <br />
                ".$cliente->telefono_empresa ." </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr  bgcolor=\"#7F8084\">
        <td width=\"15%\" class=\"texto_info_blanco_forma\"><div align=\"center\">VIGENCIA</div></td>
        <td width=\"44%\" class=\"texto_info_blanco_forma\"><div align=\"center\">CONDICIONES</div></td>
        <td width=\"27%\" class=\"texto_info_blanco_forma\"><div align=\"center\">VENDEDOR</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">REFERENCIA</div></td>
      </tr>
      <tr>
        <td class=\"texto_info_negro_c\"><div align=\"center\">".$cotizacion->vigencia."</div></td>
        <td class=\"texto_info_negro_c\"><div align=\"left\">".$cliente->condiciones_pago."</div></td>
        <td class=\"texto_info_negro_c\"><div align=\"center\">".$usuario."</div></td>
        <td class=\"texto_info_negro_c\">".$cotizacion->referencia."</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>";
	if($cuantos<5){//todo en una hoja
	  $body.="<td height=\"460px\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr bgcolor=\"#7F8084\">
        <td width=\"6%\" class=\"texto_info_blanco\"><div align=\"center\">PART.</div></td>
		   
        <td class=\"texto_info_blanco_forma\"  colspan=\"2\"><div align=\"center\">NOMBRE</div></td>
        <td width=\"7%\" class=\"texto_info_blanco_forma\"><div align=\"center\">U. MED. </div></td>
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">UNI</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PRECIO</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">IMPORTE</div></td>
      </tr>";
	  $cnt=0;
	  while($cuantos>$cnt++)
	  	$body.=$rows[$cnt-1];
	}else{//mas de 5 productos
	  $body.="<td height=\"550px\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr bgcolor=\"#7F8084\">
        <td width=\"6%\" class=\"texto_info_blanco\"><div align=\"center\">PART.</div></td>
		   
        <td class=\"texto_info_blanco_forma\"  colspan=\"2\"><div align=\"center\">NOMBRE</div></td>
        <td width=\"7%\" class=\"texto_info_blanco_forma\"><div align=\"center\">U. Med. </div></td>
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">UNIDADES></div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PRECIO</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">IMPORTE</div></td>
      </tr>";
	  $cnt=0;
	  while(5>$cnt++)
		$body.=$rows[$cnt-1];
	  $body.="</table></td>
  </tr>
</table>
$header2
  <tr>";
	  if($cuantos<13){
	  $body.="<td height=\"710px\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr bgcolor=\"#7F8084\">
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PART.</strong></div></td>
		   
        <td class=\"texto_info_blanco_forma\"  colspan=\"2\"><div align=\"center\">NOMBRE</div></td>
        <td width=\"7%\" class=\"texto_info_blanco_forma\"><div align=\"center\">>U. MED. </div></td>
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">UNIDADES</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PRECIO</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">IMPORTE</div></td>
      </tr>";
	  $cnt=5;
	  while(12>$cnt++)
		$body.=$rows[$cnt-1];
	  }else{//mas de 13 productos
		$body.="<td height=\"800px\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr bgcolor=\"#7F8084\">
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\"><strong>PART.</strong></div></td>
		   
        <td class=\"texto_info_blanco_forma\"  colspan=\"2\"><div align=\"center\">NOMBRE</div></td>
        <td width=\"7%\" class=\"texto_info_blanco_forma\"><div align=\"center\">U. MED. </div></td>
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">UNIDADES</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PRECIO</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">IMPORTE</div></td>
      </tr>";
		$cnt=5;
		while(12>$cnt++)
		  $body.=$rows[$cnt-1];
		$body.="</table></td>
  </tr>
</table>
$header2
  <tr>";
		if($cuantos<20){
		  $body.="<td height=\"710px\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr bgcolor=\"#7F8084\">
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PART.</div></td>
		   
        <td class=\"texto_info_blanco_forma\"  colspan=\"2\"><div align=\"center\">NOMBRE</div></td>
        <td width=\"7%\" class=\"texto_info_blanco_forma\"><div align=\"center\">U. MED. </div></td>
        <td width=\"6%\" class=\"texto_info_blanco_forma\"><div align=\"center\">UNIDADES</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">PRECIO</div></td>
        <td width=\"14%\" class=\"texto_info_blanco_forma\"><div align=\"center\">IMPORTE</div></td>
      </tr>";
		  $cnt=12;
		  while(20>$cnt++)
			$body.=$rows[$cnt-1];
		}
	  }
	}
	  $body .="
    </table></td>
  </tr>
  <tr>
    <td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr >
        <td  bgcolor=\"#7F8084\" width=\"19%\" class=\"texto_info_blanco_forma\"><div align=\"center\">LAB</div></td>
        <td bgcolor=\"#7F8084\" width=\"27%\" class=\"texto_info_blanco_forma\"><div align=\"center\">TERM. ENTREGA </div></td>
        <td  bgcolor=\"#7F8084\" width=\"20%\" class=\"texto_info_blanco_forma\"><div align=\"center\">NOTAS</div></td>
        <td width=\"34%\" rowspan=\"3\" bgcolor=\"#FFFFFF\" class=\"texto_info_blanco\"><table width=\"87%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\">
          <tr>
            <td width=\"47%\"  bgcolor=\"#7F8084\"><div align=\"center\" class=\"texto_info_blanco_forma\">SUBTOTAL</div></td>
            <td width=\"53%\" class=\"texto_info_negro_c\"><div align=\"right\">$".getFormatedNumberForMoney(round($cotizacion->subtotal,2))."</div></td>
          </tr>
          <tr>
            <td  bgcolor=\"#7F8084\"><div align=\"center\" class=\"texto_info_blanco_forma\">IVA</div></td>
            <td class=\"texto_info_negro_c\"><div align=\"right\">$".getFormatedNumberForMoney(round($cotizacion->iva,2))."</div></td>
          </tr>
          <tr>
            <td  bgcolor=\"#7F8084\"><div align=\"center\" class=\"texto_info_blanco_forma\">TOTAL (".$tipo_moneda.")</div></td>
            <td class=\"texto_info_negro_c\"><div align=\"right\">$".getFormatedNumberForMoney(round($cotizacion->total,2))."</div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class=\"texto_info_negro_c\"><div align=\"center\">".$cotizacion->LAB."</div></td>
        <td class=\"texto_info_negro_c\"><div align=\"left\">".$cotizacion->terminos_entrega."</div></td>
        <td class=\"texto_info_negro_c\"><div align=\"left\">".$cotizacion->notas_adicionales."</div></td>
        </tr>
  <tr>
    <td colspan=\"3\" class=\"texto_info_negro\"><i>".$cotizacion->getLeyenda()."<BR><strong> (".num2letras(round($cotizacion->total,2), $moneda)." ".$tipo_moneda.")</strong><br> </i></td>
  </tr> 
    </table></td>
  </tr>
</table>
</body>
	</html>";
	//	echo"$body";
    return $body;
    
}
function num2letras($num, $moneda, $fem = false, $dec = true) { 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 
   
   //Zi hack
   $float=explode('.',$num);
   $num=$float[0];

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   //Zi hack --> return ucfirst($tex);
   $end_num=ucfirst($tex).' '.$moneda.' '.$float[1].'/100 ';
   return $end_num; 
} 
?>