<?php

function detectMobile()
{
	$isMobile = false;

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
	if( $iPod || $iPhone ){ //we're an iPhone/iPod touch
		$isMobile = true;
	}else if($iPad){ //we're an iPad
		$isMobile = true;
	}else if($AndroidPhone){ //we're an Android Phone
		$isMobile = true;
	}else if($AndroidTablet){ //we're an Android Tablet
		$isMobile = true;
	}else if($WindowsPhone){ //we're an Windows Phone
		$isMobile = true;
	}else if($WindowsTablet){ //we're an Windows Tablet
		$isMobile = true;
	}else if($webOS){ //we're a webOS device
		$isMobile = true;
	}else if($BlackBerry9down){ //we're an outdated BlackBerry phone
		$isMobile = true;
	}else if($BlackBerry10){ //we're an new BlackBerry phone
		$isMobile = true;
	}else if($RimTablet){ //we're a RIM/BlackBerry Tablet
		$isMobile = true;
	}else if($NokiaSymbian){ //we're a Nokia Symbian device
		$isMobile = true;
	}else{ //we're not a known mobile device
	}

	return $isMobile;
}

?>
