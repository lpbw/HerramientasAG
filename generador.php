<?php
require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
 
// almacenamos el contenido HTML
$sHTML = <<<PHP
<html><head>
<title>Ejemplo de la librería dompdf</title>
</head><body>
<pre>PHP es un lenguaje de programación interpretado, diseñado originalmente para la
creación de páginas web  dinámicas. Es usado principalmente en interpretación del lado
del servidor (server-side scripting) pero actualmente puede ser utilizado desde una
interfaz de línea de comandos o en la creación de otros tipos de
programas incluyendo aplicaciones con interfaz gráfica usando las bibliotecas
Qt o GTK+.
</pre></body></html>
PHP;
       
// creamos la instancia
$PDF = new DOMPDF();

// autorizamos la impresion del HTML
$PDF ->load_html($sHTML);
$PDF ->render();

// devolvemos el PDF
$PDF ->stream("html.pdf");

?>