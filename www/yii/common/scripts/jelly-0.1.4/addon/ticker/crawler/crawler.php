<?php

/**
*
*
*
*/

class crawler
{
	//Defaults
	private $defaultWidth = "100%";
	private $defaultFontSize = "14px";
	private $defaultTextSpace = "30";
	private $defaultTapeColor = "#eee";
	private $defaultTapeHeight = "30px";
	private $defaultTextColor = "red";
	private $defaultTapeBorderWidth = "1px";
	private $defaultTapeBorderColor = "#ccc";
	private $defaultLinkColor = "#f66";
	private $defaultLinkTextColor = "white";
	
	Public function init ($options, $jellyRootUrl)
	{
		//var_dump( $options );
		
		// Generate the content into the HTML, replacing any <substituteN> tags
		
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{

//				case "jellyword":
//					$this-> defined default as above or the jelly value
				case "width":
					$this->defaultWidth = $val;
					break;
				case "font-size":
					$this->defaultFontSize = $val;
					break;
				case "text-space":
					$this->defaultTextSpace = $val;
					break;
				case "tape-color":
					$this->defaultTapeColor = $val;
					break;
				case "tape-colour":
					$this->defaultTapeColor = $val;
					break;
				case "tape-height":
					$this->defaultTapeHeight = $val;
					break;
				case "text-color":
					$this->defaultTextColor = $val;
					break;
				case "text-colour":
					$this->defaultTextColor = $val;
					break;
				case "tape-border-width":
					$this->defaultTapeBorderWidth = $val;
					break;
				case "tape-border-color":
					$this->defaultTapeBorderColor = $val;
					break;
				case "tape-border-colour":
					$this->defaultTapeBorderColor = $val;
					break;
				case "link-color":
					$this->defaultLinkColor = $val;
					break;
				case "link-colour":
					$this->defaultLinkColor = $val;
					break;
				case "link-text-color":
					$this->defaultLinkTextColor = $val;
					break;
				case "link-text-colour":
					$this->defaultLinkTextColor = $val;
					break;
			}
		}
	
		// Build up the ticker data
		$content = "";
		$tickerItems = JellyTicker::model()->findAll(array('order'=>'id'));

/*
echo "<div id='wrapper'>";
echo "<div class='second'>
        <dl id='ticker-1'>";
*/

		foreach ($tickerItems as $tickerItem):
			$textLine = $tickerItem->heading;
			if (strlen($tickerItem->url) > 0)
				$textLine = "<a href='" . $tickerItem->url . "' target='_blank'>" . $tickerItem->heading . "</a>";
			$content .= "<dt>" . $textLine . "</dt>";
			$content .= "<dd>" . $tickerItem->text . "</dd>";
		endforeach;

/*
echo "</dl>
    </div>";
echo "</div>";
*/

		$this->apiHTML = str_replace("<substitute-data>", $content, $this->apiHTML);

	
//Apply all values to the HTML and/or JS
	//HTML
	
//	if (strstr(the string <substitute-jellyword> exists in the relevant API
//		Then in the relevant API replace <substitute-jellyword> with, "HTML / CSS / JS command" . ";", (full stop to append) $this-> defaultValue (default value variable will now either have the default value specified above or the jelly specified value . ";", $this->api and you will find it in the relevant API. 
	
	if (strstr($this->apiHTML, "<substitute-width>"))
		$this->apiHTML = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-font-size>"))
		$this->apiHTML = str_replace("<substitute-font-size>", "font-size:" . $this->defaultFontSize . ";", $this->apiHTML);
		
	if (strstr($this->apiJS, "<substitute-text-space>"))
		$this->apiJS = str_replace("<substitute-text-space>", "height:" . $this->defaultTextSpace . ",", $this->apiJS);
		
	if (strstr($this->apiHTML, "<substitute-tape-color>"))
		$this->apiHTML = str_replace("<substitute-tape-color>", "background-color:" . $this->defaultTapeColor . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-tape-height>"))
		$this->apiHTML = str_replace("<substitute-tape-height>", "height: " . $this->defaultTapeHeight . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-text-color>"))
		$this->apiHTML = str_replace("<substitute-text-color>", "color: " . $this->defaultTextColor . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-tape-border-width>"))
		$this->apiHTML = str_replace("<substitute-tape-border-width>", "border-width: " . $this->defaultTapeBorderWidth . ";", $this->apiHTML);
	
	if (strstr($this->apiHTML, "<substitute-tape-border-color>"))
		$this->apiHTML = str_replace("<substitute-tape-border-color>", "border-color: " . $this->defaultTapeBorderColor . ";", $this->apiHTML);	
		
	if (strstr($this->apiHTML, "<substitute-link-color>"))
		$this->apiHTML = str_replace("<substitute-link-color>", "background-color: " . $this->defaultLinkColor . ";", $this->apiHTML);

	if (strstr($this->apiHTML, "<substitute-link-text-color>"))
		$this->apiHTML = str_replace("<substitute-link-text-color>", "color: " . $this->defaultLinkTextColor . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-path>"))
		$this->apiHTML = str_replace("<substitute-path>", $jellyRootUrl , $this->apiHTML);
		
	
	$retArr = array();
	$retArr[0] = $this->apiHTML;
	$retArr[1] = $this->apiJS;
	return $retArr;

	}

private $apiHTML = <<<END_OF_API_HTML
<div id="jelly-crawler-container">
            <!--Crawler Ticker-->
            <link rel="stylesheet" href="<substitute-path>/crawler.css" type="text/css" />
            <script src="<substitute-path>/crawler.js"></script> 
            <script src="<substitute-path>/jquery.carouFredSel.js"></script>

<style>
body * {
		<substitute-font-size>
		}
		
#wrapper > div {
		<substitute-tape-height>
		<substitute-tape-color>
		<substitute-tape-border-width>
		<substitute-tape-border-color>
		}
		
#wrapper dd {
	<substitute-text-color>
	}

#wrapper dt {
	<substitute-link-color>
	<substitute-link-text-color>
	}

</style>

	<div class="crawlerticker">
		<div id="wrapper">
    		<div class="first">
        		<dl id="ticker-1">
					<substitute-data>
				</dl>
    		</div>
		</div>
	</div>

</div>

END_OF_API_HTML;

private $apiJS = <<<END_OF_API_JS

	// Any custom js and/or startup functions

$(document).ready(function() {
/*****
    $('#ticker-1').carouFredSel({
        items               : <substitute-text-space>,
    }); 
*****/
});

END_OF_API_JS;

}
?>
