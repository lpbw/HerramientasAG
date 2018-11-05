<?
    /**
     * Test.
     * Developer: Luis perez
     * Company: Bluewolf.
     * Date: 27/07/2018
     */
    
    class UploadFiles
    {
        //funcion que recibe el nombre del archivo,la ubicacion,y el campo.
        public function uploadFile($nombreArchivo,$ubicacion,$campo)
        {
            //print_r($_FILES);
            if($_FILES[$campo]['name']!="")
            {

                //extensiones permitidas.
                $allowedExts = array("gif", "jpeg", "jpg", "png","doc","docx","xls","xlsx","ppt","pptx","zip","rar","txt","pdf","csv","GIF","JPEG", "JPG", "PNG","DOC","DOCX","XLS","XLSX","PPT","PPTX","ZIP","RAR","TXT","PDF","CSV");
                //obtiene la extension del archivo subido.
                $extension = end(explode(".", $_FILES[$campo]["name"]));
                //Comprueba que la extension del archivo subido este en el arreglo de las extensiones permitidas.
                if(in_array($extension, $allowedExts))
                {
                    //comprueba si el archivo subito tiene un error y lo manda.
                    if($_FILES[$campo]["error"] > 0)
                    {
                        echo "Return Code error: " . $_FILES[$campo]["error"] . "<br>";
                    }
                    else
                    {
                        //checa si existe el archivo en la direccion.
                        if(file_exists("$ubicacion/$nombreArchivo"))
                        {
                            echo $_FILES[$campo]["name"]."Archivo reemplazado";
                        }
                        //guarda el archivo en la carpeta.
                        move_uploaded_file($_FILES[$campo]["tmp_name"], "$ubicacion/$nombreArchivo.$extension");
                    }
                }
                else
                {
                    echo "<script>alert('Tipo de archivo invalido');</script>";
                    return FALSE;
                }
            }
            return "$ubicacion/$nombreArchivo.$extension";
        }
        
        //funcion para redimensionar la imagen y subirla.
        //recibe en nombre del archivo, ubicacion, campo y el tamanio.
        public function resizeImageAndUpload($nombreArchivo,$ubicacion,$campo, $width=400)
        {
            // checa si ahi documento
            if($_FILES[$campo]['name']!="")
            {
                //crea arreglo con extensiones permitidas.
                $allowedExts = array("gif", "jpeg", "jpg", "png","JPEG", "JPG", "PNG","GIF");
                //obiene la extension del archivo.
                $extension = end(explode(".", $_FILES[$campo]["name"]));
                //verifica si la extension es valida.
                if(in_array($extension, $allowedExts))
                {
                    //verifica si no tiene error el archivo.
                    if ($_FILES[$campo]["error"] <= 0)
                    {
                        include_once "SimpleImage.php";
                        $image = new SimpleImage();
                        //carga imagen.
                        $image->load($_FILES[$campo]["tmp_name"]); //es el request
                        
                        // si el ancho de la imagen es mayor que la altura.
                        if($image->getWidth()>$image->getHeight())
                        {
                            $image->resizeToWidth($width);
                        }
                        else
                        {
                            $image->resizeToHeight($width);
                        }
                        // Si la extension es png.
                        if(in_array($extension, array("png","PNG")))
                        {
                            $image->save("$ubicacion/$nombreArchivo.$extension",IMAGETYPE_PNG);
                        }
                        // si la extensioon es gif.
                        else if(in_array($extension, array("gif","GIF")))
                        {
                            $image->save("$ubicacion/$nombreArchivo.$extension",IMAGETYPE_GIF);
                        }
                        else
                        {
                            $image->save("$ubicacion/$nombreArchivo.$extension",IMAGETYPE_JPEG);
                        } 
                    } 
                    else
                    {
                        echo "Return Code error: " . $_FILES[$campo]["error"] . "<br>";    
                    }
                }
                else
                {
                    echo "<script> alert('El formato $extension no es valido. Intenta nuevamente con formatos JPG, JPEG o PNG.');</script>";

                }
            }
            return "$ubicacion/$nombreArchivo.$extension";
        }
    }
?>