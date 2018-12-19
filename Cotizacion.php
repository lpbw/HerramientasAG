<?
    include_once 'UploadFiles.php';
    session_start();
    class Cotizacion extends UploadFiles
    {
        public $id;
        public $id_cotizacion_padre;
        public $id_usuario;
        public $id_usuario_ultima_modificacion;
        public $fecha_ultima_modificacion;
        public $prioridad;
        public $id_cliente;
        public $id_contacto;
        public $id_estatus;
        public $notas_adicionales;
        public $LAB;
        public $terminos_entrega;
        public $extra;
        public $tipo_moneda;
        public $estatus;
        public $valor_moneda;
        public $atencion;
        public $referencia;
        public $vigencia;
        /* @var $productos callable ArrayObject */
        public $productos = array();
        public $iva;
        public $con_iva;
        public $subtotal;
        public $total;
        public $idioma;
        /* @var $archivos ArrayObject */
        public $archivos = array();
        public $es_version = false;
        public $fecha_version = NULL;
        public $id_version = 0;


        public function get($id, $idVersion = 0)
        {
            $consulta = "SELECT id, id_cotizacion_padre,id_usuario, id_usuario_ultima_modificacion, fecha_ultima_modificacion,";
            $consulta .= "prioridad, id_cliente, id_estatus, notas_adicionales, LAB,terminos_entrega, extra, tipo_moneda, valor_moneda, iva, subtotal,";
            $consulta .= "total, idioma, es_version, atencion, referencia, vigencia, fecha_version, id_version, DATE(enviada_cliente_en_fecha) as enviada_cliente_en_fecha, con_iva, id_contacto ";
            $consulta .= "FROM Cotizaciones ";
            $consulta .= "WHERE id=$id AND id_version=$idVersion";

            $resultado = mysql_query($consulta) or print("Objeto: Cotizacion, funcion get, Consulta: $consulta " . mysql_error());
            if (@mysql_num_rows($resultado) >= 1)
            {
                $res = mysql_fetch_assoc($resultado);
                $this->id = $res['id'];
                $this->id_cotizacion_padre = $res['id_cotizacion_padre'];
                $this->id_usuario = $res['id_usuario'];
                $this->id_usuario_ultima_modificacion = $res['id_usuario_ultima_modificacion'];
                $this->fecha_ultima_modificacion = $res['fecha_ultima_modificacion'];
                $this->prioridad = $res['prioridad'];
                $this->id_cliente = $res['id_cliente'];
                $this->id_contacto = $res['id_contacto'];
                $this->id_estatus = $res['id_estatus'];
                $this->notas_adicionales = $res['notas_adicionales'];
                $this->LAB = stripslashes($res['LAB']);
                $this->terminos_entrega = stripslashes($res['terminos_entrega']);
                $this->extra = stripslashes($res['extra']);
                $this->tipo_moneda = $res['tipo_moneda'];
                $this->valor_moneda = $res['valor_moneda'];
                $this->iva = $res['iva'];
                $this->subtotal = $res['subtotal'];
                $this->total = $res['total'];
                $this->idioma = $res['idioma'];
                $this->es_version = $res['es_version'];
                $this->atencion = $res['atencion'];
                $this->referencia = $res['referencia'];
                $this->vigencia = $res['vigencia'];
                $this->fecha_version = $res['fecha_version'];
                $this->id_version = $res['id_version'];

                if ($res['enviada_cliente_en_fecha'] != "0000-00-00" || $res['enviada_cliente_en_fecha'] != "" || !is_null($res['enviada_cliente_en_fecha']))
                {
                    $this->enviada_cliente_en_fecha = $res['enviada_cliente_en_fecha'];
                }
                
			    $this->con_iva = $res['con_iva'];
                $this->archivos = $this->getArchivos();

                return true;
            }
            else
            {
                return false;
            }
            
        }

        public function update($cotizacion)
        {
            $_SESSION['prueba']=$cotizacion;
            $iva_total = $this->checkIva($cotizacion->con_iva, $cotizacion->iva);
            //".$_SESSION['totalreal']."
            //".$_SESSION['subreal']."
            //".$_SESSION['cotizacion']->subtotal."
            $cotizacion->iva = $iva_total['iva'];
            $cotizacion->total += $iva_total['total'];
            $cotizacion->subtotal = $_SESSION['cotizacion']->subtotal;
            $cotizacion->total = $_SESSION['cotizacion']->total;
            $cotizacion->iva = $_SESSION['cotizacion']->iva;
            $consulta = "UPDATE Cotizaciones SET id=$cotizacion->id,id_usuario_ultima_modificacion=$cotizacion->id_usuario,";
            $consulta .= "prioridad='$cotizacion->prioridad',id_cliente = '$cotizacion->id_cliente',id_contacto='$cotizacion->id_contacto',";
            $consulta .= "notas_adicionales='$cotizacion->notas_adicionales',LAB='$cotizacion->LAB',terminos_entrega='$cotizacion->terminos_entrega',extra='$cotizacion->extra',";
            $consulta .= "tipo_moneda='$cotizacion->tipo_moneda',valor_moneda='$cotizacion->valor_moneda',iva='$cotizacion->iva',";
            $consulta .= "total='$cotizacion->total',subtotal='$cotizacion->subtotal',idioma='$cotizacion->idioma',id_estatus='$cotizacion->id_estatus',";
            $consulta .= "es_version='$cotizacion->es_version',enviada_cliente_en_fecha='$cotizacion->enviada_cliente_en_fecha',atencion='$cotizacion->atencion',";
            $consulta .= "referencia='$cotizacion->referencia',vigencia='$cotizacion->vigencia',fecha_version='$cotizacion->fecha_version',";
            $consulta .= "id_version=$cotizacion->id_version,con_iva='$cotizacion->con_iva',fecha_ultima_modificacion = DEFAULT ";
            $consulta .= "WHERE id=$this->id AND id_version=$this->id_version";

            $resultado = mysql_query($consulta) or print("No se ha podido actualizar  objeto Cotizacion, funcion update, consulta: <br> $consulta<br> " . mysql_error());

            if ($resultado) 
            {
                $this->id = $cotizacion->id;
                $this->id_usuario = $cotizacion->id_usuario;
                $this->id_usuario_ultima_modificacion = $cotizacion->id_usuario_ultima_modificacion;
                $this->fecha_ultima_modificacion = $cotizacion->fecha_ultima_modificacion;
                $this->prioridad = $cotizacion->prioridad;
                $this->id_cliente = $cotizacion->id_cliente;
                $this->id_contacto = $cotizacion->id_contacto;
                $this->notas_adicionales = $cotizacion->notas_adicionales;
                $this->LAB = $cotizacion->LAB;
                $this->terminos_entrega = $cotizacion->terminos_entrega;
                $this->extra = $cotizacion->extra;
                $this->tipo_moneda = $cotizacion->tipo_moneda;
                $this->valor_moneda = $cotizacion->valor_moneda;
                $this->iva = $cotizacion->iva;
                $this->subtotal = $cotizacion->subtotal;
                $this->total = $cotizacion->total;
                $this->idioma = $cotizacion->idioma;
                $this->es_version = $cotizacion->es_version;
                $this->atencion = $cotizacion->atencion;
                $this->referencia = $cotizacion->referencia;
                $this->vigencia = $cotizacion->vigencia;
                $this->fecha_version = $cotizacion->fecha_version;
                $this->id_version = $cotizacion->id_version;
                $this->con_iva = $cotizacion->con_iva;
            }

            return $resultado; //== 1 ? true : false;
        }

    private function checkIva($conIva, $iva) {
        $res = array();
        if ($conIva == 1) {
            $res['iva'] = floatval($iva);
            $res['total'] = 0;
        } else {
            $res['iva'] = 0;
            $res['total'] = -floatval($iva);
        }
        return $res;
    }

    /*
     * No agrega productos, sólo inserta la cotización
     */

    public function create($cotizacion) {
        $cotizacion->id = $this->getNextId();
        $iva_total = $this->checkIva($cotizacion->con_iva, $cotizacion->iva);
        $cotizacion->iva = $iva_total['iva'];
        $cotizacion->total += $iva_total['total'];
        if ($cotizacion->id_estatus == '' || !isset($cotizacion->id_estatus))
            $cotizacion->id_estatus = 1; //Estatus Borrador
		
		/*if($cotizacion->con_iva=='')
			$cotizacion->con_iva=0;
		else*/
		///////////////////////////////
		/////////////////////////////
		///////////////////////////
		/////////////////////////	
		
        $consulta = "INSERT INTO Cotizaciones( id, id_cotizacion_padre, 
            id_usuario, id_usuario_ultima_modificacion, fecha_ultima_modificacion,
            prioridad, id_cliente, id_estatus, notas_adicionales, LAB, 
            terminos_entrega, extra, tipo_moneda, valor_moneda, iva, total, subtotal, idioma, es_version,
            atencion, referencia, vigencia, fecha_version, id_version, con_iva, fecha_creacion, id_contacto)
            VALUES( $cotizacion->id, ' $cotizacion->id_cotizacion_padre', 
        '$cotizacion->id_usuario', '$cotizacion->id_usuario_ultima_modificacion', DEFAULT, 
        '$cotizacion->prioridad', 
        '$cotizacion->id_cliente', '$cotizacion->id_estatus', '$cotizacion->notas_adicionales', 
        '$cotizacion->LAB', '$cotizacion->terminos_entrega', '$cotizacion->extra',
        '$cotizacion->tipo_moneda', '$cotizacion->valor_moneda', '$cotizacion->iva',
        '$cotizacion->total', '$cotizacion->subtotal', '$cotizacion->idioma','$cotizacion->es_version',
        '$cotizacion->atencion', '$cotizacion->referencia', '$cotizacion->vigencia',
        '$cotizacion->fecha_version' , '$cotizacion->id_version', 1, now(),  '$cotizacion->id_contacto')";

        $resultado = mysql_query($consulta) or print("Creacion no exitosa <br>$consulta<br>" . mysql_error());

        if ($resultado)
            $this->get($cotizacion->id, $cotizacion->id_version);

        return $resultado == 1 ? true : false;
    }

    public function delete() {
        $query = "SELECT id_version FROM Cotizaciones WHERE id = $this->id";
        $result = mysql_query($query) or print("ERROR $query <BR>" . mysql_error());

        while ($cotizacion = mysql_fetch_assoc($result)) {
            $this->deleteOneVersion($cotizacion['id_version']);
        }
        if ($result) {
            /*print("<script>alert('Borrada con exito');</script>");*/
            if ($this->deleteAllComentarios())
                $return = true;
        } else {
            $return = false;
        }
    }

    public function deleteOneVersion($id_version = "") {
        if ($id_version == "")
            $id_version = $this->id_version;
        if ($this->deleteAllProductsOfThisVersion($id_version)) {
            $consulta = "DELETE FROM Cotizaciones WHERE id = $this->id AND id_version = $id_version";
            return mysql_query($consulta) or print("<h1>Eliminado no exitoso de " . $this->id . "</h1><br>" . mysql_error());
        } return false;
    }

    private function deleteAllProductsOfThisVersion($id_version = "") {
        if ($id_version == "")
            $id_version = $this->id_version;
        $query = "DELETE FROM Cotizaciones_Productos WHERE id_cotizacion = $this->id AND id_version = $id_version";
        return mysql_query($query) or print( "ERROR!!: " . mysql_error());
    }

    public function updateProducto($producto) {
		$producto->comentario=str_replace("\\\\","\\",$producto->comentario);
        $consulta = "
            UPDATE Cotizaciones_Productos SET 
                fecha_entrega = DEFAULT, 
                comentario = '$producto->comentario', 
                descuento = '$producto->descuento', 
                cantidad = '$producto->cantidad',
                precio = '$producto->precio', 
                recargo = '$producto->recargo',
                partida = '$producto->partida'
            WHERE  id_producto = $producto->id AND id_cotizacion = $this->id AND id_version = $this->id_version";


        $resultado = mysql_query($consulta) or print(" || Relacion no exitosa <br>$consulta<br>" . mysql_error());

        if ($resultado) {
            foreach ($this->productos as $n => $product) {
                if ($product->id == $producto->id)
                    $product = $producto;
            }
        }
    }

        public function updateProductos($productos)
        {

            /* AQUI NO SE DUPLICAN LOS PRODUCTOS, REVISADO */
            $this->productos = array();
            if ($this->deleteAllProductsOfThisVersion()) {
                foreach ($productos as $n => $producto) {
                    if ($this->addProducto($producto))
                        array_push($this->productos, $producto);
                }
                return TRUE;
            } else
                return FALSE;
        }

    public function addProducto($producto) {
        $consulta = "INSERT INTO Cotizaciones_Productos
            ( id_version, id_cotizacion, id_producto, fecha_entrega, comentario, descuento, cantidad, precio, recargo, partida )
            VALUES
            ( $this->id_version, '$this->id', '$producto->id',DEFAULT,'$producto->comentario', '$producto->descuento',
                '$producto->cantidad', '$producto->precio', '$producto->recargo', '$producto->partida')";
        $resultado = mysql_query($consulta) or print("Relacion no exitosa <br>$consulta<br>" . mysql_error());

        if ($resultado)
            return true;
        else
            return false;
    }

    public function getEstatus() {
        $query = "SELECT * FROM EstatusCotizaciones WHERE id = $this->id_estatus";
        $result = mysql_query($query) or print(mysql_error() . "<br>$query");
        if ($result) {
            $estatus = mysql_fetch_assoc($result);
            $this->estatus = $estatus['nombre'];
            return $estatus['nombre'];
        } else
            return "Sin estatus";
    }

    public function subirArchivo($nombreCampo) {
        $return = false;
        $producto = new Producto();
        $query = "SELECT MAX(id) FROM Archivos_Cotizaciones WHERE id_cotizacion = " . $_SESSION['cotizacion']->id;
        $result = mysql_query($query) or print("ERROR al agregar el archvio<br>" . mysql_error() . "<br>$query");

        if ($result) {
            $idArchivo = mysql_fetch_row($result);
            $idArchivo = intval($idArchivo[0]) + 1;
            $nombreArchivoSistema = 'ArchivoCotizacion' . $_SESSION['cotizacion']->id . "_$idArchivo";

            $nombreArchivo = $_FILES[$nombreCampo]['name'];

            $archivo_location = $producto->uploadFile($nombreArchivoSistema, 'archivos', $nombreCampo);
            if ($archivo_location) {
                $query = "INSERT INTO Archivos_Cotizaciones(id, id_cotizacion, nombre_real, location) 
                    VALUES ( $idArchivo , " . $_SESSION['cotizacion']->id . " , '$nombreArchivo' , '$archivo_location')";
                $result = mysql_query($query) or print("ERROR al agregar el archvo<br>" . mysql_error() . "<br>$query");
                $return = true;
            } else
                return FALSE;
        }
        $this->archivos = $this->getArchivos();

        return $return;
    }

    public function addExistingFilesFromFather() {
        foreach ($this->archivos as $n => $archivo) {
            $query = "INSERT INTO Archivos_Cotizaciones(id, id_cotizacion, nombre_real, location) 
                VALUES ( " . $archivo['id'] . " , $this->id , '" . $archivo['nombre_real'] . "' , '" . $archivo['location'] . "')";
            $result = mysql_query($query) or exit("ERROR al agregar el archvo<br>" . mysql_error() . "<br>$query");
        }
        if ($result)
            return true;
        else
            return false;
    }

        //Obtiene productos de la cotizacion
        public function setCarritoFromCotizacion()
        {
            include_once 'functions_agregar_carrito.php';
            unset($_SESSION['carrito']);
            $query = "SELECT * FROM Cotizaciones_Productos WHERE id_cotizacion = $this->id AND id_version = $this->id_version ORDER BY partida";
            $result = mysql_query($query);

            while ($productoCotizacion = mysql_fetch_assoc($result))
            {
                $producto = new Producto();
                $producto->get($productoCotizacion['id_producto']);
                $productoCotizacion['comentario']=str_replace("\\","",$productoCotizacion['comentario']);
                $producto->idCotizaciones_Productos = $productoCotizacion['id'];
                $producto->partida = intval($productoCotizacion['partida']);
                $producto->comentario = $productoCotizacion['comentario'];
                $producto->descuento = $productoCotizacion['descuento'];
                $producto->cantidad = $productoCotizacion['cantidad'];
                $producto->precio = $productoCotizacion['precio'];
                $producto->recargo = $productoCotizacion['recargo'];
                $producto->nombre_proveedor = getNombreProveedor($producto->id_proveedor);

                $producto->agregarACarrito();
                array_push($this->productos, $producto);
            }
        }

    public function setCarritoFromCotizacionVersion() {
        include_once 'functions_agregar_carrito.php';
        $query = "SELECT * FROM Cotizaciones_Productos WHERE id_cotizacion = $this->id AND id_version = $this->id_version ORDER BY partida";
        $result = mysql_query($query);

        while ($productoCotizacion = mysql_fetch_assoc($result)) {
            $producto = new Producto();
            $producto->get($productoCotizacion['id_producto']);
			$productoCotizacion['comentario']=str_replace("\\","",$productoCotizacion['comentario']);
            $producto->idCotizaciones_Productos = $productoCotizacion['id'];
            $producto->partida = intval($productoCotizacion['partida']);
            $producto->comentario = $productoCotizacion['comentario'];
            $producto->descuento = $productoCotizacion['descuento'];
            $producto->cantidad = $productoCotizacion['cantidad'];
            $producto->precio = $productoCotizacion['precio'];
            $producto->recargo = $productoCotizacion['recargo'];
            $producto->nombre_proveedor = getNombreProveedor($producto->id_proveedor);

            $producto->agregarACarrito();
            array_push($this->productos, $producto);
        }
        $_SESSION['carritoVersion'] = $this->productos;
    }

    public function getArchivos() {
        $query = "SELECT id, nombre_real, location FROM Archivos_Cotizaciones WHERE id_cotizacion = $this->id";
        $result = mysql_query($query) or print("Archivo no encontrado");
        $return = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($return, $row);
        }
        return $return;
    }


    public function borrarArchivo($archivo) {

        if (file_exists($archivo['location'])) {
            if (unlink($archivo['location'])) {

                $query = "DELETE FROM Archivos_Cotizaciones WHERE id = " . $archivo['id'] . " AND id_cotizacion =  $this->id";
                $result = mysql_query($query) or print("ERROR al eliminar el archvo<br>" . mysql_error() . "<br>$query");

                if ($result)
                    return TRUE;
                else {
                    ?><script>alert('Eliminado no exitoso');</script><?
                    return FALSE;
                }
            } else
                return FALSE;
        } else {
            $query = "DELETE FROM Archivos_Cotizaciones WHERE id = " . $archivo['id'] . " AND id_cotizacion =  $this->id";
            $result = mysql_query($query) or print("ERROR al eliminar el archvo<br>" . mysql_error() . "<br>$query");
            return TRUE;
        }
    }

    /*
     * @param Contacto $toContacto ;
     * @param Usuario $sender ;
     * @param Cliente $cliente ;
     */


    public function notificarCliente($cliente, $sender, $subject, $extraMessage, $toContacto, $cc = '', $archivo, $footer = "", $enviarCotEnCorreo = TRUE) {

        include_once 'mailCotizacion.php';
        $this->getEstatus();
        $subject = "Herramientas AG Cotizacion #$this->id: $subject";
        $extraMessage = str_replace("\n", "<br>", $extraMessage);
        $from = " $sender->nombre <" . $sender->email . ">";

        $banner = "<img src=\"" . getRutaMail() . getRutaBannerMail() . "\"  alt=\"banner\">";
        if ($enviarCotEnCorreo)
            $message = " $extraMessage <br/>$footer $banner <br/><br/><br/><br/>------<h1>COTIZACIÓN #$this->id</h1><br/>" .
                    getBodyCotizacion($this, $this->productos, $cliente, true);
        else
            $message = " $extraMessage  <br/>$footer $banner";

        $headers = "From: $from";
        $headers .= "\r\nCC: $cc";

        /* PREPARANDO HEADERS PARA ACEPTAR ACHIVOS ADJUNTOS */
        // boundary 
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        // headers for attachment 
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
        // multipart boundary 
        $mail = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"utf-8\"\n" .
                "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";


        /* AGREGANDO PDF DE LA COTIZACION AL CORREO */
        $mail.= $this->getHeaderForAttachFileLocation("pdfs/" . $archivo, $mime_boundary, $archivo);


        /* AGREGANDO ARCHIVOS AGREGADOS A LA COTIZACION AL CORREO */
        if (count($this->archivos) > 0) {
            foreach ($this->archivos as $file) {
                $mail.= $this->getHeaderForAttachFileLocation($file['location'], $mime_boundary, $file['nombre_real']);
            }
        }

        /* AGREGANDO FICHAS TECNICAS AL CORREO */
        if (count($this->productos) > 0) {
            foreach ($this->productos as $producto) {
                if ($producto->archivo_ficha_tecnica != "")
                    $mail.= $this->getHeaderForAttachFileLocation($producto->archivo_ficha_tecnica, $mime_boundary, $producto->codigo_interno != "" ? $producto->codigo_interno . "_ficha_tecnica.pdf" : $producto->codigo . "_ficha_tecnica.pdf");
            }
        }

        //end preparing attachments

        $mail .= "--{$mime_boundary}--";
        $returnpath = "-f" . $sender->email;
        $ok = @mail($toContacto->email_contacto, $subject, $mail, $headers, $returnpath);
        if ($ok)
            $this->saveLogMail($cliente, $sender, mysql_real_escape_string($subject), mysql_real_escape_string($message), $toContacto, $cc);

        return $ok;
    }


    private function getHeaderForAttachFileLocation($file_location, $mime_boundary, $nameArchive = FALSE) {
        if (!$nameArchive)
            $nameArchive = basename($file_location);
        $message = "";
        if (is_file($file_location)) {
            $message .= "--{$mime_boundary}\n";
            $fp = @fopen($file_location, "rb");
            $data = @fread($fp, filesize($file_location));
            @fclose($fp);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: application/octet-stream; name=\"" . basename($file_location) . "\"\n" .
                    "Content-Description: " . basename($file_location) . "\n" .
                    "Content-Disposition: attachment;\n" . " filename=\"" . $nameArchive . "\"; size=" . filesize($file_location) . ";\n" .
                    "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
        }
        return $message;
    }


    private function saveLogMail($cliente, $sender, $subject, $extraMessage, $toContacto, $cc = "") {
        include_once 'Usuario.php';
        include_once 'Cliente.php';
        include_once 'Contacto.php';

        $query = "INSERT INTO correo_enviado (
            id, id_contacto, id_cliente, id_usuario, 
            id_cotizacion, id_version_cotizacion,
            asunto, mensaje, destinatarios_adicionales, fecha) 
            VALUES (DEFAULT, '$toContacto->id','$cliente->id',
                '$sender->id', '$this->id','$this->id_version',
                '$subject', '$extraMessage', '$cc', '$this->enviada_cliente_en_fecha')";
        $result = mysql_query($query) or print("ERROR guardando en DB en saveLogMail<br> $query<br>" . mysql_error());

        return $result ? true : false;
    }


    public function notificarUsuario($cliente, $sender, $subject, $extraMessage, $toUsuario, $asuntoSupervisor = '') {

        include_once 'mailCotizacion.php';
        $this->getEstatus();
        $subject = "$asuntoSupervisor <" . $this->getEstatus() . "> $subject";
        // email fields: to, from, subject, and so on
        $from = " $sender->nombre <" . $sender->email . ">";
        $message = "<h3>$extraMessage</h3>";
        $message .= getBodyCotizacion($this, $this->productos, $cliente, $esParaCliente = false);
        $headers = "From: $from";

        // boundary 
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        // headers for attachment 
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

        // multipart boundary 
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
                "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

        // preparing attachments
        if (count($this->archivos) > 0) {
            foreach ($this->archivos as $file) {
                if (is_file($file['location'])) {
                    $message .= "--{$mime_boundary}\n";
                    $fp = @fopen($file['location'], "rb");
                    $data = @fread($fp, filesize($file['location']));
                    @fclose($fp);
                    $data = chunk_split(base64_encode($data));
                    $message .= "Content-Type: application/octet-stream; name=\"" . basename($file['location']) . "\"\n" .
                            "Content-Description: " . basename($file['location']) . "\n" .
                            "Content-Disposition: attachment;\n" . " filename=\"" . basename($file['location']) . "\"; size=" . filesize($file['location']) . ";\n" .
                            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                }
            }
        }

        $message .= "--{$mime_boundary}--";
        $returnpath = "-f" . $sender->email;
        $ok = @mail($toUsuario->email, $subject, $message, $headers, $returnpath);
        return $ok;
    }


    private function getNextIdVersion() {
        $query = "SELECT ( MAX(id_version) + 1 ) AS nextIdVersion FROM Cotizaciones WHERE id = $this->id";
        $result = mysql_query($query)
                or print ('ERROR getting getNextIdVersion()<br>$query<br>' . mysql_error());
        $result = mysql_fetch_array($result);
        return intval($result['nextIdVersion']);
    }


    private static function getNextId() { //REVISADO 
        $query = "SELECT ( MAX(id) + 1 ) AS nextIdVersion FROM Cotizaciones";
        $result = mysql_query($query) or print ('ERROR getting getNextId()<br>$query<br>' . mysql_error());
        $result = mysql_fetch_array($result);
        return intval($result['nextIdVersion']);
    }

    public function createVersion() {
        /* Asignar por valor la cotizacion actual */
        $newCotizacion = clone $this;

        /* 1. actualizar la cotizacion actual 
         * para que sea version (ponerle fecha y que es version); */
        date_default_timezone_set('America/Chihuahua');
        $this->fecha_version = date('Y-m-d H:i:s');
        $this->es_version = true;

        if ($this->update($this)) {
            /* 2. crear la nueva version de la cotizacion
             * (que no tenga fecha y que no es version); */

            $newCotizacion->fecha_version = NULL;
            $newCotizacion->es_version = FALSE;
            $newCotizacion->id_version = $newCotizacion->getNextIdVersion();

            if ($newCotizacion->create($newCotizacion)) {
                /* 3. Actualizar id nueva version 
                 * porque fue aumentado en el create()
                 */
                $cotizacionToUpdate = new Cotizacion();
                $cotizacionToUpdate->get($newCotizacion->id, $newCotizacion->id_version);
                $cotizacionToUpdate->id = $this->id;

                if ($newCotizacion->update($cotizacionToUpdate)) {//ACTUALIZANDO ID
                    $newCotizacion->updateProductos($this->productos);
                }
                /*
                 * NOTA BUG: Si el error de que la cantidad de algunos productos aparezca en ceros
                 * no es en la Base de Datos, los productos se insertan correctamente.
                 * Más bien buscar en $_SESSION['carrito'], etc.
                 */
                $newCotizacion->get($newCotizacion->id, $newCotizacion->id_version);
                $newCotizacion->archivos = $this->archivos;
                $_SESSION['cotizacion'] = $newCotizacion;
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            ?><script>alert('ERROR: Update de Cotizacion Anterior');</script><?
            return FALSE;
        }
    }


    public function getChildren() {

        $consulta = "SELECT Cotizaciones.id, CONCAT(nombre_empresa,' (' ,nombre_contacto,')') AS nombre_cliente, 
                        Cotizaciones.total, Usuarios.nombre AS usuarioAsignado, 
                        EstatusCotizaciones.nombre AS estatus, EstatusCotizaciones.id AS id_estatus,
                        DATE_FORMAT(Cotizaciones.fecha_version, '%e %b %Y - %l:%i %p') as fecha_version, 
                        Cotizaciones.id_version
                    FROM Cotizaciones
                    LEFT OUTER JOIN Clientes ON Cotizaciones.id_cliente = Clientes.id
                    LEFT OUTER JOIN Usuarios ON Cotizaciones.id_usuario = Usuarios.id
                    LEFT OUTER JOIN EstatusCotizaciones ON EstatusCotizaciones.id = Cotizaciones.id_estatus
                    WHERE Cotizaciones.id = $this->id
                    GROUP BY Cotizaciones.id_version
                    ORDER BY Cotizaciones.fecha_version DESC";

        $resultado = mysql_query($consulta) or print("Error en buscador Children: <Br> $consulta <br> " . mysql_error());
        /* @var $resultado ArrayObject */
        while ($res = mysql_fetch_array($resultado, MYSQL_ASSOC)) {
            ?> 
            <tr>
                <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id']; ?>,<? echo $res['id_version']; ?>);" class="texto_info_negro"><? echo $res['id_version']; ?></a></div></td>
                <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id']; ?>,<? echo $res['id_version']; ?>);" class="texto_info_negro"><? echo $res['fecha_version'] == "" ? 'version actual' : $res['fecha_version']; ?></a></div></td>
                <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id']; ?>,<? echo $res['id_version']; ?>);" class="texto_info_negro"><? echo $res['nombre_cliente']; ?></a></div></td>
                <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a  href="javascript:editarCotizacion(<? echo $res['id']; ?>,<? echo $res['id_version']; ?>);"class="texto_info_negro">$<? echo round($res['total'], 2); ?></a></div></td>
                <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id']; ?>,<? echo $res['id_version']; ?>);" class="texto_info_negro"><? echo $res['estatus']; ?></a></div></td>
                <td class="texto_info_negro"><div align="center" class="texto_info_negro"><a href="javascript:editarCotizacion(<? echo $res['id']; ?>,<? echo $res['id_version']; ?>);" class="texto_info_negro"></div>
                <td class="texto_info_negro"><div align="center">
            <? if ($res['fecha_version'] != "") { ?>
                            <a href="generar_cotizacion.php?idVersion=<? echo $res['id_version']; ?>&idCotizacionEditar=<? echo $res['id']; ?>&borrar=true&onlyVersion=true" onclick="return borrar('<? echo $res['id']; ?>');" 
                               onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Imag22','','images/cerrar_r.jpg',1)" > 
                                <img src="images/cerrar.jpg" alt="" name="Imag22" width="17" height="16" border="0" id="Imag22" /> 
                            </a><? } ?>
                    </div></td>
                <?
                if ($res['usuarioAsignado'] != "")
                    echo $res['usuarioAsignado'];
                else {
                    ?>Sin Asignar<? } ?>
            </a></div></td>
            <td class="texto_info_negro"></td>
            </tr>
            <?
        }
    }


    public static function getComentario($id) {
        $query = "SELECT id, id_cotizacion, id_usuario, descripcion, fecha FROM Log_Cotizaciones
            WHERE id = $id";
        $result = mysql_query($query) or print($query);

        if ($result) {
            $comentario = mysql_fetch_assoc($result);
            return $comentario;
        } else
            return false;
    }


    public static function updateComentario($descripcion, $id) {
        $query = "UPDATE Log_Cotizaciones SET descripcion = '" . mysql_real_escape_string($descripcion) . "'
            WHERE id = $id";
        $result = mysql_query($query) or print($query);
        if ($result)
            return true;
        else
            return false;
    }


    public static function deleteComentario($id) {
        $query = "DELETE FROM Log_Cotizaciones WHERE id = $id";
        $result = mysql_query($query) or print('ERROR deleteComentario ' . $query);
        if ($result)
            return true;
        else
            return false;
    }


    private function deleteAllComentarios() {
        $query = "DELETE FROM Log_Cotizaciones WHERE id_cotizacion = $this->id";
        $result = mysql_query($query) or print('ERROR deleteAllComentarios ' . $query);
        if ($result)
            return true;
        else
            return false;
    }

    /*
     * @param mixed $descripcion
     * @param int $id_usuario
     * @return bool
     */


    public function createComentario($descripcion, $id_usuario) {
        $query = "INSERT INTO Log_Cotizaciones(id, id_cotizacion, id_usuario, descripcion, fecha)
            VALUES  (DEFAULT, " . $this->id . ", " . $id_usuario . ", '$descripcion', DEFAULT)";
        $result = mysql_query($query) or print('ERROR <br>$query<br>' . mysql_error());
        if ($result)
            return true;
        else
            return false;
    }

    public function getComentarios() {
        $query = "SELECT id, id_cotizacion, id_usuario, descripcion, fecha FROM Log_Cotizaciones
            WHERE id_cotizacion = $this->id ORDER BY fecha DESC";
        $result = mysql_query($query) or print($query);
        $comentarios = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($comentarios, $row);
        }
        return $comentarios;
    }


    public function getTareas() {
        $query = "SELECT id, id_cotizacion, id_usuario, 
            fecha_inicio, fecha_vencimiento, fecha_recordatorio, 
            fecha_inicio, DATE_FORMAT(fecha_inicio, '%e %b %Y') as fecha_inicio_user_friendly,
            fecha_vencimiento, DATE_FORMAT(fecha_vencimiento, '%e %b %Y') as fecha_vencimiento_user_friendly,
            fecha_recordatorio, DATE_FORMAT(fecha_recordatorio, '%e %b %Y') as fecha_recordatorio_user_friendly,
            completada, descripcion , asunto
            FROM Tareas
            WHERE id_cotizacion = $this->id ORDER BY fecha_vencimiento DESC";

        $result = mysql_query($query) or print($query);
        $tareas = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($tareas, $row);
        }
        return $tareas;
    }


    public static function getLeyenda() {
        $query = "SELECT * FROM LeyendaCotizaciones";
        $result = mysql_query($query) or print($query);
        $result = mysql_fetch_row($result);
        return $result[0];
    }


    public function correosEnviados() {
        $query = "SELECT correo_enviado.* ,
            Usuarios.nombre AS de, Contactos.nombre_contacto AS para
            FROM correo_enviado
            LEFT OUTER JOIN Usuarios ON Usuarios.id = correo_enviado.id_usuario
            LEFT OUTER JOIN Contactos ON Contactos.id = correo_enviado.id_contacto
            WHERE id_cotizacion = $this->id";
        $result = mysql_query($query) or print("ERROR correos Enviados<br>$query<br>" . mysql_error());
        $return = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($return, $row);
        }
        return $return;
    }

}
?>