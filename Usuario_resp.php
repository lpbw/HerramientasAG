<?
    include_once "coneccion.php";
    include_once 'UploadFiles.php';
    class Usuario extends UploadFiles
    {
        public $nombre;
        public $email;
        public $id;
        public $id_rol;
        public $limite_descuento;
        public $rol_nombre;
        public $contrasenia;
        public $permisos = array();
        public $id_supervisor;
        public $carteras = array();

        public function getCarteras()
        {
            $query = "SELECT id,nombre FROM CarteraClientes "; 
            $query .= "INNER JOIN CarteraClientes_Usuarios AS ccu ON ccu.id_usuario";
            $query .= "WHERE ccu.id_usuario = $this->id";
            $result = mysql_query($query);
            while ($cartera = mysql_fetch_assoc($result))
            {
                array_push($this->carteras, $cartera);
            }
        }
    
    public function getUser($id){
        $consulta  = "SELECT Usuarios.*, Roles.nombre AS rol_nombre, Roles.limite_descuento
            FROM Usuarios
            LEFT OUTER JOIN Roles ON Roles.id = Usuarios.id_rol
            WHERE Usuarios.id ='$id'";
			
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " . mysql_error());
        if(@mysql_num_rows($resultado)>=1){
            $res=mysql_fetch_assoc($resultado);
            $this->id=$res['id'];
            $this->nombre=$res['nombre'];
            $this->email=$res['email'];
            $this->id_rol=$res['id_rol'];
            $this->rol_nombre=$res['rol_nombre'];
            $this->contrasenia=$res['contrasenia'];
            $this->id_supervisor=$res['id_supervisor'];
            $this->limite_descuento=$res['limite_descuento'];      
            $this->saludoMail=$res['saludoMail'];
            $this->firmaMail=$res['firmaMail'];      
            $this->deMail=$res['deMail'];
            return TRUE;
        } else
            return FALSE;
    }

    public function login($user,$password,$pin){
        $consulta  = "SELECT pin from pin where id=1";
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " );//. mysql_error()
        if(mysql_num_rows($resultado) > 0){
			$res=mysql_fetch_assoc($resultado);
			$mypin=$res['pin'];
		}
		$consulta  = "SELECT Usuarios.nombre, email, Usuarios.id, Roles.nombre AS rol_nombre, contrasenia, id_rol, id_supervisor
            FROM Usuarios
            LEFT OUTER JOIN Roles ON Roles.id = Usuarios.id_rol
            WHERE email='$user' and contrasenia='".md5($password)."'";
//        echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto Usuario fallo: " );//. mysql_error()
		/*echo"<script>alert(\"mp=$mypin p=$pin\");</script>";*/
        if(mysql_num_rows($resultado) > 0 && $mypin==$pin){
                $res=mysql_fetch_assoc($resultado);
                $this->getUser($res['id']);
//                $this->id=$res['id'];
//                $this->nombre=$res['nombre'];
//                $this->email=$res['email'];
//                $this->id_rol=$res['id_rol'];
//                $this->rol_nombre=$res['rol_nombre'];
//                $this->contrasenia=$res['contrasenia'];
//                $this->id_supervisor=$res['id_supervisor'];
			//definiendo variables de accesos y permisos como variables de ambiente
			$this->setPermisosInSession();
			$this->setEnvVariables();
			return true;
        } else return false;
    }
    
    
    public function update($nombre,$email,$contrasenia,$id_rol,$id, $id_supervisor=0,$id_carteras=array()){
		$consulta99  = "select contrasenia from Usuarios where id=$id";
        $resultado99 = mysql_query($consulta99) or print("$consulta99" );
		$res99=mysql_fetch_assoc($resultado99);
		
        if($contrasenia!=''){
			if($contrasenia==$res99['contrasenia']){
				$contra = ", contrasenia = '".$contrasenia."'";
			}else{
				$contra = ", contrasenia = '".md5($contrasenia)."'";
			}
        }else{ $contra = ", contrasenia = '".$res99['contrasenia']."'";}
        
        $consulta = "UPDATE Usuarios SET nombre = '$nombre', email = '$email'
         $contra ,id_rol='$id_rol', id_supervisor = $id_supervisor
            WHERE id=$id";
        $resultado = mysql_query($consulta) or print("No se ha podido actualizar<br>$consulta");
        
        $errores = $this->setCarteras($id_carteras);
        
        return $resultado && count($errores) <= 0;
    }
    
    public function createUser($nombre,$email,$contrasenia,$id_rol,$id_supervisor,$carteras){
        if($contrasenia!=''){
            $contra = md5($contrasenia);
        } else $contra = md5('123456');
        
        $consulta = "INSERT INTO Usuarios(nombre,email,contrasenia,id_rol, id_supervisor)
            VALUES('$nombre', '$email', '$contra','$id_rol', '$id_supervisor')";
        $resultado = mysql_query($consulta) or print("Cartera Repetida<br>");
        
        $this->getUser(mysql_insert_id());
        
        $errores = $this->setCarteras($carteras);
        
        return $resultado && count($errores) <= 0;
    }
    
    public function deleteUser(){
        if( ! $this->existeCotizacionConEsteUsuario() ){
            $consulta  = "DELETE FROM Usuarios WHERE id = ".$this->id;
            return mysql_query($consulta) or print("<h1>Eliminado no exitoso de ".$this->nombre."</h1><br>" . mysql_error());
        } else return false;
    }

    
    
    
    
    /*
     * private functions
     */
    
    
    private function setCarteras($id_carteras){
            $errores = array();
            $consulta = "DELETE FROM CarteraClientes_Usuarios WHERE id_usuario = ".$this->id;
            $resultado = mysql_query($consulta) or print("No se ha podido borrar actualizar cartera<br>");
            if(mysql_errno())
                array_push ($errores, mysql_error ());
            
            if(count($id_carteras)>0){
                foreach ($id_carteras as $id) {
                    $consulta = "INSERT INTO CarteraClientes_Usuarios(id_usuario,id_cartera_clientes)
                        VALUES (".$this->id.",$id)";
                    $resultado = mysql_query($consulta) or print("No se ha podido actualizar cartera<br>");
                    if(mysql_errno())
                        array_push ($errores, mysql_error ());
                }
            }
            
            if(count($errores)>0){
                return $errores;
            } else 
                return true;
    }
    
    
    private function setEnvVariables(){
        $consulta  = "SELECT nombre,id,limite_descuento
            FROM Roles";
        //echo $consulta;
        $resultado = mysql_query($consulta) or print("La consulta en objeto Tipo_usuario fallo: " . mysql_error());
        $_SESSION['accesos']=array();
        while($acceso = mysql_fetch_array($resultado)){
            $_SESSION['accesos'][$acceso['nombre']]=$acceso['id'];
        }
        $_SESSION['accesos']['todos']=99;
		
		/*obteniendo valor de dollar*/
		$query = "SELECT id,nombre,valor FROM tipo_de_cambio WHERE 1";
		$result = mysql_query($query) or print("<script>parent.location.reload();</script>");
		if($result)
			$tipo_cambio = mysql_fetch_assoc($result);
		
		$_SESSION['dollar'] = $tipo_cambio['valor'];
    }
    
    private function setPermisosInSession(){
        $consulta  = "SELECT nombre,id FROM Permisos 
            INNER JOIN Usuarios_Permisos ON Usuarios_Permisos.id_permiso = Permisos.id
            WHERE id_usuario = ".$this->id;
        $resultado = mysql_query($consulta) or print("La consulta en setPermisosInSession objeto Usuario fallo: " . mysql_error());
        while($permisos = mysql_fetch_array($resultado)){
            $this->permisos[intval($permisos['id'])]=$permisos['nombre'];
        }
    }
    
    public function getCompras(){
        $query = "SELECT Usuarios.id FROM Usuarios 
            INNER JOIN Roles ON Roles.id = Usuarios.id_rol
            WHERE Roles.nombre like '%compras%' OR Roles.nombre like '%Compras%' 
             OR Roles.nombre like '%COMPRAS%' ";
        $result = mysql_query($query) or print ("Error en getCompras Usuario en ".mysql_error()."<br>$query");
        
        if($result){
            $id = mysql_fetch_assoc($result);    
            $compras = new Usuario();
            $compras->getUser($id['id']);
            return $compras;
        } else {
            return FALSE;
        }
    }
    
    public function getSupervisor(){
        if($this->id_supervisor != ""){
            $supervisor = new Usuario();
            if($supervisor->getUser($this->id_supervisor))
                return $supervisor;
            else
                return FALSE;
        } else
            return FALSE;
    }
    public function getVendedoresSubordinados(){
        if($this->id_rol <= $_SESSION['accesos']['supervisor']){
            $vendedores = array();
            $query = "SELECT id, nombre, email FROM Usuarios WHERE id_supervisor = $this->id";
            $result = mysql_query($query) or print('Error obteniendo sus vendedores');
            while ($row = mysql_fetch_assoc($result)) {
                array_push($vendedores, $row);
            }
            return $vendedores;
        } else return false;
    }
    
    
    public function getTareas($date=""){
        if($date!="")$where = "fecha_vencimiento <= '$date'";
        else $where = 1;
        $query = "SELECT id, id_cotizacion, id_usuario, 
            fecha_inicio, fecha_vencimiento, fecha_recordatorio, 
            fecha_creacion, DATE_FORMAT(fecha_creacion, '%e %b %Y') as fecha_inicio_user_friendly,
            fecha_vencimiento, DATE_FORMAT(fecha_inicio, '%e %b %Y') as fecha_vencimiento_user_friendly,
            fecha_recordatorio, DATE_FORMAT(fecha_recordatorio, '%e %b %Y') as fecha_recordatorio_user_friendly,
            completada, descripcion , asunto
            FROM Tareas
            WHERE id_usuario = $this->id 
                AND completada = FALSE
                AND $where
            ORDER BY fecha_vencimiento ASC";
        
        $result = mysql_query($query) or print($query.  mysql_error());
        $tareas = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($tareas, $row);
        }
        return $tareas;
    }
    
    
    public function getRecordatoriosFromToday(){
        $today = $this->getToday();
        $query = "SELECT id, id_cotizacion, id_usuario, 
            fecha_inicio, fecha_vencimiento, fecha_recordatorio, 
            fecha_creacion, DATE_FORMAT(fecha_creacion, '%e %b %Y') as fecha_inicio_user_friendly,
            fecha_vencimiento, DATE_FORMAT(fecha_inicio, '%e %b %Y') as fecha_vencimiento_user_friendly,
            fecha_recordatorio, DATE_FORMAT(fecha_recordatorio, '%e %b %Y') as fecha_recordatorio_user_friendly,
            completada, descripcion , asunto
            FROM Tareas
            WHERE id_usuario = $this->id 
                AND fecha_recordatorio = '$today'
                AND completada = FALSE
                ORDER BY fecha_recordatorio ASC";
        
        $result = mysql_query($query) or print(mysql_error());
        $tareas = array();
        while ($row = mysql_fetch_assoc($result)) {
            array_push($tareas, $row);
        }
        return $tareas;
    }
    
    private function getToday(){
            date_default_timezone_set('America/Chihuahua');
            $today = getdate();
            $today = $today['year']."-".
                    $this->returnNumberWithCero($today['mon'])."-".
                    $this->returnNumberWithCero($today['mday']);
            return $today;
    }
    
    public function mailTareasToday(){
        include_once 'mailTareas.php';
        $today = $this->getToday();
        $tareasToday = $this->getTareas($today);
        
        if( count($tareasToday) > 0 ){
            
            $sender = new Usuario();
            $sender->getUser(1);

            $subject = "Tienes ".count($tareasToday)." tareas para hoy";
            // email fields: to, from, subject, and so on
            $from = " $sender->nombre <".$sender->email.">"; 
            $to = "$this->nombre <$this->email>";

            // cuerpo de las tareas
            $message = "<h3>$this->nombre</h3>";//debug
            $message .= getBodyTareas($tareasToday);
            $headers = "From: $from";



            // headers for CC
            $headers .= "\nCC: $cc"; 
           // boundary 
            $semi_rand = md5(time()); 
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

            // headers for attachment 
            $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

            // multipart boundary 
            $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 

            // preparing attachments
        //    for($i=0;$i<count($files);$i++){
    //        if (count($this->archivos)>0){
    //            foreach ($this -> archivos as $file) {
    //                if(is_file($file['location'])){
    //                    $message .= "--{$mime_boundary}\n";
    //                    $fp = @fopen($file['location'],"rb");
    //                    $data = @fread($fp,filesize($file['location']));
    //                    @fclose($fp);
    //                    $data = chunk_split(base64_encode($data));
    //                    $message .= "Content-Type: application/octet-stream; name=\"".basename($file['location'])."\"\n" . 
    //                        "Content-Description: ".basename($file['location'])."\n" .
    //                        "Content-Disposition: attachment;\n" . " filename=\"".basename($file['location'])."\"; size=".filesize($file['location']).";\n" . 
    //                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    //                }
    //            }
    //        }

            $message .= "--{$mime_boundary}--";
            $returnpath = "-f" . $sender->email;
            $ok = @mail($to, $subject, $message, $headers, $returnpath);

            return $ok;
        }
        
    }
    
    private function returnNumberWithCero($number){
        if(intval($number) < 10)
            return "0".$number;    
        else return $number;
    }
    
    private function existeCotizacionConEsteUsuario(){
        $consulta  = "SELECT id
            FROM Cotizaciones
            WHERE id_usuario = $this->id
            GROUP BY id";
        $resultado = mysql_query($consulta) or print("La consulta en existeCotizacionConEsteCliente: " . mysql_error());
        
        $cotizaciones = array();
        while ($row = mysql_fetch_assoc($resultado)) {
            array_push($cotizaciones, $row);
            $cotizacionesTexto .= $row['id'].", ";
        }
        if(count($cotizaciones) > 0){
            echo "<script>alert('No es posible eliminar $this->nombre por que tiene cotizaciones con folios: $cotizacionesTexto relacionadas.')</script>";
            return true;
        } else {
            return false;
        }
    }
