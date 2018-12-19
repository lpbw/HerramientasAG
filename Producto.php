<?
    include_once 'UploadFiles.php';
    class Producto extends UploadFiles
    {
        public $nombre;
        public $id;
        public $id_catalogo_productos;
        public $precio;
        public $costo;
        public $id_proveedor;
        public $modificado;
        public $archivo_ficha_tecnica;
        public $imagen;
        public $descripcion;
        public $codigo_familia;
        public $codigo_microsip;
        public $unidad_metrica;
        public $tipo_moneda_usa;
        public $origen;
        public $exportar_microsip;
        public $stock;
        public $stock_proveedor;
        public $codigo_descuento;
        public $codigo;
        public $codigo_interno;
        public $unidad_metrica_ingles;
        public $recargo;
        public $existencia;
        public $peso;
        public $stock_fabrica;
        public $tienda;
        public $descripcion_l;
        public $factor;
        public $flete_cliente;
        public $flete_proveedor;
        public $v_cliente;
        public $v_proveedor;
	    
        public function getExistencia()
        {
            return $this->existencia;
        }

        public function setExistencia($existencia) {
            $this->existencia = $existencia;
        }

        /**
         * Obtiene los datos del producto.
         * Tabla: Productos.
         * Datos obtenidos: nombre,id,id_catalogo_productos,precio,costo, id_proveedor, modificado, archivo_ficha_tecnica, 
         * ,imagen, descripcion, codigo_familia, codigo_microsip, 
         * ,unidad_metrica, tipo_moneda_usa, origen, exportar_microsip, stock, codigo_descuento,
         * ,codigo, codigo_interno,unidad_metrica_ingles, recargo, existencia, peso, stock_fabrica, tienda, descripcion_l, stock_proveedor, factor, flete_cliente, flete_proveedor, v_cliente, v_proveedor
         */
        public function get($id=NULL)
        {
            if(isset($id) && !empty($id))
            {
                $consulta  = "SELECT nombre,id,id_catalogo_productos,precio,costo,id_proveedor";
                $consulta .= ",modificado,archivo_ficha_tecnica, imagen, descripcion, codigo_familia";
                $consulta .= ", codigo_microsip, unidad_metrica, tipo_moneda_usa, origen, exportar_microsip";
                $consulta .= ", stock, codigo_descuento,codigo, codigo_interno,unidad_metrica_ingles";
                $consulta .= ", recargo, existencia, peso, stock_fabrica, tienda";
                $consulta .= ", descripcion_l, stock_proveedor, factor, flete_cliente, flete_proveedor";
                $consulta .= ", v_cliente, v_proveedor ";
                $consulta .= "FROM Productos ";
                $consulta .= "WHERE id =$id";
        
                $resultado = mysql_query($consulta) or print("La consulta en funcion get en el objeto Producto fallo: " . mysql_error());
                if(@mysql_num_rows($resultado)>=1)
                {
                    $res=mysql_fetch_assoc($resultado);
                    $this->nombre =  strtoupper($res['nombre']);
                    $this->id=$res['id'];
                    $this->id_catalogo_productos=$res['id_catalogo_productos'];
                    $this->precio=$res['precio'];
                    $this->costo=$res['costo'];
                    $this->id_proveedor=$res['id_proveedor'];
                    $this->modificado=$res['modificado'];
                    $this->archivo_ficha_tecnica=$res['archivo_ficha_tecnica'];
                    $this->imagen=$res['imagen'];
                    $this->descripcion=strtoupper($res['descripcion']);
                    $this->unidad_metrica=$res['unidad_metrica'];
                    $this->tipo_moneda_usa=$res['tipo_moneda_usa'];
                    $this->origen = $res['origen'];
                    $this->exportar_microsip = $res['exportar_microsip'];
                    $this->stock = $res['stock'];
                    $this->stock_proveedor = $res['stock_proveedor'];
                    $this->codigo_familia=$res['codigo_familia'];
                    $this->codigo_microsip=$res['codigo_microsip'];
                    $this->codigo_descuento = $res['codigo_descuento'];
                    $this->codigo = $res['codigo'];
                    $this->existencia = $res['existencia'];
                    $this->unidad_metrica_ingles = $res['unidad_metrica_ingles'];
                    $this->recargo = $res['recargo'];
                    $this->peso = $res['peso'];
                    $this->stock_fabrica = $res['stock_fabrica'];
                    $this->tienda = $res['tienda'];
                    $this->descripcion_l = $res['descripcion_l'];
                    $this->factor = $res['factor'];
                    $this->flete_cliente = $res['flete_cliente'];
                    $this->flete_proveedor = $res['flete_proveedor'];
                    $this->v_cliente = $res['v_cliente'];
                    $this->v_proveedor = $res['v_proveedor'];

                    if($res['codigo_interno']=="")
                    {
                        $this->constructCodigoInterno();
                    } 
                    else
                    {
                        $this->codigo_interno = $res['codigo_interno'];
                    }     
                }   
            }
        }
    
        public function update($nombre,$id,$id_catalogo_productos,$precio,$costo,$id_proveedor,$modificado,$archivo_ficha_tecnica,$imagen,$descripcion, $codigo_familia, $unidad_metrica, $exportar_microsip=0, $stock="", $codigo_descuento="", $tipo_moneda_usa="",$unidad_metrica_ingles="", $recargo=0,$esProductoEspecial = FALSE, $peso, $stock_fabrica, $tienda,$descripcion_l, $stock_proveedor, $factor, $flete_cliente, $flete_proveedor, $v_cliente, $v_proveedor)
        {
            $nombre = strtoupper($nombre);
            $nombre1 = addslashes($nombre);
            $descripcion = strtoupper($descripcion);
            $descripcion1 = addslashes($descripcion);
            if($imagen!="")
            {
                $imagen = "imagen = '".$this->resizeImageAndUpload('imagenProducto_'.$this->id,'archivos','imagen',400)."',";
                $this->resizeImageAndUpload('ch_imagenProducto_'.$this->id,'archivos','imagen',92);
            } 
            else
            {
                $imagen = "";
            } 
                
            if($archivo_ficha_tecnica!="")
            {
                $archivo_ficha_tecnica = "archivo_ficha_tecnica = '".$this->uploadFile('FichaTecnica_'.$this->id,'archivos','archivo_ficha_tecnica')."',";
            }
            else
            {
                $archivo_ficha_tecnica = "";
            } 
            
            $costo_precio = "";
            if($costo!="" || $costo !=0)
            {
                $costo_precio .="costo = '$costo',";
            }
            if($tipo_moneda_usa!="")
            {
                $tipo_moneda_usa_query = "tipo_moneda_usa = $tipo_moneda_usa,";
            }
           
            $consulta = "UPDATE Productos SET "; 
            $consulta .= "nombre = '$nombre1',";
            $consulta .= "id_catalogo_productos = '$id_catalogo_productos',";
            $consulta .= $costo_precio;
            $consulta .= "id_proveedor = '$id_proveedor',";
            $consulta .= "modificado = '$modificado',";
            $consulta .= $archivo_ficha_tecnica;
            $consulta .= $imagen;
            $consulta .= $tipo_moneda_usa_query;
            $consulta .= "descripcion = '$descripcion1',";
            $consulta .= "codigo_familia = '$codigo_familia',";
            $consulta .= "unidad_metrica = '$unidad_metrica',";
            $consulta .= "exportar_microsip = '$exportar_microsip',";
            $consulta .= "stock = '$stock',";
            $consulta .= "codigo_descuento = '$codigo_descuento',";
            $consulta .= "unidad_metrica_ingles = '$unidad_metrica_ingles',";
			$consulta .= "recargo=$recargo,";
			$consulta .= "peso=$peso,";
			$consulta .= "stock_fabrica=$stock_fabrica,";
			$consulta .= "tienda=$tienda,";
			$consulta .= "descripcion_l='$descripcion_l',";
			$consulta .= "stock_proveedor=$stock_proveedor, ";
			$consulta .= "factor='$factor',";
			$consulta .= "flete_cliente='$flete_cliente',";
			$consulta .= "flete_proveedor='$flete_proveedor',";
			$consulta .= "v_cliente='$v_cliente',";
			$consulta .= "v_proveedor='$v_proveedor' ";
            $consulta .= "WHERE id=".$this->id;

            $resultado = mysql_query($consulta) or print("funcion update, objeto Producto, No se ha podido actualizar $consulta ".  mysql_error());
		
            $this->get($this->id);
            if(!($esProductoEspecial==TRUE))
            {
                //se compara con false porque en algunos casos se pasa un string
                $this->setPrecioAndFactor();
            }else
            {
                $this->updateOneAttr('precio', $precio);
            } 
            return $resultado==1?true:false;
        }

        /**
         * Create a product and upload the 'Ficha Tecnica' using uploadFile inherit from UploadFile class
         * @param string $nombre escaped string
         * @param int $id_catalogo_productos
         * @param float $precio The price of the product
         * @param float $costo The value that cost the product
         * @param int $id_proveedor Taken from a Proveedor object
         * @param bool $modificado 
         * @param string $archivo_ficha_tecnica, if not is empty it upload the file to archivos Folder
         * @param string $imagen
         * @param string $descripcion escaped string
         * @param string $codigo_microsip
         * @param string $codigo_familia
         * @param bool $exportar_microsip [optional] default = 0
         * @param float $stock
         * @param string $codigo_descuento
         * @param bool $tipo_moneda_usa [optional] default = 0
         * @param string $unidad_metrica 
         * @param string $unidad_metrica_ingles
         * @param float $recargo [optional] default = 0
         * @return bool true on success
        */
        public function create($nombre,$id_catalogo_productos,$precio,$costo,$id_proveedor,$modificado,$archivo_ficha_tecnica,$imagen,$descripcion,$codigo, $codigo_familia,$codigo_microsip,$numero_consecutivo='',$unidad_metrica,$unidad_metrica_ingles,$origen,$tipo_moneda_usa, $codigo_descuento,$stock,$exportar_microsip=0,$existencia='',$codigo_interno='',$recargo=0,$descripcion_l, $peso, $stock_fabrica, $tienda, $stock_proveedor, $factor, $flete_cliente, $flete_proveedor, $v_cliente, $v_proveedor)
        {
            $nombre = strtoupper($nombre);
            $nombre1 = addslashes($nombre);
            $descripcion = strtoupper($descripcion);
            $descripcion1 = addslashes($descripcion);
        
            $consulta = "INSERT INTO Productos(nombre,id_catalogo_productos,precio,costo,id_proveedor,";
			$consulta .= "modificado,archivo_ficha_tecnica,imagen,descripcion,codigo,codigo_familia,codigo_microsip,";
			$consulta .= "numero_consecutivo,unidad_metrica,unidad_metrica_ingles,origen,tipo_moneda_usa,codigo_descuento,stock,";
			$consulta .= "exportar_microsip,existencia,codigo_interno,recargo,descripcion_l,peso,stock_fabrica,tienda,precio_online,";
			$consulta .= "favorito,stock_proveedor,factor,flete_cliente,flete_proveedor,v_cliente,v_proveedor) ";
            $consulta .= "VALUES('$nombre1','$id_catalogo_productos','$precio','$costo','$id_proveedor','$modificado','$archivo_ficha_tecnica',";
            $consulta .= "'$imagen','$descripcion1','','$codigo_familia','$codigo_microsip','','$unidad_metrica','$unidad_metrica_ingles','','$tipo_moneda_usa',";
            $consulta .= "'$codigo_descuento','$stock','$exportar_microsip','$existencia','$codigo_interno','$recargo','descripcion_l','peso','$stock_fabrica',";
            $consulta .= "'$tienda','','','$stock_proveedor','factor','$flete_cliente','$flete_proveedor','$v_cliente','$v_proveedor')";
        
            $resultado = mysql_query($consulta) or print("funcion create, objeto Producto, Creacion no exitosa $consulta".  mysql_error());
        
            if($resultado)
            {
                $id = mysql_insert_id();
                if($imagen!="")
                {
                    $imagen = "imagen='".$this->resizeImageAndUpload('imagenProducto_'.$id,'archivos','imagen',400)."'";
                    $this->resizeImageAndUpload('ch_imagenProducto_'.$id,'archivos','imagen',92);
                }
                else
                {
                    $imagen = "";
                } 
                    
                if($archivo_ficha_tecnica!="")
                {
                    $archivo_ficha_tecnica = "archivo_ficha_tecnica ='".$this->uploadFile('FichaTecnica_'.$id,'archivos','archivo_ficha_tecnica')."'";
                } 
                else 
                {
                    $archivo_ficha_tecnica = "";
                }
                    
                if($archivo_ficha_tecnica!="" )
                {
                    $consulta = "UPDATE Productos SET  $archivo_ficha_tecnica WHERE id=".$id;
                    $resultado = mysql_query($consulta) or print("Actualizacion de ficha tecnica no exitosa funcion create, objeto Producto : $consulta".  mysql_error());
                }

                if( $imagen !=""){
                    $consulta = "UPDATE Productos SET $imagen WHERE id=".$id;
                    $resultado = mysql_query($consulta) or print("Actualizacion de ficha tecnica no exitosa funcion create, objeto Producto : $consulta".mysql_error());
                }
            }
            $this->get($id);
            return $resultado==1?true:false;
        }
    
        public function delete()
        {
            $consulta  = "DELETE FROM Productos WHERE id=$this->id";
	        return mysql_query($consulta) or print("$consulta<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        }
    
        public function hasCotizaciones()
        {
            $consulta  = "SELECT id_cotizacion FROM Cotizaciones_Productos WHERE id_producto=$this->id";
            $result=mysql_query($consulta);
            if(mysql_num_rows($result))
            {
                return TRUE;
            } 
            else
            {
                return FALSE;
            } 
        }
        
        /**
         * Agregar producto al carrito.
         */
        public function agregarACarrito()
        {
            if(isset($_SESSION['carrito']))
            {
                array_push( $_SESSION['carrito'] , $this);
            }
            else 
            {
                $_SESSION['carrito'] = array($this);
            }
        }
    
        public function updateOneAttr($attrName, $attrValue, $idUsuario, $requireRevision = NULL, $id_cotizacion = '', $id_version_cotizacion = '')
        {
            $query = "UPDATE Productos SET $attrName = '$attrValue' WHERE id=$this->id";
            $result = mysql_query($query) or print("".  mysql_error()."<br>No se ha podido actualizar un solo atributo <br>$query");
            if($result)
            {
                if($requireRevision && $idUsuario)
                {
                    foreach ($this as $key => $value)
                    {
                        if($key == $this->attrName)
                        {
                            $actualValueToChange = $value;
                        }   
                    }
                    $this->insertNewRequestRevision($attrName, $idUsuario, $attrValue, $actualValueToChange,$id_cotizacion, $id_version_cotizacion);
                }
                $return =  TRUE;
            }
            else 
            {
                $return =  FALSE;
            }
            return $return;
        }
    
        private function insertNewRequestRevision( $attrName, $id_usuario, $proposedValue, $actualValue,$id_cotizacion, $id_version_cotizacion )
        {
            $fecha = $this->getToday();
            $query = "INSERT INTO revisionCambios_Productos( id_producto, fecha,id_cotizacion, id_version_cotizacion, id_usuario,atributo, valor_propuesto, valor_actual)";
            $query .= "VALUES ($this->id, '$fecha','$id_cotizacion', '$id_version_cotizacion', $id_usuario,'$attrName', '$proposedValue', '$actualValue')";        
            $result = mysql_query($query) or print(mysql_error()."<Br>$query");
        
            return $result==1?true:false;
        }
    
        /**
         * Obtener la fecha actual.
         */
        private function getToday()
        {
            date_default_timezone_set('America/Chihuahua');
            $today = getdate();
            $today = $today['year']."-".$this->returnNumberWithCero($today['mon'])."-".$this->returnNumberWithCero($today['mday']);
            
            return $today;
        }
        
        //Regresar el mes y el dia con 0 si es menor a 10
        private function returnNumberWithCero($number)
        {
            //si el numero es menor a 10.
            if(intval($number) < 10)
            {
                return "0".$number;
            }
            else
            {
                return $number;
            } 
        }
    
        public function setPrecioAndFactor($origen='FACTOR1')
        {
            if($this->origen!="NO_IMPORTA")
            {
                if( intval($this->id_catalogo_productos)!=0)
                {
                    $consulta  = "SELECT factor, factor2 FROM CatalogoProductos WHERE id_proveedor=".$this->id_proveedor." AND id=".$this->id_catalogo_productos;
                    $resultado = mysql_query($consulta) or print("La consulta en la funcion setPrecioAndFactor no funciono lista roles: " . mysql_error());
                    $factores = mysql_fetch_assoc($resultado);
                    $f1 = $factores['factor'];
                    $f2 = $factores['factor2'];

                    if($origen=='FACTOR1')
                    {
                        $factor = $f1;
                    }
                    else
                    {
                        $factor = $f2;
                    } 
                     
                    if(doubleval($this->costo==0))
                    {
                        $precio = 0;
                    } 
                    else
                    {
                        $precio = doubleval($factor) * doubleval($this->costo);
                    }

                    $query = "UPDATE Productos SET origen='$origen', precio='$precio' WHERE id=".$this->id;
                    $result = mysql_query($query) or print("Error actualizando el precio y origen del producto en funcion setPrecioAndFactor");

                    if($result)
                    {
                        $this->precio=$precio;
                        $this->origen=$origen;
                        return $precio;
                    }else 
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }     
            }
            else
            {
                return false;
            }   
        }
    
        public function constructCodigoInterno()
        {
            $query = "SELECT prefijo FROM Proveedores WHERE id=$this->id_proveedor";
            $result = mysql_query($query) or print(mysql_error());
            if($result)
            {
                $prefijo = mysql_fetch_assoc($result);
                $this->codigo_interno=$prefijo['prefijo'].$this->codigo;
            }
            return $this->codigo_interno;
        }
    
        public function uploadImage($campo=NULL)
        {
            if(!is_null($campo) || isset($this->id) && $_FILES[$campo]['name']!="")
            {
                $imagen = $this->resizeImageAndUpload('imagenProducto_'.$this->id,'archivos',$campo,400);
                $this->resizeImageAndUpload('ch_imagenProducto_'.$this->id,'archivos',$campo,92);
            
                $consulta = "UPDATE Productos SET imagen='$imagen' WHERE id=$this->id";

                if(mysql_query($consulta) or print("No se ha podido actualizar $consulta ".  mysql_error()))
                {
                    $this->imagen = $imagen;
                    return TRUE;
                }
                else
                {
                    return FALSE;
                } 
            } 
            else 
            {
                return FALSE;
            }
        }
    
        public function uploadFichaTecnica($campo=NULL)
        {
            if(!is_null($campo) || isset($this->id) && $_FILES[$campo]['name']!="")
            {
                $archivo_ficha_tecnica = $this->uploadFile('FichaTecnica_'.$this->id,'archivos',$campo);
                $consulta = "UPDATE Productos SET archivo_ficha_tecnica='$archivo_ficha_tecnica' WHERE id=".$this->id;

                if(mysql_query($consulta) or print("No se ha podido actualizar $consulta ".  mysql_error()))
                {
                    $this->archivo_ficha_tecnica = $archivo_ficha_tecnica;
                    return TRUE;
                }
                else 
                {
                    return FALSE;
                }
            } 
            else 
            {
                return FALSE;
            }
        }
}
?>