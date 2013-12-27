<?php

/**
 * API for Fancybox
 *
 * Notes
 * -----
 * None
 */

class fancybox
{
	//Defaults
	private $defaultSource = 'db';

	public $apiOption = array(
	);

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
//		var_dump( $options );

		// Generate the content into the html, replacing any <substituteN> tags
		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "something":
					$this->defaultSomething = $val;
					break;
                case "source":
					if ($val == "db")
					{
						// If db based content
						$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
						foreach ($sliderItems as $sliderItem):
							$content .= "<li>";

$content .= "<img src='" . Yii::app()->baseUrl . "/userdata/jelly/sliderimage/" . $sliderItem->image . "' style='margin:0px; width:" . $this->defaultWidth . "; height:" . $this->defaultHeight . "; background: url(/userdata/jelly/sliderimage/" . $sliderItem->image  . " no-repeat center center; background-size:cover;' alt=''>";

							$content .= "</li>";
						endforeach;
					}
					else if ($val == "glob")
					{
						// get pattern
						$pattern = $options['pattern'];
						foreach (glob(Yii::app()->basePath . "/../" . $pattern) as $filename)
						{
							$content .= "<li>";
							$content .= "<img src='" . Yii::app()->baseUrl . dirname($pattern) . "/". basename($filename) . "' style='float: none; margin: 0px;' alt=''>";
							$content .= "</li>";
						}
					}
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-something>"))
			$this->apiHtml = str_replace("<substitute-something>", $this->defaultSomething, $this->apiHtml);

		// JS
		if (strstr($this->apiJs, "<substitute-animation>"))
		{
			$tmp = str_replace("<substitute-animation>", "'" . $this->defaultAnimation . "'", $this->apiJs);
			$this->apiJs = $tmp;
		}

		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

		<div id="jelly-fancybox-container">
			<!--Fancybox-->

			<!-- Add mousewheel plugin (this is optional) -->
			<script type="text/javascript" src="<substitute-path>/lib/jquery.mousewheel-3.0.6.pack.js"></script>

			<!-- Add fancyBox -->
			<link rel="stylesheet" href="<substitute-path>/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
			<script type="text/javascript" src="<substitute-path>/source/jquery.fancybox.pack.js?v=2.1.5"></script>

			<!-- Optionally add helpers - button, thumbnail and/or media -->
			<link rel="stylesheet" href="<substitute-path>/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
			<script type="text/javascript" src="<substitute-path>//source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
			<script type="text/javascript" src="<substitute-path>/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

			<link rel="stylesheet" href="<substitute-path>/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
			<script type="text/javascript" src="<substitute-path>/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>



<a class="fancybox" rel="group" href="big_image_1.jpg"><img src="small_image_1.jpg" alt="" /></a>
<a class="fancybox" rel="group" href="big_image_2.jpg"><img src="small_image_2.jpg" alt="" /></a>

		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
	});

	$(document).ready(function() {
		$(".fancybox").fancybox();
	});

END_OF_API_JS;

}
?>
