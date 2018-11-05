<?
function changeLanguage($prod, $changeLanguage){
    if($changeLanguage == 'ESP')
    $producto = $prod;
    
    return floatval($producto->precio);
}
?>