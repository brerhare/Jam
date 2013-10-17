<?php

/**
 * API for Product filter
 *
 * Notes
 * -----
 * This will use the width and height of your container
 */

class product
{
	//Defaults
	private $department = "";

// Not used ...
	public $apiOption = array(
		"width" => "900px",
		"height" => "400px",
		"animation" => "fade | slide",
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
				case "department":
					$department = $val;
					break;
				default:
					break;
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
			$tmp = str_replace("<substitute-animation>", "'" . $this->defaultAnimation . "'", $this->apiJs);
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
