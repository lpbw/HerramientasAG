// JavaScript Document
function setViewCurrency(objSrcId,objTargetid){
    var number = parseFloat(document.getElementById(objSrcId).value);
    if(!isNaN(number)){
        number = number.toFixed(2);
        var thousands  = parseInt(number/1000);
        var hundreds = number - thousands*1000;
        
        var pre="";
        if(hundreds<100 && thousands>=1)
            pre="0";
	
        var result = "$0";
        if(thousands >= 1){
            result =  "$" + thousands + "," + pre + hundreds.toFixed(2);
        } else result =  "$"+pre+hundreds;

        document.getElementById(objTargetid).innerHTML = result;
    }
}