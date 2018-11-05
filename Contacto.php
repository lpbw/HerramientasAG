<?
class Contacto{
    public $id;	
    public $id_cliente;
    public $nombre_contacto; 
    public $email_contacto;
    public $contrasenia;
    public $telefono_contacto;
    public $departamento_empresa;
    public $es_comprador;
    
    public function get($id, $id_cliente){
        $consulta  = "
            SELECT id, id_cliente, nombre_contacto, email_contacto, telefono_contacto, contrasenia,
                departamento_empresa, es_comprador
            FROM Contactos
            WHERE id ='$id' AND id_cliente = $id_cliente";
        $resultado = mysql_query($consulta) or die("La consulta a Contacto fall&oacute;: $consulta" . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
                $res=mysql_fetch_assoc($resultado);
                $this->id=$res['id'];
                $this->id_cliente=$res['id_cliente'];
                $this->nombre_contacto=$res['nombre_contacto'];
                $this->email_contacto=$res['email_contacto'];
                $this->telefono_contacto=$res['telefono_contacto'];
                $this->contrasenia = $res['contrasenia'];
                $this->departamento_empresa  = $res['departamento_empresa'];
                $this->es_comprador  = $res['es_comprador'];
            }
    }

    public function login($user , $password){
        $consulta  = "SELECT id, id_cliente FROM Contactos
            WHERE email_contacto='$user' and contrasenia='".md5($password)."'";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Contacto en Login: " . mysql_error());
        if(mysql_num_rows($resultado)>0){
            $res=mysql_fetch_assoc($resultado);
            $this->get($res['id'], $res['id_cliente']);
            return true;
        } else {
            return false;
        }
    }
    
    
    public function update( $id, $id_cliente ,
            $nombre_contacto, $email_contacto, $telefono_contacto,
            $contrasenia, $departamento_empresa, $es_comprador){
        
        $consulta = "UPDATE  Contactos SET  id_cliente =  '$id_cliente',
                nombre_contacto =  '$nombre_contacto',
                email_contacto =  '$email_contacto',
                telefono_contacto =  '$telefono_contacto',
                contrasenia = '".  md5($contrasenia)."',
                departamento_empresa  = '$departamento_empresa',
                es_comprador = '$es_comprador'
            WHERE id=$id";
        $resultado = mysql_query($consulta) or print(mysql_error()."No se ha podido actualizar <br> $consulta");
        if($resultado)
            $this->get($id , $id_cliente);
        return $resultado==1?true:false;
    }
    public function create($id_cliente,
            $nombre_contacto, $email_contacto ="", $telefono_contacto ="",
            $contrasenia ="e10adc3949ba59abbe56e057f20f883e", //123456
            $departamento_empresa ="", $es_comprador =0){
        
        $consulta = "INSERT INTO  Contactos (
                id_cliente, nombre_contacto, email_contacto, telefono_contacto, contrasenia,
                departamento_empresa, es_comprador
            ) VALUES ( '$id_cliente', '$nombre_contacto','$email_contacto','$telefono_contacto',
                '".  md5($contrasenia)."', '$departamento_empresa', '$es_comprador')";
        $resultado = mysql_query($consulta) or print("<script>alert('El Contacto no fue creado :(');</script>".  mysql_error() ." $consulta");
        
        if($resultado)
            $this->get(mysql_insert_id() , $id_cliente);
        return $resultado==1?true:false;
    }
    
    public function delete(){
        $consulta  = "UPDATE  Contactos SET activo=0 WHERE id = ".$this->id;
	mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre_contacto."</h1><br>" . mysql_error());
    }

}
?>