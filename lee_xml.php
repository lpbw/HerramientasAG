<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); 

include "coneccion.php";


$xml = simplexml_load_file("http://www.banxico.org.mx/rsscb/rss?BMXC_canal=fix&BMXC_idioma=es") or die("Error, no se encontro archivo");
$valor=$xml->item->title. "\n";
$valor=explode(" ", $valor);

$dolar="DOLAR";
echo $dolar;
echo "<br/>";
$valor=$valor[1];
echo $valor;

	if($dolar=="DOLAR"){
		$consulta  = "update tipo_de_cambio set valor=round('$valor' ,2) where id=1";
		$resultado = mysql_query($consulta) or die("Error en operacion1: $consulta" . mysql_error());
		
		$to="roberto@herramientasag.com.mx";
		$to2="agcuevas@herramientasag.com.mx";
		$to3="operaciones@herramientasag.com.mx";
		$to4="lupita@herramientasag.com.mx";
		$to5="mostrador@herramientasag.com.mx";
		$to6="mostrador2@herramientasag.com.mx";
		
		$subject="Tipo de cambio";
		
		$EmailFrom = "no-contestar@herramientasag.com.mx";
		$returnpath = "-f" . $EmailFrom;

		$success = mail($to, $subject, "Tipo de cambio ejecutado: $valor", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
		$success2 = mail($to2, $subject, "Tipo de cambio ejecutado: $valor", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
		$success3 = mail($to3, $subject, "Tipo de cambio ejecutado: $valor", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
		$success4 = mail($to4, $subject, "Tipo de cambio ejecutado: $valor", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
		$success5 = mail($to5, $subject, "Tipo de cambio ejecutado: $valor", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
		$success6 = mail($to6, $subject, "Tipo de cambio ejecutado: $valor", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
		}else{
			$to="roberto@herramientasag.com.mx";
			$to2="agcuevas@herramientasag.com.mx";
			$to3="operaciones@herramientasag.com.mx";
			$to4="lupita@herramientasag.com.mx";
			$to5="mostrador@herramientasag.com.mx";
			$to6="mostrador2@herramientasag.com.mx";
		
			$subject="Tipo de cambio";
		
			$EmailFrom = "no-contestar@herramientasag.com.mx";
			$returnpath = "-f" . $EmailFrom;

			$success = mail($to, $subject, "No se encontro valor de Dolar en DOF - Diario Oficial de la Federación - Indicadores", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
			$success2 = mail($to2, $subject, "No se encontro valor de Dolar en DOF - Diario Oficial de la Federación - Indicadores", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		
			$success3 = mail($to3, $subject, "No se encontro valor de Dolar en DOF - Diario Oficial de la Federación - Indicadores", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
			
			$success4 = mail($to4, $subject, "No se encontro valor de Dolar en DOF - Diario Oficial de la Federación - Indicadores", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
			
			$success5 = mail($to5, $subject, "No se encontro valor de Dolar en DOF - Diario Oficial de la Federación - Indicadores", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
			
			$success6 = mail($to6, $subject, "No se encontro valor de Dolar en DOF - Diario Oficial de la Federación - Indicadores", "From: Herramientas AG <$EmailFrom>\nContent-type: text/html; charset=utf-8\n", $returnpath);
		}

?>