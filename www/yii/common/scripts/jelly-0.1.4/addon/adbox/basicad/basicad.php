<?php

class basicad
{

	private $_imageDir = '/../userdata/jelly/adblock/';

	//Defaults
	private $defaultPicWidth = "180";
	private $defaultPicHeight = "180";
	private $defaultPicSpacing = "5";
	private $defaultNumPics = 3;
	private $defaultInterval = 60;

	Public function init ($options, $jellyRootUrl)
	{
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "picwidth":
					$this->defaultPicWidth = str_replace("px", "", $val) . "px";
					break;
				case "picheight":
					$this->defaultPicHeight = str_replace("px", "", $val) . "px";
					break;
				case "picspacing":
					$this->defaultPicSpacing = str_replace("px", "", $val) . "px";
					break;
				case "numpics":
					$this->defaultNumPics = $val;
					break;
				case "interval":
					$this->defaultInterval = $val;
					break;
			}
		}
	
		// Build up the html

		$content = "";
        $content .= "<table style='border-spacing:0px; padding:0px; margin:0px; margin-left:-1px'>";
		$cnt = 0;
		$adBlocks = JellyAdblock::model()->findAll(array('order'=>'RAND()'));
		foreach ($adBlocks as $adBlock):
            $content .= "<tr><td  style='padding-bottom:10px' height='" . $this->defaultPicHeight . "'>";
			$content .= "<input type=hidden id='id-" . $cnt . "' value='" . $adBlock->id . "'>";
			if (trim($adBlock->url) != "")
				$content .= "<a id='url-" . $cnt . "' href='" . $adBlock->url . "' target='_blank'>";
            $content .= "<img id='img-" . $cnt . "' src='" . Yii::app()->baseUrl . $this->_imageDir . $adBlock->image . "' style='width:" . $this->defaultPicWidth . "; height:" . $this->defaultPicHeight . "; border:0px solid black' alt=''>";
			if (trim($adBlock->url) != "")
				$content .= "</a>";
            $content .= "</td></tr>";
			if (++$cnt >= $this->defaultNumPics)
				break;
        endforeach;
        $content .= "</table>";

		// Record the call dir for ajax calls
		$content .= "<script> var ajaxUrl='" . Yii::app()->getBaseUrl(true) . "/backend.php/jellyAdblock/ajaxGetAds';</script>";

		// Set the ad rotator timer
		$content .= "<script> var ajaxInterval=" . ($this->defaultInterval * 1000) . ";</script>";

		// Record the number of items for ajax calls
		$content .= "<script> var ajaxCount='" . $cnt . "';</script>";

        $this->apiHTML = str_replace("<substitute-data>", $content, $this->apiHTML);

		// HTML substitutions
		if (strstr($this->apiHTML, "<substitute-width>"))
			$this->apiHTML = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHTML);
		
		if (strstr($this->apiHTML, "<substitute-font-size>"))
			$this->apiHTML = str_replace("<substitute-font-size>", "font-size:" . $this->defaultFontSize . ";", $this->apiHTML);
		
		if (strstr($this->apiJS, "<substitute-text-space>"))
			$this->apiJS = str_replace("<substitute-text-space>", "height:" . $this->defaultTextSpace . ",", $this->apiJS);
		
		if (strstr($this->apiHTML, "<substitute-tape-color>"))
			$this->apiHTML = str_replace("<substitute-tape-color>", "background-color:" . $this->defaultTapeColor . ";", $this->apiHTML);
		
		// JS substitutions

		$retArr = array();
		$retArr[0] = $this->apiHTML;
		$retArr[1] = $this->apiJS;
		return $retArr;

	}

private $apiHTML = <<<END_OF_API_HTML
<!-- <div id="jelly-adbox> -->
            <!--Adbox-->
			<substitute-data>
<!-- </div> -->
END_OF_API_HTML;

private $apiJS = <<<END_OF_API_JS
$(document).ready(function() {
//	document.getElementById("img-0").src = document.getElementById("img-1").src;
//	document.getElementById("url-0").href = 'ddddd';
});

setInterval(ajaxGetAds, ajaxInterval);

// Ajax call to get the event details when a header is clicked
function ajaxGetAds() { 
	//alert('about to call for ad updates');
	ids = [];
	for (i = 0; i < ajaxCount; i++)
		ids[i] = document.getElementById("id-" + i).value;
//alert('sending ids ' + ids[0] + ' ' + ids[1] + ' ' + ids[2]);
    jQuery.ajax({'url':ajaxUrl,'data':{'count':ajaxCount, 'ids':ids},'type':'POST','dataType':'json','success':function(val){ajaxShowAds(val);},'cache':false});
}

var nextChange = 0;

// Return from Ajax call with event details
var ajaxShowAds = function(val) {

//alert('back. nextChange is ' + nextChange + '. ajaxCount is ' + ajaxCount + '. lengths are: id='+val.id.length+' url='+val.url.length+' img='+val.img.length );
//alert('id that came back is ' + val.id[0]);
	for (i = 0; i < val.id.length; i++)
	{
		tval = "id-" + nextChange;
		document.getElementById(tval).value = val.id[i];
		tval = "img-" + nextChange;
		document.getElementById(tval).src = '/../userdata/jelly/adblock/' + val.img[i];
		tval = "url-" + nextChange;
		document.getElementById(tval).href = val.url[i];
		nextChange++;
		if (nextChange == (ajaxCount))
			nextChange = 0;
	}
}

END_OF_API_JS;

}
?>
