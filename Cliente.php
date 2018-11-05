<?
    class Cliente
    {
        public $id;
        public $id_cartera;
        public $nombre_empresa;
        public $direccion_empresa;
        public $telefono_empresa;
        public $id_estado;
        public $estado;
        public $ciudad;
        public $id_industria;
        public $industria;
        public $nombre_contacto; 
        public $email_contacto;
        public $contactos = array();
        public $rfc;
        public $condiciones_pago;
        public $moneda_usa;
        public $alias;
        public $codigo;
        public $tipo_cliente;
        public $objetivo;
	
        public function get($id)
        {
            $consulta  = "SELECT Clientes.id, id_cartera,nombre_empresa, direccion_empresa, telefono_empresa,";
            $consulta .= "nombre_contacto, email_contacto, telefono_contacto,estado,ciudad,industria,Industrias.nombre AS industria_nombre,";
            $consulta .= "Estados.nombre AS estado_nombre, contrasenia,Clientes.codigo, rfc, condiciones_pago, moneda_usa, alias,tipo_cliente, objetivo ";
            $consulta .= "FROM Clientes ";
            $consulta .= "LEFT OUTER JOIN Industrias ON Industrias.id = Clientes.industria ";
            $consulta .= "LEFT OUTER JOIN Estados ON Estados.id = Clientes.estado ";
            $consulta .= "WHERE Clientes.id=$id";
        
            $resultado = mysql_query($consulta) or print("funcion: get, objeto: cliente, consulta: $consulta " . mysql_error());
            if(@mysql_num_rows($resultado)>=1)
            {
                $res=mysql_fetch_assoc($resultado);
                $this->id=$res['id'];
                $this->id_cartera=$res['id_cartera'];
                $this->nombre_empresa=$res['nombre_empresa'];
                $this->direccion_empresa=$res['direccion_empresa'];
                $this->telefono_empresa=$res['telefono_empresa'];
                $this->nombre_contacto=$res['nombre_contacto'];
                $this->email_contacto=$res['email_contacto'];
                $this->id_estado = $res['estado'];
                $this->estado= $res['estado_nombre'];
                $this->ciudad = $res['ciudad'];
                $this->id_industria = $res['industria'];
                $this->industria = $res['industria_nombre'];
                $this->rfc  = $res['rfc'];
                $this->condiciones_pago  = $res['condiciones_pago'];
                $this->moneda_usa  = $res['moneda_usa'];
                $this->alias  = $res['alias'];
                $this->codigo  = $res['codigo'];
				$this->tipo_cliente  = $res['tipo_cliente'];
				$this->objetivo  = $res['objetivo'];
            }
        }

    public function login($user,$password){
        $pass=$_POST["pass"];
        $consulta  = "SELECT id FROM Clientes
            WHERE email_contacto='$user' and contrasenia='".md5($password)."'";
        echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto Cliente en Login: " . mysql_error());
        if(mysql_num_rows($resultado)>0){
            $res=mysql_fetch_assoc($resultado);
            $this->get($res['id']);
            //definiendo variables de accesos como variables de ambiente
            $_SESSION['accesos']=array('super'=>0,'supervisor'=>1,'administrador'=>2,
                'vendedor'=>3,'mostrador'=>4,'cliente'=>5,'todos'=>10);
            return true;
        } else {
            return false;
        }
    }
    
    
    public function update( $id, $id_cartera ,$nombre_empresa ,$direccion_empresa ,
            $telefono_empresa, 
//            $nombre_contacto, $email_contacto, $telefono_contacto,
            $id_estado, $ciudad, $id_industria, 
//            $contrasenia, 
            $rfc, $condiciones_pago, $moneda_usa, $alias, $codigo, $tipo_cliente, $objetivo){
        
        $consulta = "UPDATE  Clientes SET  `id_cartera` =  '$id_cartera',
                `nombre_empresa` =  '$nombre_empresa',
                `direccion_empresa` =  '$direccion_empresa',
                `telefono_empresa` =  '$telefono_empresa',
                `estado` =  '$id_estado',
                `ciudad` =  '$ciudad',
                `industria` =  '$id_industria',
                rfc  = '$rfc',
                condiciones_pago  = '$condiciones_pago',
                moneda_usa  = '$moneda_usa',
                alias  = '$alias',
                codigo  = '$codigo',
				tipo_cliente  = '$tipo_cliente',
				objetivo  = '$objetivo'
            WHERE id=$id";
        $resultado = mysql_query($consulta) or print("No se ha podido actualizar");
        return $resultado==1?true:false;
    }
    public function create($id_cartera="" ,$nombre_empresa="" ,$direccion_empresa="" ,
            $telefono_empresa="", 
//            $nombre_contacto="", $email_contacto="", $telefono_contacto="",
            $estado="", $ciudad="", $industria="",
//            $contrasenia="",
            $rfc, $condiciones_pago, $moneda_usa, $alias, $codigo, $tipo_cliente, $objetivo){
        
        $consulta = "INSERT INTO  Clientes (
            `id_cartera` ,`nombre_empresa` ,`direccion_empresa` ,`telefono_empresa` ,
            estado, ciudad, industria, rfc  , condiciones_pago  , moneda_usa  , alias  , codigo, tipo_cliente,objetivo 
            )
            VALUES ( '$id_cartera','$nombre_empresa','$direccion_empresa','$telefono_empresa',
                    $estado,'$ciudad',$industria, '$rfc', '$condiciones_pago', '$moneda_usa','$alias','$codigo', '$tipo_cliente', '$objetivo')";
//        echo $consulta;
        $resultado = mysql_query($consulta) or print("<script>alert('El Cliente no fue creado :(');</script>$consulta");
        
        return $resultado==1?true:false;
    }
    
    public function delete(){
        if(!$this->existeCotizacionConEsteCliente()){
            $consulta  = "DELETE FROM Clientes WHERE id = ".$this->id;
            mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre_contacto."</h1><br>" . mysql_error());
            return true;
        } else return false;
    }
    
    private function existeCotizacionConEsteCliente(){
        $consulta  = "SELECT id
            FROM Cotizaciones
            WHERE id_cliente = $this->id";
        $resultado = mysql_query($consulta) or print("La consulta en existeCotizacionConEsteCliente: " . mysql_error());
        if(@mysql_num_rows($resultado)>0){
            echo "<script>alert('No es posible eliminar $this->nombre_empresa por que tiene cotizaciones relacionadas a el.')</script>";
            return true;
        } else {
            return false;
        }
    }
    
    public function getContactos(){
        $query = "SELECT FROM Contactos WHERE id_cliente = $this->id";
        $result = mysql_query($query) or print(mysql_error()." getContactos()");
        if($result){
            while ($row = mysql_fetch_array($query)) {
                array_push($contactos, $row);
            }
        }
        
    }
}
?>