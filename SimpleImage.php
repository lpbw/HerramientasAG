<?php
    class SimpleImage
    {
        var $image;
        var $image_type;

        //cargar imagen.
        //recibe el nombre de la imagen.
        function load($filename)
        {
            $image_info = getimagesize($filename);
            $this->image_type = $image_info[2];
            //crea la imagen JPEG.
            if( $this->image_type == IMAGETYPE_JPEG ) 
            {
                $this->image = imagecreatefromjpeg($filename);
            } 
            //crea la imagen GIF.
            elseif( $this->image_type == IMAGETYPE_GIF ) 
            {
                $this->image = imagecreatefromgif($filename);
            } 
            //Crea  la imagen PNG
            elseif( $this->image_type == IMAGETYPE_PNG ) 
            {
                $this->image = imagecreatefrompng($filename);
            }
        }

        //salvar imagen.
        //Recibe el nombre de la imagen,tipo,compresion y permisos.
        function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) 
        {
            //salva imagen tipo JPEG
            if( $image_type == IMAGETYPE_JPEG )
            {
                imagejpeg($this->image,$filename,$compression);
            } 
            //Salva imagen GIF.
            elseif( $image_type == IMAGETYPE_GIF ) 
            {
                imagegif($this->image,$filename);         
            } 
            //Salva imagen PNG
            //Necesita una imagen transparente.
            elseif( $image_type == IMAGETYPE_PNG ) 
            {        
                imagealphablending($this->image, false);
                imagesavealpha($this->image,true);
                imagepng($this->image,$filename);
            }   
            if( $permissions != null) 
            {
                chmod($filename,$permissions);
            }
        }

        //Salida de la imagen.
        function output($image_type=IMAGETYPE_JPEG)
        {
            //JPEG
            if( $image_type == IMAGETYPE_JPEG )
            {
                imagejpeg($this->image);
            } 
            //GIF
            elseif( $image_type == IMAGETYPE_GIF ) 
            {
                imagegif($this->image);         
            } 
            //PNG
            elseif( $image_type == IMAGETYPE_PNG ) 
            {
                imagepng($this->image);
            }   
        }

        //obtiene el ancho de la imagen.
        function getWidth() 
        {
            return imagesx($this->image);
        }

        //obtiene el alto de la imagen.
        function getHeight() 
        {
            return imagesy($this->image);
        }

        //cambia la altura de la imagen.
        //recibe altura.
        function resizeToHeight($height)
        {
            //obtine ratio dividiendo altura dada entre la altura actual de la imagen.
            $ratio = $height / $this->getHeight();
            //obtiene ancho con el ancho actual de la imagen por el ratio.
            $width = $this->getWidth() * $ratio;
            //redimensiona la imagen.
            $this->resize($width,$height);
        }

        //cambia el ancho de la imagen.
        //recibe ancho.
        function resizeToWidth($width)
        {
            //obtine ratio dividiendo ancho dada entre el ancho actual de la imagen.
            $ratio = $width / $this->getWidth();
            //obtiene altura con la altura actual de la imagen por el ratio.
            $height = $this->getheight() * $ratio;
            //redimensiona imagen.
            $this->resize($width,$height);
        }

        //escala imagen.
        function scale($scale)
        {
            //calcula ancho con el ancho actual de la imagen por la escala dada entre 100.
            $width = $this->getWidth() * $scale/100;
            //calcula altura con la altura actual de la imagen por la escala dada entre 100.
            $height = $this->getheight() * $scale/100; 
            //redimensiona imagen.
            $this->resize($width,$height);
        }

        //redimensionar imagen.
        //reciebe ancho y altura.
        function resize($width,$height)
        {
            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            $this->image = $new_image;   
        }      
    }
?>