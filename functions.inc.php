<?php

function get_data($url)
{
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  curl_setopt($ch,CURLOPT_TIMEOUT,$timeout); 
  
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}



/**
* ********************* ********************
*/


function stripHttp($website,$removeWWW=false) {
    $ret_str = str_replace("http://", "", strtolower($website));
    $ret_str = str_replace("https://", "", $ret_str);

	if ($removeWWW==true)
	{
		$ret_str=preg_replace("|^www.|", '',$ret_str);
	}
	
    return $ret_str;
}


/**
* ***
*/

function extractTLD( $domain )
{
    $productTLD = '';
    $tempstr = explode(".", $domain);
    unset($tempstr[0]);
    foreach($tempstr as $value){
        $productTLD = $productTLD.".".$value;
    }    
	$productTLD = substr($productTLD,1);
    return $productTLD;
}

?>