<?php

class basicad
{

	private $_imageDir = '/../userdata/jelly/adblock/';

	//Defaults
	private $defaultPicWidth = "180";
	private $defaultPicHeight = "180";
	private $defaultPicSpacing = "5";
	private $defaultNumPics = 3;

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
			}
		}
	
		// Build up the html

		$content = "";
        $content .= "<table style='border-spacing:0px; padding:0px; margin:0px; margin-left:-1px'>";
		$cnt = 0;
		$adBlocks = JellyAdblock::model()->findAll(array('order'=>'id'));
		foreach ($adBlocks as $adBlock):
            $content .= "<tr><td  style='padding-bottom:10px' height='" . $this->defaultPicHeight . "'>";
			$content .= "<input type=hidden id='id-" . $cnt . "' value='" . $adBlock->id . "'>";
			$content .= "<a id='url-" . $cnt . "' href='" . $adBlock->url . "' target='_blank'>";
            $content .= "<img id='img-" . $cnt . "' src='" . Yii::app()->baseUrl . $this->_imageDir . $adBlock->image . "' style='width:" . $this->defaultPicWidth . "; height:" . $this->defaultPicHeight . "; border:0px solid black' alt=''>";
			$content .= "</a>";
            $content .= "</td></tr>";
			if (++$cnt >= $this->defaultNumPics)
				break;
        endforeach;
        $content .= "</table>";

		// Record the image dir for ajax calls
		$content .= "<script> var ajaxUrl='" . Yii::app()->getBaseUrl(true) . "/backend.php/jellyAdblock/ajaxGetAds';</script>";

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
	document.getElementById("img-0").src = document.getElementById("img-1").src;
	document.getElementById("url-0").href = 'ddddd';
ajaxGetAds('1-2-3');
});

// Ajax call to get the event details when a header is clicked
var ajaxGetAds = function(paneId) { 
    var ev = paneId.split("-");
    var evIx = ev[ev.length - 1];
    jQuery.ajax({'url':ajaxUrl,'data':{'paneId':paneId, 'eventId':'111'},'type':'POST','dataType':'json','success':function(val){ajaxShowAds(val);},'cache':false});
};

// Return from Ajax call with event details
var ajaxShowAds = function(val) {
    //alert('Server sent ' + val.paneId + ' and ' + val.eventId);
    //$('#' + val.paneId).html(val.content);
    //FB.XFBML.parse();
}

END_OF_API_JS;

}
?>
