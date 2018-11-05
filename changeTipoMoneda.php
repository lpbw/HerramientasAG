<?
    //Recibe changeTipoMoneda(obj producto, tipo de moneda, valor de la moneda)
    function changeTipoMoneda($prod, $moneda_a_cambiar_es_usa, $valor_moneda)
    {
        $producto = $prod;
        /**
         * tipo de moneda
         * 0 = mxn
         * 1 = usa
         */
        //valida si el tipo de moneda es mxn y el tipo de moneda recibido es usa.
        if($producto->tipo_moneda_usa == 0 && $moneda_a_cambiar_es_usa == 1)
        {
            $producto ->precio =  $producto -> precio_original / floatval($valor_moneda);
        }
        //valida si el tipo de moneda es usa y el tipo de moneda recibido es mx.
        else if($producto->tipo_moneda_usa == 1 && $moneda_a_cambiar_es_usa==0)
        {
            $producto ->precio = floatval($producto -> precio_original) * floatval($valor_moneda);
        }
        //si el tipo de moneda es igual.
        else if($producto->tipo_moneda_usa == $moneda_a_cambiar_es_usa)
        {
            $producto ->precio = floatval($producto -> precio_original);
        }
        //var_dump($producto->precio);
        return floatval($producto->precio);
    }
    //var_dump($producto->tipo_moneda_usa);
?>