<?
class Cartera_clientes{
    public $nombre;
    public $id;   
    /*
     @param nombre,limite_descuento,id
     */
    public function get($id){
        $pass=$_POST["pass"];
        $consulta  = "SELECT nombre,id
            FROM CarteraClientes
            WHERE id ='$id'";
        //echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
                $res=mysql_fetch_assoc($resultado);
                $this->id=$res['id'];
                $this->nombre=$res['nombre'];
            }    
    }
    public function update($id,$nombre){
        if($id!=''){
            $consulta = "UPDATE CarteraClientes SET nombre = '$nombre' WHERE id=$id";
            $resultado = mysql_query($consulta) or print("No se ha podido actualizar");
        }
        return $resultado==1?true:false;
    }
    public function create($nombre){
        $consulta = "INSERT INTO CarteraClientes(nombre)
            VALUES('$nombre')";
        $resultado = mysql_query($consulta) or print("Creacion no exitosa");
        return $resultado==1?true:false;
    }
    
    public function delete(){
        if(!$this->existeClienteConEstaCartera($this->id)){
            $consulta  = "DELETE FROM CarteraClientes WHERE id = ".$this->id;
            $resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        }
    }
    private function existeClienteConEstaCartera($id){
        $consulta  = "SELECT * FROM  CarteraClientes_Usuarios
            WHERE id_usuario = '$id'";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>0){
            echo "<script>alert('No es posible eliminar esta categoria por que tiene usuarios relacionados.')</script>";
            return true;
        } else {
            return false;
        }
    }
}
?>