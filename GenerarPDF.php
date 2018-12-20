<?php
    include_once 'Usuario.php';
    include_once 'Producto.php';
    include_once 'Cliente.php';
    include_once 'Cotizacion.php';
    include_once "coneccion.php";
    include_once 'functions_cotizacion.php';
    date_default_timezone_set('America/Chihuahua');
	//echo $_SESSION['cotizacion']->id;
	if(isset($_SESSION['cotizacion'])){
		include_once 'mailCotizacion.php';
		require_once(dirname(__FILE__)."/dompdf/dompdf_config.inc.php");
		require_once ("dompdf/include/style.cls.php");
        $cliente = new Cliente();
		$cliente -> get($_SESSION['cotizacion'] ->id_cliente);
        $cotizacion = new Cotizacion();
        $cotizacion = $_SESSION['cotizacion'];
		$cotizacion -> enviada_cliente_en_fecha = date('Y-m-d H:i:s');
		$html=getBodyCotizacion($cotizacion, $cotizacion->productos, $cliente, $esParaCliente = false);
		$pdf = new DOMPDF();
		$pdf ->load_html($html);
		$pdf ->render();
		$archivo="pdfs/cotizacion_".$cotizacion->id.".pdf";
		file_put_contents($archivo,$pdf->output());
		$pdf->stream("cotizacion_".$cotizacion->id.".pdf");
	    echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	}
?>