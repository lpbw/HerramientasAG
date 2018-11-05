<?php
require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
 
// almacenamos el contenido HTML
$sHTML = <<<PHP
<html><head>
<title>Ejemplo de la librer�a dompdf</title>
</head><body>
<pre>PHP es un lenguaje de programaci�n interpretado, dise�ado originalmente para la
creaci�n de p�ginas web  din�micas. Es usado principalmente en interpretaci�n del lado
del servidor (server-side scripting) pero actualmente puede ser utilizado desde una
interfaz de l�nea de comandos o en la creaci�n de otros tipos de
programas incluyendo aplicaciones con interfaz gr�fica usando las bibliotecas
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