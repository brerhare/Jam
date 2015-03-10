<?php

function deviceInfo()
{
	//Detect special conditions devices
	$AndroidPhone = false;
	$AndroidTablet = false;
	$WindowsPhone = false;
	$WindowsTablet = false;
	$iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$BlackBerry9down = stripos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$BlackBerry10 = stripos($_SERVER['HTTP_USER_AGENT'],"BB10");
	$RimTablet = stripos($_SERVER['HTTP_USER_AGENT'],"RIM Tablet");
	$NokiaSymbian = stripos($_SERVER['HTTP_USER_AGENT'],"SymbianOS");
	if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")){ 
    	if(stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
        	$AndroidPhone = true;
        	$AndroidTablet = false;
    	}else{
        	$AndroidPhone = false;
        	$AndroidTablet = true;
    	}
	}
	if(stripos($_SERVER['HTTP_USER_AGENT'],"Windows")){
    	if(stripos($_SERVER['HTTP_USER_AGENT'],"Touch")){
        	$WindowsTablet = true;
    	}else{
        	$WindowsPhone = false;
    	}
    	if(stripos($_SERVER['HTTP_USER_AGENT'],"Windows Phone")){
        	$WindowsPhone = true;
    	}else{
        	$WindowsTablet = false;
    	}
	}

	//do something with this information
	$deviceWidth = 1366;	// Typical laptop
	if( $iPod || $iPhone ){ //we're an iPhone/iPod touch
		$deviceWidth = 768;
	}else if($iPad){ //we're an iPad
		$deviceWidth = 768;
	}else if($AndroidPhone){ //we're an Android Phone
		$deviceWidth = 768;
	}else if($AndroidTablet){ //we're an Android Tablet
		$deviceWidth = 768;
	}else if($WindowsPhone){ //we're an Windows Phone
		$deviceWidth = 768;
	}else if($WindowsTablet){ //we're an Windows Tablet
		$deviceWidth = 768;
	}else if($webOS){ //we're a webOS device
		$deviceWidth = 768;
	}else if($BlackBerry9down){ //we're an outdated BlackBerry phone
		$deviceWidth = 768;
	}else if($BlackBerry10){ //we're an new BlackBerry phone
		$deviceWidth = 768;
	}else if($RimTablet){ //we're a RIM/BlackBerry Tablet
		$deviceWidth = 768;
	}else if($NokiaSymbian){ //we're a Nokia Symbian device
		$deviceWidth = 768;
	}

	return $deviceWidth;
}

?>
