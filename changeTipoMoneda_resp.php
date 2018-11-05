<?
function changeTipoMoneda($prod, $moneda_a_cambiar_es_usa, $valor_moneda){
    $producto = $prod;
    
    if($producto->tipo_moneda_usa == 0 && $moneda_a_cambiar_es_usa == 1)
        $producto ->precio =  $producto -> precio_original / floatval($valor_moneda);
    
    else if($producto->tipo_moneda_usa == 1 && $moneda_a_cambiar_es_usa==0)
        $producto ->precio = floatval($producto -> precio_original) * floatval($valor_moneda);
    
    else if($producto->tipo_moneda_usa == $moneda_a_cambiar_es_usa)
        $producto ->precio = floatval($producto -> precio_original);
    
    return floatval($producto->precio);
}
?>