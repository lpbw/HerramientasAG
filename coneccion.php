<?php 
/*
 * Base de datos
herramie_bluewolf_ag
Usuario: herramie_bw
Pass: myuserag56
 */
	$enlace = mysql_connect('localhost:3306', 'herramie_bw', 'myuserag56');
	mysql_set_charset('utf8',$enlace);
	//debug
        //$link = mysql_connect('localhost','root',''); 
        if (!$enlace) { 
                die('Could not connect to MySQL: ' . mysql_error()); 
        }
	mysql_select_db("herramie_bluewolf_ag_testing") or print("No pudo seleccionarse la BD.");
?>