/*
 * FUNCIONES DE CONFIGURACION DE EMAIL
 */    
    
    function getMailSettings(){
        $consulta = "SELECT deMail, saludoMail, firmaMail FROM Usuarios WHERE id=$this->id";
        $resultado = mysql_query($consulta) or print("No se ha podido obtener<br>");
        $usuario = mysql_fetch_assoc($resultado);
        $this->saludoMail = $usuario['saludoMail'] ;
        $this->firmaMail = $usuario['firmaMail'];
        $this->deMail = $usuario['deMail'] ;
    }

    function updateMailSettings($de=NULL,$saludo=NULL,$firma=NULL){
        $id = $this->id;
        $consulta = "UPDATE Usuarios SET deMail = '$de', saludoMail= '$saludo', firmaMail='$firma'
            WHERE id=$id";
        $resultado = mysql_query($consulta) or print("No se ha podido actualizar<br>");
        if($resultado){
            $this->saludoMail = $saludo ;
            $this->firmaMail = $firma;
            $this->deMail = $de ;
        }
        return $resultado;
    }

    function updateBannerMail($nombreCampo=NULL){
        //---------------------------
        if(!is_null($nombreCampo) || !empty($nombreCampo) || $nombreCampo!=""){
            $nombreArchivoSistema = "banner_mail.png";
            $nombreArchivo = $_FILES[$nombreCampo]['name'];

            $archivo_location = $this->uploadFile($nombreArchivoSistema , 'archivos' , $nombreCampo);
//            $archivo_location = uploadFile($nombreArchivoSistema , 'archivos' , $nombreCampo);
            if($archivo_location)
                return TRUE;
            else 
                return FALSE;
        }
    }

}
?>