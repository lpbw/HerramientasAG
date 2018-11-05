<?php
session_start();

function mailDebug($subject, $message){
    $toContacto = "miguel.hidrogo@gmail.com";
//    $subject = "ERROR en cotizacion {$_SESSION['cotizacion']->id}";
//    $message = "El producto ID 1234 CODIGO 567 se insertado con moneda inadecuada.";
    $headers = "From: <no-contestar@herramientasag.com.mx>";
    $returnpath = "-fno-contestar@herramientasag.com.mx";
    $ok = mail($toContacto, $subject, $message, $headers,$returnpath);
}
?>
