<?

function castCodigoInterno($cod_interno, $numero_consecutivo){
    $num_consecutivo = intval($numero_consecutivo);
    $codigo_interno = intval($cod_interno);
    if(!is_nan($codigo_interno) && $codigo_interno!=""){
        if($codigo_interno>9)
        $codigo_interno = "0$codigo_interno";
    } else
        $codigo_interno = "XXX";
    
    
    if($num_consecutivo>9){
        return $codigo_interno."0000$num_consecutivo";
        
    } else if($num_consecutivo>99){
        return $codigo_interno."000$num_consecutivo";
        
    } else if($num_consecutivo>999){
        return $codigo_interno."00$num_consecutivo";
        
    } else if($num_consecutivo==9999){
        return $codigo_interno."0$num_consecutivo";
    } else return "sin numero";
}
?>