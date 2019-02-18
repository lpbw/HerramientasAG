<?
    session_start();
    include_once "getFormatedNumberForMoney.php";
    $valor_moneda = $_SESSION['dollar'];
    function getRutaMail()
    {
        return "http://www.herramientasag.com.mx/cotizador_test/";
    }

    function getBodyCotizacion($cotizacion, $productos , $cliente , $esParaCliente,$sinCodigos = TRUE)
    {
        //$productos viene vacio
        $query="SELECT nombre FROM Usuarios where id=".$cotizacion->id_usuario_ultima_modificacion;
        $resultado=mysql_query($query)or die ("Error al consultar vendedor".mysql_error());
        $re=mysql_fetch_row($resultado);
        $usuario=$re[0];
					
	    $ruta=getRutaMail();
        if($esParaCliente)
        {
            $mensajeBienvenida = "Gracias por contactarnos, esta es la cotizacion que nos has solicitado. <br>";
            $mensajeBienvenida .= "Sí deseas hacer alguna aclaración estamos a tus órdenes. <br>";
            $mensajeBienvenida .= "Atte: Herramientas AG <br>";
            $mensajeBienvenida .= "Tel: 614 1 234 567<br>";
            $mensajeBienvenida .= "Fax: 614 4 567 890";
        }
        else
        {
            $mensajeBienvenida= "<h2>Cliente <b>".  $cliente->nombre_contacto."</b></h2>$cliente->email_contacto";
        }
        
        $rows="";
        $color ="#CCCCCC";
        $cuantos=1;
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
        foreach ($productos as $n => $producto)
        {
            if($color!="#CCCCCC")
            {
                $color="#CCCCCC";
            }   
            else
            {
                $color="";
            } 
        
            if($cotizacion->idioma == 'ESP')
            {
                $nombre = $producto->nombre;
            }
            else
            {
                $nombre = $producto->descripcion;
            }
        
            //if(!$sinCodigos)
            $nombre.=  " ($producto->codigo_interno)";
        
            if($producto->comentario!="")
            {
                $extra="(".$producto->comentario.")<br>";
            }  
            else
            {
                $extra="";
            }
            
            $extra=str_replace("\n","<br>",$extra);
            if($producto->imagen!="")
            {
                $imagen=$producto->imagen;
                $widthRowImage = "92px";
            }
            else
            {
                $imagen="archivos/spacer.gif";        
                $widthRowImage = "1px";
            }
            list($rut, $arch) = explode('/', $imagen);

            /*
            if($producto->tipo_moneda_usa != $_SESSION['cotizacion']->tipo_moneda)
            {
				$precio_n=getFormatedNumberForMoney(( ((1 - $producto->descuento ) * $producto->precio) + ($producto->recargo * $_SESSION['dollar']) ) );
				$total_n=getFormatedNumberForMoney((((1-($producto->descuento))*$producto->precio)+ ($producto->recargo * $_SESSION['dollar'])) * $producto->cantidad);
            }
            else
            {
				$precio_n=getFormatedNumberForMoney(( ((1 - $producto->descuento ) * $producto->precio) + $producto->recargo ) );
				$total_n=getFormatedNumberForMoney((((1-($producto->descuento))*$producto->precio)+$producto->recargo) * $producto->cantidad);
      }*/

      /**Dolares a pesos */
      if ($producto->tipo_moneda_usa == "1" && $_SESSION['cotizacion']->tipo_moneda == "0"){
        $precio_n = getFormatedNumberForMoney((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento))));
        $total_n = getFormatedNumberForMoney(((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento)))*$producto->cantidad));
      }else if($producto->tipo_moneda_usa == "0" && $_SESSION['cotizacion']->tipo_moneda == "1"){
        //pesos a dolar
        $precio_n = getFormatedNumberForMoney((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento))));
        $total_n = getFormatedNumberForMoney(((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento)))*$producto->cantidad));
       }else if($producto->tipo_moneda_usa == $_SESSION['cotizacion']->tipo_moneda){
          switch ($producto->tipo_moneda_usa){
            case "0":
              $precio_n = getFormatedNumberForMoney((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento))));
              $total_n = getFormatedNumberForMoney(((($producto->precio+($producto->recargo*$valor_moneda))-(($producto->precio+($producto->recargo*$valor_moneda))*($producto->descuento)))*$producto->cantidad));
            break;
            case "1":
              $precio_n = getFormatedNumberForMoney((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento))));
              $total_n = getFormatedNumberForMoney(((($producto->precio+$producto->recargo)-(($producto->precio+$producto->recargo)*($producto->descuento)))*$producto->cantidad));
            break;
            default:
            break;
          }
        }
            $rows .= "<tr>";
            $rows .= "<td valign=\"top\" class=\"texto_info_negro_cMail\"><div align=\"center\">$producto->partida</div></td>";
            $rows .= "<td valign=\"top\" class=\"texto_info_negro_cMail\" width=\"$widthRowImage\"><div align=\"center\"><img src=\"".$ruta."archivos/ch_".$arch."\"  /></div></td>";
            $rows .= "<td valign=\"top\" class=\"texto_info_negro_cMail\"><div align=\"left\">". $nombre ."<br> ".stripslashes($extra)."</div></td>";
            $rows .= "<td valign=\"top\" class=\"texto_info_negro_cMail\"><div align=\"center\">$producto->cantidad $producto->unidad_metrica</div></td>";
            $rows .= "<td valign=\"top\" class=\"texto_info_negro_cMail\"><div align=\"right\">$".$precio_n."</div></td>";
            $rows .= "<td valign=\"top\" class=\"texto_info_negro_cMail\"><div align=\"right\">$".$total_n."</div></td>";											  
            $rows .= "</tr>";
            $cuantos++;
        }
        $body = "<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Cotización</title>
