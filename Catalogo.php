<?
class Catalogo{
    public $nombre;
    public $id;
    public $factor;
    public $factor2;
    public $id_proveedor;
    public $codigo;
    public $id_tipo_importacion;
    public $origenFactor;
    public $origenFactor2;
    /*
     @param nombre,limite_descuento,id
     */
    public function get($id,$id_proveedor){
        $consulta  = "SELECT nombre, id, factor, factor2,
            id_proveedor, id_tipo_importacion, codigo, origenFactor, origenFactor2
            FROM CatalogoProductos
            WHERE id ='$id' AND id_proveedor=$id_proveedor";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Catalogo fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
                $res=mysql_fetch_assoc($resultado);
                $this->id=$res['id'];
                $this->nombre=$res['nombre'];
                $this->factor=$res['factor'];
                $this->factor2=$res['factor2'];
                $this->id_proveedor=$res['id_proveedor'];
                $this->id_tipo_importacion=$res['id_tipo_importacion'];
                $this->codigo=$res['codigo'];
                $this->origenFactor=$res['origenFactor'];
                $this->origenFactor2=$res['origenFactor2'];
            }    
    }
    public function update($id,$nombre,$factor,$factor2,$id_tipo_importacion,$origenFactor,$origenFactor2){
            $consulta = "UPDATE CatalogoProductos SET nombre = '$nombre',
                factor = $factor, factor2 = '$factor2', id_tipo_importacion = $id_tipo_importacion,
                    origenFactor = '$origenFactor', origenFactor2 = '$origenFactor2'
                WHERE id=$id";
            $resultado = mysql_query($consulta) or print("No se ha podido actualizar ".  mysql_error());
            
			$consulta  = "SELECT Productos.id, Productos.costo FROM Productos  WHERE id_catalogo_productos=$id ";
				 //echo"$consulta";
			$resultado2 = mysql_query($consulta) or die(mysql_error());
			$contador=0;
			while(mysql_num_rows($resultado2) > $contador){
				$idP2 = mysql_fetch_assoc($resultado2);
				if($idP2['factor']!="0")
				$precio_on=round(($idP2['costo']*$factor),2);
				else
				$precio_on=round(($idP2['costo']),2);
				
				$id_pr=$idP2['id'];
				$consulta3  = "update Productos set precio=$precio_on where id=$id_pr";
				//echo"$consulta3";
				$resultado3 = mysql_query($consulta3) or die("Error en operacion1.2:$consulta " . mysql_error());
				$contador++;
			}
        return ($resultado==1)?true:false;
    }
    public function create($nombre,$factor,$factor2,$id_proveedor,$id_tipo_importacion){
        
        $codigo = intval($this->getNextCodigo($id_proveedor));
        ++$codigo;
        
        $consulta = "INSERT INTO CatalogoProductos
                    ( nombre, factor, factor2, id_proveedor, id_tipo_importacion, codigo)
            VALUES  ('$nombre', '$factor', '$factor2', $id_proveedor, $id_tipo_importacion, $codigo)";
//        echo $consulta."<br>";
        $resultado = mysql_query($consulta) or print("Creacion no exitosa ".mysql_errno()." ".  mysql_error());
        return ($resultado==1)?true:false;
    }
    private function getNextCodigo($id_proveedor){
        $consulta = "SELECT MAX(codigo) AS codigo FROM CatalogoProductos 
                       WHERE id_proveedor = $id_proveedor";
        $resultado = mysql_query($consulta) or print("Consulta next codigo no exitosa $consulta ".  mysql_error());
        $codigo = mysql_fetch_array($resultado);
        $codigo = $codigo['codigo'];
        
        if($resultado)
            return $codigo;
    }
    
    
    public function delete(){
        if(!$this->existeProductoConEsteCatalogo()){
            $consulta  = "DELETE FROM CatalogoProductos
                WHERE id = ".$this->id."
                    AND id_proveedor = ".$this->id_proveedor;
            mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        }
    }
    private function existeProductoConEsteCatalogo(){
        $consulta  = "SELECT id
            FROM Productos
            WHERE id_catalogo_productos ='$this->codigo' AND id_proveedor = $this->id_proveedor ";
        $resultado = mysql_query($consulta) or print("La consulta en existeProductoConEsteCatalogo: " . mysql_error());
        if(@mysql_num_rows($resultado)>0){
            echo "<script>alert('No es posible eliminar este catalogo por que tiene productos relacionados a el.')</script>";
            return true;
        } else {
            return false;
        }
    }
}
?>