<?php

/**
 * API for Flexslider
 *
 * Notes
 * -----
 * For best results dont set the height/width of your container
 */

class parallax
{
	// (A) Declare some sensible default values for all options
	// --------------------------------------------------------

	private $defaultWidth = "100%";
	private $defaultHeight = "250px";
	private $defaultInterval = 10;
	private $defaultImageWidth = 150;
	private $defaultImageHeight = 150;
	private $defaultTitleTextColor = "#ffffff";

	public function init($options, $jellyRootUrl)
	{
		// (B) Override declared default values with any Jelly supplied values
		// -------------------------------------------------------------------

		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "width":
					$this->defaultWidth = $val;
					break;
				case "height":
					$this->defaultHeight = $val;
					break;
				case "interval":
					$this->defaultInterval = $val;
					break;
				case "imagewidth":
					$this->defaultImageWidth = $val;
					break;
				case "imageheight":
					$this->defaultImageHeight = $val;
					break;
				case "title-text-color":
					$this->defaultTitleTextColor = $val;
					break;
				default:
					// Not all array items are action items
			}
		}


		// (C) Construct some HTML (like the originally downloaded index.html) but with our own database content and declared/overridden defaults
		//     (This will replace a <substitute-xxxxx> tag like any other substitutions)
		// -------------------------------------------------------------------------------------------------------------------------------------

		$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
		$content .= "<div id='da-slider' class='da-slider'>";
		foreach ($sliderItems as $sliderItem):
			$content .= "<div class='da-slide'>";
			$content .= 	"<h2>" . $sliderItem->title . "</h2>";
			$content .= 	"<p>" . $sliderItem->text . "</p>";
			$content .= 	"<a href='" . $sliderItem->url . "' class='da-link'>Read more</a>";
			$content .= 	"<div class='da-img'><img width='" . $this->defaultImageWidth . "' height='" . $this->defaultImageHeight . "' src='" . "/userdata/jelly/sliderimage/" . $sliderItem->image. "' alt='' /></div>";
			$content .= "</div>";
		endforeach;
		$content .= 	"<nav class='da-arrows'>";
		$content .=		 	"<span class='da-arrows-prev'></span>";
		$content .=		 	"<span class='da-arrows-next'></span>";
		$content .=		 "</nav>";
		$content .= "</div>";

		// (D) Make sure all <substitute-xxxx> tags have been substituted in $apiHtml
		// --------------------------------------------------------------------------
		if (strstr($this->apiHtml, "<substitute-title-text-color>"))
			$this->apiHtml = str_replace("<substitute-title-text-color>", $this->defaultTitleTextColor, $this->apiHtml);


		// (E) Make sure all <substitute-xxxx> tags have been substituted in $apiJs
		// --------------------------------------------------------------------------

		if (strstr($this->apiJs, "<substitute-interval>"))
			$this->apiJs = str_replace("<substitute-interval>", ($this->defaultInterval * 1000), $this->apiJs);


		// Send the html and js to the jelly processor
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$html = str_replace("<substitute-data>", $content, $tmp);
		$js = $this->apiJs;
		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-parallax-slider-container">
            <!--Parallax Slider-->
			<script type="text/javascript" src="<substitute-path>/js/modernizr.custom.28468.js"></script>
			<script type="text/javascript" src="<substitute-path>/js/jquery.cslider.js"></script>
			<link rel="stylesheet" type="text/css" href="<substitute-path>/css/style.css" />

			<script>
				.da-slide h2 { color: <substitute-title-text-color>; }
			</script>

			<substitute-data>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
		// Put any startup code in here
	});

	$('#da-slider').cslider({
		autoplay : true,
		interval : <substitute-interval>,
	});

END_OF_API_JS;

}
?>