<style type=\"text/css\">
.style1 {
	font-size: xx-large;
	font-weight: bold;
.style3 {font-size: 10px}
}
</style>
</head>
<link href=\"".$ruta."images/textos.css\" rel=\"stylesheet\" type=\"text/css\" />

<body>
<table width=\"720px\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"
border:1px solid black;
padding: 2px;
page-break-inside:avoid;\">
  <tr>
    <td><table style=\"width:100%\">
  <tr>
    <td align=\"left\" class=\"texto_info_negro_forma\"><h2 style=\"margin-left:15px\">COTIZACIÓN</h2></td>
    <td align=\"center\">&nbsp;</td>
    <td align=\"center\" class=\"texto_info_negro_forma\">HERRAMIENTAS AG SA DE CV<br />
RFC: HAG920225FM7<br />
CALLE MIGUEL BARRAGAN 6915<br />+52 614 417.6565</td>
    <td align=\"center\">&nbsp;</td>
    <td align=\"center\"><span style=\"float:right;\"><img src=\"".$ruta."images/logo_ag_cot.jpg\" width=\"150\" /></span></td>
  </tr></table></td>
  </tr>
  <tr>
    <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      
      <tr>
        <td width=\"65%\"><table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
          <tr bgcolor=\"#7F8084\">
            <td><div align=\"center\" class=\"texto_info_blanco_formaMail\"><strong>CLIENTE</strong></div></td>
          </tr>
          <tr>
            <td height=\"30\" valign=\"top\" class=\"texto_info_negro_cMail\"><p>".$cliente->id ." ".$cliente->nombre_empresa ."</p>              </td>
          </tr>
          <tr>
            <td height=\"30\" valign=\"top\" class=\"texto_info_negro_cMail\">".$cliente->direccion_empresa ."</td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_cMail\">".$cliente->estado ." ".$cliente->ciudad ."</td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_cMail\">RFC: ".$cliente->rfc ." </td>
          </tr>
        </table></td>
        <td width=\"35%\" valign=\"top\">
		<table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
          <tr  bgcolor=\"#7F8084\">
            <td width=\"59%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>FECHA</strong></div></td>
            <td width=\"41%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>FOLIO</strong></div></td>
          </tr>
          <tr>
            <td class=\"texto_info_negro_cMail\"><div align=\"center\">".$cotizacion->enviada_cliente_en_fecha."</div></td>
            <td class=\"texto_info_negro_cMail\"><div align=\"center\">".$cotizacion->id ."</div></td>
          </tr>
        </table>
          <table width=\"92%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
            <tr  bgcolor=\"#7F8084\">
              <td class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>AT'N</strong></div></td>
            </tr>
            <tr>
              <td height=\"60\" class=\"texto_info_negro_cMail\">".$cotizacion->atencion ." <br />
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
        <td width=\"15%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>VIGENCIA</strong></div></td>
        <td width=\"44%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>CONDICIONES</strong></div></td>
        <td width=\"27%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>VENDEDOR</strong></div></td>
        <td width=\"14%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>REFERENCIA</strong></div></td>
      </tr>
      <tr>
        <td class=\"texto_info_negro_cMail\"><div align=\"center\">".$cotizacion->vigencia."</div></td>
        <td class=\"texto_info_negro_cMail\"><div align=\"left\">".$cliente->condiciones_pago."</div></td>
        <td class=\"texto_info_negro_cMail\"><div align=\"center\">".$usuario."</div></td>
        <td class=\"texto_info_negro_cMail\">".$cotizacion->referencia."</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height=\"483px\" valign=\"top\">
    <table width=\"100%\" border=\"0\" cellspacing=\"2\"  cellpadding=\"0\">
      <tr bgcolor=\"#7F8084\" class=\"texto_info_blanco_formaMail\">
        <td width=\"5px\"   class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>#</strong></div></td>	   
        <td width=\"\"    class=\"texto_info_blanco_formaMail\" colspan=\"2\"><div align=\"center\" style=\"margin-right:5px\"><strong>NOMBRE</strong></div></td>
        <td width=\"8%\"  class=\"texto_info_blanco_formaMail\"><div align=\"center\" style=\"margin-right:5px\"><strong>CANTIDAD</strong></div></td>
        <td width=\"7%\"  class=\"texto_info_blanco_formaMail\"><div align=\"center\" style=\"margin-right:5px\"><strong>PRECIO</strong></div></td>
        <td width=\"7%\"  class=\"texto_info_blanco_formaMail\"><div align=\"center\" style=\"margin-right:5px\"><strong>IMPORTE</strong></div></td>
      </tr>
	 
      ". $rows ."
    </table></td>
  </tr>
  <tr>
    <td valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">
      <tr >
        <td  bgcolor=\"#7F8084\" width=\"27%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>LAB</strong></div></td>
        <td bgcolor=\"#7F8084\" width=\"19%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>TERM. ENTREGA </strong></div></td>
        <td  bgcolor=\"#7F8084\" width=\"20%\" class=\"texto_info_blanco_formaMail\"><div align=\"center\"><strong>NOTAS</strong></div></td>
        <td width=\"34%\" rowspan=\"3\" bgcolor=\"#FFFFFF\" class=\"texto_info_blanco_formaMail\"><table width=\"87%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\">
          <tr>
            <td width=\"47%\"  bgcolor=\"#7F8084\"><div align=\"center\" class=\"texto_info_blanco_formaMail\"><strong>SUBTOTAL</strong></div></td>
            <td width=\"53%\" class=\"texto_info_negro_cMail\"><div align=\"right\">$".getFormatedNumberForMoney(round($cotizacion->subtotal,2))."</div></td>
          </tr>
          <tr>
            <td  bgcolor=\"#7F8084\"><div align=\"center\" class=\"texto_info_blanco_formaMail\"><strong>IVA</strong></div></td>
            <td class=\"texto_info_negro_cMail\"><div align=\"right\">$".getFormatedNumberForMoney(round($cotizacion->iva,2))."</div></td>
          </tr>
          <tr>
            <td  bgcolor=\"#7F8084\"><div align=\"center\" class=\"texto_info_blanco_formaMail\"><strong>TOTAL (".$tipo_moneda.")</strong></div></td>
            <td class=\"texto_info_negro_cMail\"><div align=\"right\">$".getFormatedNumberForMoney(round($cotizacion->total,2))."</div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class=\"texto_info_negro_cMail\"><div align=\"center\">".stripslashes($cotizacion->LAB)."</div></td>
        <td class=\"texto_info_negro_cMail\"><div align=\"left\">".stripslashes($cotizacion->terminos_entrega)."</div></td>
        <td class=\"texto_info_negro_cMail\"><div align=\"left\">".stripslashes($cotizacion->notas_adicionales)."</div></td>
        </tr>
  <tr>
    <td colspan=\"3\" class=\"texto_info_negro_cMail\"><i>".$cotizacion->getLeyenda()."<BR><strong> (".num2letras(round($cotizacion->total,2), $moneda)." ".$tipo_moneda.")</strong><br> </i></td>
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

function getBannerMail(){
    $query = "SELECT * FROM bannerMail";
    $result = mysql_query($query) or print(mysql_error());
    $banner = mysql_fetch_assoc($result);
    return $banner;
}
function getRutaBannerMail(){
    $banner = getBannerMail();
    return $banner['rutaCompleta'];
}
?>