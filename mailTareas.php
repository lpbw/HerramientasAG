<?
include_once 'Tarea.php';
function getBodyTareas($tareas){

    $ruta="http://herramientasag.com.mx/cotizador/";
$body = "
<html>
<head>

<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<title>Untitled Document</title>

<style type=\"text/css\">
<!--
.style6 {color: #FFFFFF}
.style5 {font-size: 18}
-->
</style>

<style type=\"text/css\">
<!--
.style51 {font-size: 12}
</style>

</head>
<link href=\"".$ruta."images/textos.css\" rel=\"stylesheet\" type=\"text/css\" />

<body onLoad=\"MM_preloadImages('images/cerrar_r.jpg')\" >
<form action=\"\" method=\"post\" enctype=\"multipart/form-data\" name=\"form1\" id=\"form1\">

<div id=\"containerTareas\" style=\" width:430px; padding:10px 30px 10px 30px; background-color: #e3e3e3;\">";
    

    foreach ($tareas as $tarea) { 
	$body .= "
    <div align=\"center\" style=\"margin: 10px;\">
    <div class=\"texto_info\" style=\"height:20px;\">
    <div style=\"float:left;\">inicio: ".$tarea['fecha_inicio_user_friendly']."</div>
    
    <span style=\"float:right;margin:0px 5px 0px 5px; width:17px; height:16px; border:0\">
       <a href=\"".$ruta."main.php?\">
       <img src=\"".$ruta."images/reminder.png\" alt=\"\" name=\"Image86\" width=\"17\" height=\"16\" border=\"0\" title=\"".$tarea['fecha_recordatorio_user_friendly']."\" /></a>
    </span>
    <div style=\"float:right\">";
        if($tarea['fecha_vencimiento']=="")
            $body .= " -sin fecha- ";
        else $body .= $tarea['fecha_vencimiento_user_friendly'];
        
    $body .= "</div>
    </div><div style=\"float:left; width:100%\">";
        if($tarea['id_cotizacion']!="")
            $body .= "<span class=\"texto_chico_gris\">
                Cotizacion # ".$tarea['id_cotizacion']."
                    </span>";
        
        $body .= "</div>
    <div align=\"left\" class=\"texto_info_negro\" style=\"background-color: #FFF;-moz-border-radius: 20px;-webkit-border-radius: 10px;border-radius: 10px; padding: 10px;\" >
         <a href=\"".$ruta."main.php\" class=\"texto_info_negro\" >
          ".$tarea['asunto']."</a>
    </div>
    </div>";
     }
$body .= "
</div>
</form>
</body>
</html>";
return $body;
 }?>