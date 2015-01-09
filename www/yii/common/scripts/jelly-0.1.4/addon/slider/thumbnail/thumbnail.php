<?php

/**
*Original source code found: http://coolcarousels.frebsite.nl/c/61/
*
*
*/

class thumbnail
{
	//Defaults
	private $defaultWidth = "100%";
	private $defaultHeight = "100%";
	
	
	Public function init ($options, $jellyRootUrl)
	{
		//var_dump( $options );
		
		// Generate the content into the HTML, replacing any <substituteN> tags
		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{

//				case "jellyword":
//					$this-> defined default as above or the jelly value
				case "width":
					$this->defaultWidth = $val;
					break;
				case "height":
					$this->defaultHeight = $val;
					break;
				
			}
		}
	
		// Database content
		$images="";
		$thumbs="";
			$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
			foreach ($sliderItems as $sliderItem):


$images .= "<img src='/userdata/jelly/sliderimage/" . $sliderItem->image . "' width='800' height='500' />";
$thumbs .= "<img src='/userdata/jelly/sliderimage/" . $sliderItem->image . "' width='80' height='80' />";				
				
//$content .= "<a href='" . $sliderItem->url . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/sliderimage/" . $sliderItem->image . "' style='margin:0px; width:" . $this->defaultWidth . "; height:" . $this->defaultHeight . "; background: url(/userdata/jelly/sliderimage/" . $sliderItem->image  . " no-repeat center center; background-size:cover;' alt=''></a>";


			endforeach;


		$this->apiHTML = str_replace("<substitute-data>", $content, $this->apiHTML);
		$this->apiHTML = str_replace("<substitute-images>", $images, $this->apiHTML);
		$this->apiHTML = str_replace("<substitute-thumbs>", $thumbs, $this->apiHTML);

	
//Apply all values to the HTML and/or JS
	//HTML
	
//	if (strstr(the string <substitute-jellyword> exists in the relevant API
//		Then in the relevant API replace <substitute-jellyword> with, "HTML / CSS / JS command" . ";", (full stop to append) $this-> defaultValue (default value variable will now either have the default value specified above or the jelly specified value . ";", $this->api and you will find it in the relevant API. 
	
	if (strstr($this->apiHTML, "<substitute-width>"))
		$this->apiHTML = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-height>"))
		$this->apiHTML = str_replace("<substitute-height>", "font-size:" . $this->defaultFontSize . ";", $this->apiHTML);
		
	/*if (strstr($this->apiJS, "<substitute-text-space>"))
		$this->apiJS = str_replace("<substitute-text-space>", "height:" . $this->defaultTextSpace . ",", $this->apiJS);*/
		
	$this->apiHTML = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHTML);
	
	$retArr = array();
	$retArr[0] = $this->apiHTML;
	$retArr[1] = $this->apiJS;
	return $retArr;

	}

private $apiHTML = <<<END_OF_API_HTML

<script src="<substitute-path>/jquery.carouFredSel.js"></script>
<script src="<substitute-path>/thumbnail.js"></script>

<div id="wrapper">
	<div id="inner">
		<div id="carousel-wrapper">
			<div id="carousel">
				<substitute-images>
			</div>
		</div>
		<div id="pager-wrapper">
			<div id="pager">
				<substitute-thumbs>
			</div>
		</div>
	</div>
	
	<style>
		#wrapper > div {
						<substitute-height>
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

	
	
</div>




END_OF_API_HTML;

private $apiJS = <<<END_OF_API_JS

	// Any custom js and/or startup functions

$(document).ready(function() {
$('.caroufredsel_wrapper').css('width', '100%');
/*****
    $('#ticker-1').carouFredSel({
        items               : <substitute-text-space>,
    }); 
*****/
});

END_OF_API_JS;

}
?>
