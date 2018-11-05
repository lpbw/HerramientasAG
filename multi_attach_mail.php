<?
include 'coneccion.php';
include 'Usuario.php';
include 'Producto.php';
include 'Cotizacion.php';
//function __autoload($class_name) {
//    include $class_name . '.php';
//}
session_start();

multi_attach_mail('miguel.hidrogo@gmail.com',
        $_SESSION['cotizacion']->archivos,
        'miguel.hidrogo@gmail.com');

function multi_attach_mail($to, $files, $sendermail){
    // email fields: to, from, subject, and so on
    $from = "Files attach <".$sendermail.">"; 
    $subject = date("d.M H:i")." F=".count($files); 
    $message = date("Y.m.d H:i:s")."\n".count($files)." attachments";
    $headers = "From: $from";
 
    // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
 
    // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
    // multipart boundary 
    $message = "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
 
    // preparing attachments
//    for($i=0;$i<count($files);$i++){
    foreach ($files as $file) {
        if(is_file($file['location'])){
            $message .= "--{$mime_boundary}\n";
            $fp =    @fopen($file['location'],"rb");
        $data =    @fread($fp,filesize($file['location']));
                    @fclose($fp);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: application/octet-stream; name=\"".basename($file['location'])."\"\n" . 
            "Content-Description: ".basename($file['location'])."\n" .
            "Content-Disposition: attachment;\n" . " filename=\"".basename($file['location'])."\"; size=".filesize($file['location']).";\n" . 
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            }
        }
    $message .= "--{$mime_boundary}--";
    $returnpath = "-f" . $sendermail;
    $ok = @mail($to, $subject, $message, $headers, $returnpath); 
    if($ok){ return $i; } else { return 0; }
    }
    ?>