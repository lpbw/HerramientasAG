<?
include 'Producto.php';
class Familia_cotizador{
    public $nombre;
    public $codigo;
	public $factor;
    public $id;   
    /*
     @param nombre,limite_descuento,id
     */
    public function get($id){
        $pass=$_POST["pass"];
        $consulta  = "SELECT nombre,id,codigo, factor
		FROM FamiliaCotizador
            WHERE id ='$id'";
        //echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto Familia_cotizador fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
                $res=mysql_fetch_assoc($resultado);
                $this->id=$res['id'];
                $this->nombre=$res['nombre'];
                $this->codigo=$res['codigo'];
				$this->factor=$res['factor'];
            }    
    }
    public function update($id,$nombre,$codigo, $factor){
        if($id!=''){
            $consulta = "UPDATE FamiliaCotizador SET nombre = '$nombre', 
                codigo= '$codigo', factor= '$factor'
                    WHERE id=$id";
            //echo $consulta;
            $resultado = mysql_query($consulta) or print("No se ha podido actualizar");
			$consulta  = "SELECT Productos.id, Productos.precio, FamiliaCotizador.factor FROM Productos inner join FamiliaCotizador on Productos.codigo_familia=FamiliaCotizador.codigo WHERE Productos.tienda=1 and Productos.precio>0 ";
				//echo"$consulta";
			$resultado2 = mysql_query($consulta) or die(mysql_error());
			$contador=0;
			while(mysql_num_rows($resultado2) > $contador){
				$idP2 = mysql_fetch_assoc($resultado2);
				if($idP2['factor']!="0")
				$precio_on=round((($idP2['precio']-($idP2['precio']*$idP2['factor']))*1.16),2);
				else
				$precio_on=round(($idP2['precio']*1.16),2);
				
				$id_pr=$idP2['id'];
				$consulta3  = "update Productos set precio_online=$precio_on where id=$id_pr";
				//echo"$consulta3";
				$resultado3 = mysql_query($consulta3) or die("Error en operacion1.2:$consulta3 " . mysql_error());
				$contador++;
			}
        }
        return $resultado==1?true:false;
    }
	
    public function create($nombre,$codigo,$factor){
        
        $consulta = "INSERT INTO FamiliaCotizador(nombre,codigo, factor)
            VALUES('$nombre', '$codigo', '$factor')";
//        echo $consulta;
        $resultado = mysql_query($consulta) or print("Creacion no exitosa");
        return $resultado==1?true:false;
    }
    
    public function delete(){
        if(!$this->existeProductoConEstaFamilia($this->id)){
            $consulta  = "DELETE FROM FamiliaCotizador WHERE id = ".$this->id;
            $resultado = mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        }
    }
    private function existeProductoConEstaFamilia($id){
        
        $consulta  = "SELECT Productos.nombre AS producto_nombre, Productos.id AS id_producto, 
		FamiliaCotizador.nombre, FamiliaCotizador.codigo
            FROM Productos
            LEFT OUTER JOIN FamiliaCotizador ON FamiliaCotizador.codigo = Productos.codigo_familia
            WHERE FamiliaCotizador.id ='$id'";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>0){
            echo "<script>alert('No es posible eliminar esta familia por que tiene productos relacionados a ella.')</script>";
            return true;
        } else {
            return false;
        }
    }
}
?>