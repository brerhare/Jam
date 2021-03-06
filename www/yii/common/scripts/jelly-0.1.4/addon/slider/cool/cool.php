<?php

/**
 * API for addons
 *
 * Notes
 * -----
 * None
 */

class cool
{
	//Defaults
	private $defaultOrientation = "horizontal";		// 'vertical' or 'horizontal'
	private $defaultHeight = "500";
	private $defaultWidth = "800";
	private $defaultDuration = "3000";

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing html[0] and js[1]
	 */

	public function init($options, $jellyRootUrl)
	{
//		var_dump( $options );

		// Generate the content into the html, replacing any <substituteN> tags
		$content = "";
		foreach ($options as $opt => $val)
		{
			$val = str_replace("px", "", $val);
			$val = str_replace("%", "", $val);
			$val = trim($val);

			switch ($opt)
			{
				case "orientation":
					$this->defaultOrientation = $val;
					break;
				case "width":
					$this->defaultWidth = $val;
					break;
				case "height":
					$this->defaultHeight= $val;
					break;
				case "duration":
					$this->defaultDuration= ($val * 1000);
					break;
				default:
					// Not all array items are action items
			}
		}

		// Images
		$images = "";
		$thumbs = "";
		$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
		$base = Yii::app()->baseUrl . "/userdata/jelly/sliderimage/";
		foreach ($sliderItems as $sliderItem):
			$images .= "<img src='" . $base . $sliderItem->image . "' width='" . $this->defaultWidth. "px' height='" . $this->defaultHeight . "px' />";
			$thumbs .= "<img src='" . $base . $sliderItem->image . "' width='" . 80 . "' height='" . 80 . "' />";
		endforeach;

		// Subsitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-height>", $this->defaultHeight, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-width>", $this->defaultWidth, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-min-height>", ($this->defaultHeight + 100), $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-images>", $images, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-duration>", $this->defaultDuration, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-thumbs>", $thumbs, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

		// JS
		//$this->apiJs = str_replace("<substitute-orientation>", $this->defaultOrientation, $this->apiJs);


		// Apply all defaults that werent overridden
		// HTML

		// JS

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-cool-container" style="position:relative; min-height:<substitute-min-height>px">

            <link rel="stylesheet" href="<substitute-path>/cool.css" type="text/css">
            <script src="<substitute-path>/jquery.carouFredSel.js"></script>

			<script>
				/* CarouFredsel settings */
				var paramWidth = <substitute-width>;
				var paramHeight = <substitute-height>;
				var paramDuration = <substitute-duration>;
			</script>
            <script src="<substitute-path>/cool.js"></script>

			<style>
			/* Overrides */
			#carousel {
    			width: <substitute-width>px;
    			height: <substitute-height>px;
    			overflow: hidden;
			} 
			</style>

            <div id="cool-wrapper">
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



        </div>    <!-- jelly-cool-container -->

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	$(document).ready(function(){
	});

END_OF_API_JS;

}
?>
