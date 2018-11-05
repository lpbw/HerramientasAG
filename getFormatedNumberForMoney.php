<?
    function getFormatedNumberForMoney($n)
    {
        $number = floatval($n);
        $nf = floatval($number);
        $ni = intval($number);
        
        return number_format($number,2,".",",");
    }
	
    function getStrNumberOf($number)
    {
        $number = intval($number);    
        if($number < 1000)
        {
            if($number < 100)
            {
                if($number < 10)
                {
                    return "00$number";
                } 
                else
                {
                    return "0$number";
                }      
            } 
            else
            {
                return "$number"; 
            }
                    
        } 
        else 
        {
            return $number;
        }
    }
?>