<?php
	/**
     * Test.
     * Developer: Luis perez
     * Company: Bluewolf.
     * Date: 27/07/2018
	 * 
	 * Base de datos: herramie_bluewolf_ag_testing
	 * Usuario: herramie_bw
     * Pass: myuserag56
     */

	$enlace = mysql_connect('localhost:3306', 'herramie_bw', 'myuserag56');
	mysql_set_charset('utf8',$enlace);
    if (!$enlace)
    { 
        die('Could not connect to MySQL: ' . mysql_error()); 
    }
	mysql_select_db("herramie_bluewolf_ag_testing") or print("No pudo seleccionarse la BD.");
?>
