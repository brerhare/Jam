<?php

/**
 * API for Flexslider
 *
 * Notes
 * -----
 * For best results dont set the height/width of your container
 */

class Api
{
	//Defaults
	private $defaultWidth = "900px";
	private $defaultHeight = "250px";
	private $defaultAnimation = "fade";

	public $apiOption = array(
		"width" => "900px",
		"height" => "400px",
		"animation" => "fade",
		"source" => "db | glob",
		"(db) sql" => "CarouselBlock::model()->findAll(array('order'=>'sequence'))",
		"(db) column" => "content",
		"(glob) pattern" => "/userdata/images/*.jpg",
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
				case "source":
					if ($val == "db")
					{
						// If db based content
						// @@NB: OI! hardcoded to jacquies here
						$carouselItems = CarouselBlock::model()->findAll(array('order'=>'sequence'));
						foreach ($carouselItems as $carouselItem):
							$content .= "<li>";
							$content .= $carouselItem->content;
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
							$content .= "<img src='" . dirname($pattern) . "/". basename($filename) . "' style='float: none; margin: 0px;' alt=''>";
							$content .= "</li>";
						}
					}
					break;
				case "width":
					$tmp = str_replace("<substitute-width>", "width:" . $val . ";", $this->apiHtml);		// Optional field
					$this->apiHtml = $tmp;
					break;
				case "height":
					$tmp = str_replace("<substitute-height>", "height:" . $val . ";", $this->apiHtml);	// Optional field
					$this->apiHtml = $tmp;
					break;
				case "animation":
					$tmp = str_replace("<substitute-animation>", $val, $this->apiJs);
					$this->apiJs = $tmp;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
		{
			$tmp = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		if (strstr($this->apiHtml, "<substitute-height>"))
		{
			$tmp = str_replace("<substitute-height>", "height:" . $this->defaultHeight . ";", $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		// JS
		if (strstr($this->apiHtml, "<substitute-animation>"))
		{
			$tmp = str_replace("<substitute-animation>", $this->defaultAnimation, $this->apiJs);
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

	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-flexslider-container">
            <!--Flex Slider-->
            <link rel="stylesheet" href="<substitute-path>/flexslider.css" type="text/css">
            <script src="<substitute-path>/jquery.flexslider.js"></script>

            <!-- Flex Slider custom CSS to handle sizing/clipping-->
            <style>
            /* NOTE! height wins in case of conflict */
            .flexslider {
                <substitute-width>   /* Setting this clips the calculated height */
            }
            .slides {
                overflow:hidden;
                <substitute-height> /* Setting this clips the calculated width */
            }
            </style>

            <!--Flex Slider-->
            <div class="flexslider">
                <ul class="slides">
					<substitute-data>
                </ul>
            </div>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){

	    $('.flexslider').flexslider({
	        //itemHeight: 300,
	        //itemWidth: 610,
	        itemMargin: 5,
	        //minItems: 1,
	        //maxItems: 1,
	        animation: <substitute-animation>,
	    });
	});

END_OF_API_JS;

}
?>
