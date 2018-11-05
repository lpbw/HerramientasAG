<?
class Tarea{
    public $id;
    public $id_cotizacion;
    public $id_usuario;
    public $fecha_creacion;
    public $fecha_inicio;
    public $fecha_vencimiento;
    public $fecha_recordatorio;
    public $completada;
    public $descripcion;
    public $fecha_inicio_user_friendly;
    public $fecha_vencimiento_user_friendly;
    public $fecha_recordatorio_user_friendly;
    public $asunto;

    
    public function get($id){
        $query  = "SELECT id, 
                id_cotizacion, 
                id_usuario, 
                
                fecha_creacion, 
                fecha_inicio, 
                fecha_vencimiento, 
                fecha_recordatorio, 
                
                asunto,
                completada, 
                descripcion,
                
                fecha_creacion, DATE_FORMAT(fecha_inicio, '%e %b %Y') as fecha_inicio_user_friendly,
                fecha_vencimiento, DATE_FORMAT(fecha_vencimiento, '%e %b %Y') as fecha_vencimiento_user_friendly,
                fecha_recordatorio, DATE_FORMAT(fecha_recordatorio, '%e %b %Y') as fecha_recordatorio_user_friendly 
                
            FROM Tareas
            WHERE id ='$id'";
			
        $resultado = mysql_query($query) or print("La consulta en objeto tarea: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            $res=mysql_fetch_assoc($resultado);
            $this -> id = $res['id'];
            $this -> id_cotizacion = $res['id_cotizacion'];
            $this -> id_usuario = $res['id_usuario'];
            
            $this -> fecha_creacion = $res['fecha_creacion'];
            $this -> fecha_inicio = $res['fecha_inicio'];
            $this -> fecha_vencimiento = $res['fecha_vencimiento'];
            $this -> fecha_recordatorio = $res['fecha_recordatorio'];
            
            $this -> asunto = $res['asunto'];
            $this -> completada = $res['completada'];
            $this -> descripcion = $res['descripcion'];
            
            $this -> fecha_inicio_user_friendly = $res['fecha_inicio_user_friendly'];
            $this -> fecha_vencimiento_user_friendly= $res['fecha_vencimiento_user_friendly'];
            $this -> fecha_recordatorio_user_friendly= $res['fecha_recordatorio_user_friendly'];
            
            return TRUE;
            
        } else
            return FALSE;
    }

    public function update($tarea){
        
        
        if($tarea->fecha_recordatorio!="") 
            $tarea->fecha_recordatorio = "'$tarea->fecha_recordatorio'";
        else 
            $tarea->fecha_recordatorio="NULL";
            
        $query = "UPDATE Tareas SET 
            id = '$tarea->id',
            id_cotizacion = '$tarea->id_cotizacion',
            id_usuario = '$tarea->id_usuario',
            fecha_creacion = '$tarea->fecha_creacion',
            fecha_inicio = '$tarea->fecha_inicio',
            fecha_vencimiento = '$tarea->fecha_vencimiento',
            fecha_recordatorio = $tarea->fecha_recordatorio,
            completada = '$tarea->completada',
            descripcion = '$tarea->descripcion',
            asunto = '$tarea->asunto'
            WHERE id=$tarea->id";
        $resultado = mysql_query($query) or print("No se ha podido actualizar tarea<br>");
        
        return $resultado ? true : false;
    }
    
    public function create($id = 'DEFAULT', $id_cotizacion = "DEFAULT", $id_usuario, 
             $asunto, $fecha_inicio='', $fecha_vencimiento='', $fecha_recordatorio = "NULL", $completada, $descripcion,
            $fecha_creacion = "CURRENT_TIMESTAMP"){
        
        if($asunto != ""){
            if($fecha_recordatorio == "") 
                $fecha_recordatorio = "NULL";
            else 
                $fecha_recordatorio = "'$fecha_recordatorio'";
            
            if($id_cotizacion == "" || $id_cotizacion == "NULL" || $id_cotizacion==0 || $id_cotizacion == "null" ) 
                $id_cotizacion = "NULL";
            
            $query = "INSERT INTO Tareas(id, 
                                id_cotizacion, 
                                id_usuario, 
                                fecha_creacion, 
                                fecha_inicio, 
                                fecha_vencimiento, 
                                fecha_recordatorio, 
                                completada, 
                                descripcion,
                                asunto)
                VALUES('$id', 
                        $id_cotizacion, 
                        $id_usuario, 
                        $fecha_creacion, 
                        '$fecha_inicio', 
                        '$fecha_vencimiento', 
                        $fecha_recordatorio, 
                        '$completada', 
                        '$descripcion',
                        '$asunto')";
            $resultado = mysql_query($query) or print("Create Tarea<br>$query<br>".  mysql_error());

        } else $resultado = false;
        
        return $resultado ? true : false;
    }
    
    public function delete(){
        $query  = "DELETE FROM Tareas WHERE id = ".$this->id;
	mysql_query($query) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
    }

}
?>