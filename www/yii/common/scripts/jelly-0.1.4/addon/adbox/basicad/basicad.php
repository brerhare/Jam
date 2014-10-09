<?php

class basicad
{
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
					$this->defaultPicWidth = $val;
					break;
				case "picheight":
					$this->defaultPicHeight = $val;
					break;
				case "picspacing":
					$this->defaultPicSpacing = $val;
					break;
				case "numpics":
					$this->defaultNumPics = $val;
					break;
			}
		}
	
		// Build up the html
/*****
		$content = "";
		$tickerItems = JellyTicker::model()->findAll(array('order'=>'id'));
		foreach ($tickerItems as $tickerItem):
			$textLine = $tickerItem->text;
			if (strlen($tickerItem->url) > 0)
				$textLine = "<a href='" . $tickerItem->url . "' target='_blank'>" . $tickerItem->text . "</a>";
			$content .= "<dt>" . $tickerItem->heading . "</dt>";
			$content .= "<dd>" . $textLine . "</dd>";
		endforeach;
		$this->apiHTML = str_replace("<substitute-data>", $content, $this->apiHTML);
*****/


		$content = "";
        $pattern = "/img/*";
        $content .= "<table>";
		$cnt = 0;
        foreach (glob(Yii::app()->basePath . "/../" . $pattern) as $filename)
        {
            $content .= "<tr><td  style='padding-bottom:10px' height='" . $this->defaultPicHeight . "px'>";
			$content .= "<a href='http://www.google.com' target='_blank'>";
            $content .= "<img src='" . Yii::app()->baseUrl . dirname($pattern) . "/". basename($filename) . "' style='width=" . $this->defaultPicWidth . "; height:" . $this->defaultPicHeight . "; border:0px solid black' alt=''>";
			$content .= "</a>";

            $content .= "</td></tr>";
			if (++$cnt >= $this->defaultNumPics)
				break;
        }
        $content .= "</table>";
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
/*****
Anything for startup in here
*****/
});
END_OF_API_JS;

}
?>
