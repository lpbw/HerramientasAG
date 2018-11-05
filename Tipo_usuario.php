<?
//include 'Usuario.php';
class Tipo_usuario{
    public $nombre;
    public $limite_descuento;
    public $id;   
    /*
     @param nombre,limite_descuento,id
     */
    public function get($id){
        $consulta  = "SELECT nombre,id,limite_descuento
            FROM Roles
            WHERE id ='$id'";
        //echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto Tipo_usuario fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
                $res=mysql_fetch_assoc($resultado);
                $this->id=$res['id'];
                $this->nombre=$res['nombre'];
                $this->limite_descuento=$res['limite_descuento'];
            }    
    }
    public function update($id,$nombre,$limite_descuento){
        if($id!=''){
            $consulta = "UPDATE Roles SET nombre = '$nombre', 
                limite_descuento= ".doubleval($limite_descuento)."
                    WHERE id=$id";
            //echo $consulta;
            $resultado = mysql_query($consulta) or print("No se ha podido actualizar");
        }
        return $resultado==1?true:false;
    }
    public function create($nombre,$limite_descuento){
        
        $consulta = "INSERT INTO Roles(nombre,limite_descuento)
            VALUES('$nombre', '$limite_descuento')";
//        echo $consulta;
        $resultado = mysql_query($consulta) or print("Creacion no exitosa");
        return $resultado==1?true:false;
    }
    
    public function delete(){
        if(!$this->existeUsuarioConEsteRol($this->id)){
            $consulta  = "DELETE FROM Roles WHERE id = ".$this->id;
            $resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        }
    }
    private function existeUsuarioConEsteRol($id){
        
        $consulta  = "SELECT Usuarios.nombre, email, Usuarios.id, Roles.nombre AS rol_nombre, contrasenia, id_rol
            FROM Usuarios
            LEFT OUTER JOIN Roles ON Roles.id = Usuarios.id_rol
            WHERE Roles.id ='$id'";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>0){
            echo "<script>alert('No es posible eliminar este tipo de usuario por que tiene usuario relacionados a el.')</script>";
            return true;
        } else {
            return false;
        }
    }
    public function getAll(){
        $consulta  = "SELECT id
            FROM Roles
            WHERE 1";
        //echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto Tipo_usuario fallo: " . mysql_error());
        $tiposUsuario = array();
        while($tipo=  mysql_fetch_array($resultado)){
            array_push($tiposUsuario, $this->get($tipo['id']));
        }
        return $tiposUsuario;
    }
}
?>