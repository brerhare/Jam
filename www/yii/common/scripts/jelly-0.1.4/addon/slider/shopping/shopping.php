<?php

/**
 * API for shopping--
 *
 * Notes
 * -----
 * For best results don't set the height/width of your container
 *
 * http://coolcarousels.frebsite.nl/c/47/
 */

class shopping
{
	// (A) Declare some sensible default values for all options
	// --------------------------------------------------------

	private $defaultSliderHeight = "400px";
	private $defaultNavPrevious = "/scripts/jelly/addon/slider/shopping/img/ui-prev.png";
	private $defaultNavNext = "/scripts/jelly/addon/slider/shopping/img/ui-next.png";
	private $defaultNavMargin = "-520px";
	private $defaultImages = 5;

	public function init($options, $jellyRootUrl)
	{
		// (B) Override declared default values with any Jelly supplied values
		// -------------------------------------------------------------------

		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "slider-height":
					$this->defaultSliderHeight = $val;
					break;
				case "image-previous":
					$this->defaultNavPrevious = $val;
					break;
				case "image-next":
					$this->defaultNavNext = $val;
					break;
				case "images":
					$this->defaultImages = $val;
					break;
				case "nav-margin":
					$this->defaultNavMargin = $val;
					break;
					
				default:
					// Not all array items are action items
			}
		}


		// (C) Construct some HTML (like the originally downloaded index.html) but with our own database content and declared/overridden defaults
		//     (This will replace a <substitute-xxxxx> tag like any other substitutions)
		// -------------------------------------------------------------------------------------------------------------------------------------


			
		$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
		$content .= "<div id='shopping-wrapper'>";
		$content .= "  <div id='shopping-inner'>";
		$content .= "    <div id='carousel'>";
		
		foreach ($sliderItems as $sliderItem):
			$content .= "<div class='inner-product-block' style=height:230px;overflow:hidden>";
			$content .= 	"<a href='" . $sliderItem->url . "' >";
			$content .= "       <img src='" . "/userdata/jelly/sliderimage/" . $sliderItem->image . "' width='140' height='200' />";
			$content .=         "<em>" . $sliderItem->title . "</em>";
			$content .= "     </a>";
			$content .= "</div>";
		endforeach;
			$content .= "    </div>";

			$content .='
                <a href="#" id="prev"></a>
                <a href="#" id="next"></a>
			';


			$content .= "  </div>";
			$content .= "</div>";

		// (D) Make sure all <substitute-xxxx> tags have been substituted in $apiHtml
		// --------------------------------------------------------------------------
		
		if (strstr($this->apiHtml, "<substitute-slider-height>"))
			$this->apiHtml = str_replace("<substitute-slider-height>", $this->defaultSliderHeight, $this->apiHtml);
		
		if (strstr($this->apiHtml, "<substitute-image-previous>"))
			$this->apiHtml = str_replace("<substitute-image-previous>", $this->defaultNavPrevious, $this->apiHtml);
		
		if (strstr($this->apiHtml, "<substitute-image-next>"))
			$this->apiHtml = str_replace("<substitute-image-next>", $this->defaultNavNext, $this->apiHtml);
		
		if (strstr($this->apiHtml, "<substitute-nav-margin>"))
			$this->apiHtml = str_replace("<substitute-nav-margin>", $this->defaultNavMargin, $this->apiHtml);
		
		$this->apiHtml = str_replace("<substitute-images>", $this->defaultImages, $this->apiHtml);
				

		// (E) Make sure all <substitute-xxxx> tags have been substituted in $apiJs
		// --------------------------------------------------------------------------

		//if (strstr($this->apiJs, "<substitute-interval>"))
			//$this->apiJs = str_replace("<substitute-interval>", ($this->defaultInterval * 1000), $this->apiJs);


		// Send the html and js to the jelly processor
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$html = str_replace("<substitute-data>", $content, $tmp);
		$js = $this->apiJs;
		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// (F) Make sure you have added your substitute 
	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-shopping-slider-container" style="overflow:hidden";>
            <!--Shopping Slider-->
			<script src="<substitute-path>/jquery.carouFredSel-6.0.4-packed.js" type="text/javascript"></script>
			<script type="text/javascript" src="<substitute-path>/shopping.js"></script>
			
			<link rel="stylesheet" type="text/css" href="<substitute-path>/shopping.css" />
			
			<script>
			imageNum = <substitute-images>;	/* Override */
			</script>

			<style>
			
				#prev {
					background: url(<substitute-image-previous>) no-repeat;
					margin-left: <substitute-nav-margin>;
						}
				
				#next {
					background: url(<substitute-image-next>) no-repeat;
					margin-right: <substitute-nav-margin>;
						}
			
			</style>

			<substitute-data>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
		// Put any startup code in here
	});


END_OF_API_JS;

}
?>
