<?php

    include_once "SimpleImage.php";
    
function listarArchivos( $path ){
    $imagenesGrandes = array();
    $directorio = opendir($path); //ruta actual
    while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
    {
        if (is_dir($archivo))//verificamos si es o no un directorio
        {
            echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
        }
        else
        {
            if(strpos($archivo, "imagenProducto")===false){
                
            }else{
                $size = getimagesize("$path/$archivo");
                $size =  $size[3];
                $size = str_replace('"', "", $size);
                $size = str_replace('width', "", $size);
                $size = str_replace('height', "", $size);
                $sizes = explode('=', $size);
                $width = $sizes[1];
                $height = $sizes[2];
                if(intval($width)>400){
                    echo "$archivo width: $width; height = $height<BR>";
                    resizeImage("$path/$archivo");
                }
            }
            
            
        }
    }
    return $imagenesGrandes;
}
// Llamamos a la función para que nos muestre el contenido de la carpeta gallery
listarArchivos("archivos");

function resizeImage($imagenAME, $width = 400){
    $allowedExts = array("gif", "jpeg", "jpg", "png","JPEG", "JPG", "PNG","GIF");
    $extension = end(explode(".", $imagenAME));
    
    if (in_array($extension, $allowedExts)) {
        echo " resize($imagenAME)";
        $image = new SimpleImage();
        $image->load($imagenAME); //es el request
        if($image->getWidth()>$image->getHeight())
            $image->resizeToWidth($width);
        else
            $image->resizeToHeight($width);
        $image->save($imagenAME);
    }
}

?>
