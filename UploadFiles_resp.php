<?
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
?>
                    <script> alert('Tipo de archivo invalido');</script>
<?
                    return FALSE;
                }
            }
            return "$ubicacion/$nombreArchivo.$extension";
        }
    
    public function resizeImageAndUpload($nombreArchivo,$ubicacion,$campo, $width=400){
        if($_FILES[$campo]['name']!=""){
            $allowedExts = array("gif", "jpeg", "jpg", "png","JPEG", "JPG", "PNG","GIF");
            $extension = end(explode(".", $_FILES[$campo]["name"]));

            if (in_array($extension, $allowedExts)) {
                if ($_FILES[$campo]["error"] <= 0) {
                    /*
                     * code to resize and upload
                     */
                    //echo "ENTRO A resizeImageAndUpload($nombreArchivo,$ubicacion,$campo, $width=400);";
                    
                    include_once "SimpleImage.php";
                    $image = new SimpleImage();
                    $image->load($_FILES[$campo]["tmp_name"]); //es el request
                    if($image->getWidth()>$image->getHeight())
                    	$image->resizeToWidth($width);
                    else
                        $image->resizeToHeight($width);
                    
                    if(in_array($extension, array("png","PNG")))
                        $image->save("$ubicacion/$nombreArchivo.$extension",IMAGETYPE_PNG);
                    else if(in_array($extension, array("gif","GIF")))
                        $image->save("$ubicacion/$nombreArchivo.$extension",IMAGETYPE_GIF);
                    else 
                        $image->save("$ubicacion/$nombreArchivo.$extension",IMAGETYPE_JPEG);
                        
                    /*
                     * end code to resize and upload
                     */
                } else {
                    echo "Return Code error: " . $_FILES[$campo]["error"] . "<br>";    
                }
            } else {
              ?><script> alert('El formato <? echo $extension ?> no es valido. Intenta nuevamente con formatos JPG, JPEG o PNG.');</script><? 
            }
        }
        return "$ubicacion/$nombreArchivo.$extension";
    }
}
?>