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
	//Defaults
	private $defaultWidth = "900px";
	private $defaultHeight = "250px";
	private $defaultInterval = 10;
	private $defaultImageWidth = 150;
	private $defaultImageHeight = 150;

	public function init($options, $jellyRootUrl)
	{
//		var_dump( $options );

		// Generate the content into the html, replacing any <substituteN> tags
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
				default:
					// Not all array items are action items
			}
		}


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

/****
$content .= "<a href='" . $sliderItem->url . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/sliderimage/" . $sliderItem->image . "' style='margin:0px; width:" . $this->defaultWidth . "; height:" . $this->defaultHeight . "; background: url(/userdata/jelly/sliderimage/" . $sliderItem->image  . " no-repeat center center; background-size:cover;' alt=''></a>";
****/

		// Apply all defaults that werent overridden

/******************
		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
			$this->apiHtml = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-height>"))
			$this->apiHtml = str_replace("<substitute-height>", "height:" . $this->defaultHeight . ";", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-border-width>", $this->defaultBorderWidth,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-border-color>", $this->defaultBorderColor,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-active-dotcolor>", $this->defaultActiveDotColor,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-inactive-dotcolor>", $this->defaultInactiveDotColor,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-top>", "0px",  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-bottom>", "0px",  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-left>", "0px",  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-right>", "0px",  $this->apiHtml);
******************/

		// JS
		if (strstr($this->apiJs, "<substitute-interval>"))
		{
			$tmp = str_replace("<substitute-interval>", ($this->defaultInterval * 1000), $this->apiJs);
			$this->apiJs = $tmp;
		}

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

			<substitute-data>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){

	});

			$('#da-slider').cslider({
			autoplay : true,
			interval : <substitute-interval>,
		});


END_OF_API_JS;

}
?>
