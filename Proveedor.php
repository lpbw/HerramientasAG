<?
//include 'UploadFiles.php';
class Proveedor{// extends UploadFiles
    public $nombre;
    public $id;
	public $prefijo;
    
    public function get($id){
        $consulta  = "SELECT *
            FROM Proveedores
            WHERE id ='$id'";
        //echo "Consulta GET".$consulta."<br>";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Producto fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
                $res=mysql_fetch_assoc($resultado);
                $this->nombre=$res['nombre'];
                $this->id=$res['id'];
                $this->prefijo=$res['prefijo'];
            }    
    }
    
    public function update($nombre, $prefijo){
        $consulta = "UPDATE Proveedores SET nombre = '$nombre', prefijo = '$prefijo'
                    WHERE id = ".$this->id;
        $resultado = mysql_query($consulta) or print("No se ha podido actualizar ".  mysql_error());
       
        return $resultado==1?true:false;
    }
    
    public function create($nombre, $prefijo){
        $consulta = "INSERT INTO Proveedores(nombre,prefijo)
            VALUES('$nombre','$prefijo')";
        $resultado = mysql_query($consulta) or print("Creacion no exitosa ".  mysql_error());

        return $resultado==1?true:false;
    }
    
    public function delete(){
        if(!$this->existeProductoConEsteProveedor($this->id)){
            $consulta  = "DELETE FROM Proveedores WHERE id = ".$this->id;
            $resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        }
    }
    private function existeProductoConEsteProveedor($id){
        
        $consulta  = "SELECT Productos.nombre AS producto_nombre, Productos.id AS id_producto, 
		Proveedores.nombre, Proveedores.prefijo
            FROM Productos
            LEFT OUTER JOIN Proveedores ON Proveedores.id = Productos.id_proveedor
            WHERE Proveedores.id ='$id'";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>0){
            echo "<script>alert('No es posible eliminarlo por que tiene productos relacionados.')</script>";
            return true;
        } else {
            return false;
        }
        
    }
}
?>