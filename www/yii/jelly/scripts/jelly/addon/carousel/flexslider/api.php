<?php

/**
 * API for Flexslider
 *
 * Notes
 * -----
 * Dont set the height of your container
 */

class Api
{
	public $apiOption = array(
		//"width" => "900",			unimplemented
		//"height" => "400",		unimplemented
		//"animation" => "fade",	unimplemented
		"source" => "db",
		"sql" => "CarouselBlock::model()->findAll(array('order'=>'sequence'))",
		"column" => "content",
	);

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options)
	{
//		var_dump( $options );

		// Generate the carousel content into the html, replacing any <substituteN> tags
		$content = "";
		$carouselItems = CarouselBlock::model()->findAll(array('order'=>'sequence'));
		foreach ($carouselItems as $carouselItem):
			$content .= "<li>";
			$content .= $carouselItem->content;
			$content .= "</li>";
		endforeach;

		$localCode = str_replace("<substitute1>", $content, $this->apiLocalCode);
		$globalCode = $this->apiGlobalCode;

		$retArr = array();
		$retArr[0] = $localCode;
		$retArr[1] = $globalCode;
		return $retArr;
	}

	// @@TODO: 'source' needs to be extended - image directories, etc.
	// Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiLocalCode = <<<END_OF_API_LOCAL_CODE

        <div id="jelly-flexslider-container">
            <!--Flex Slider-->
            <link rel="stylesheet" href="/js/flexslider/flexslider.css" type="text/css">
            <script src="/js/flexslider/jquery.flexslider.js"></script>

            <!-- Flex Slider custom CSS to handle sizing/clipping-->
            <style>
            /* NOTE! height wins in case of conflict */
            .flexslider {
                width: 900px;   /* Setting this clips the calculated height */
            }
            .slides {
                overflow:hidden;
                height: 250px; /* Setting this clips the calculated width */
            }
            </style>

            <!--Flex Slider-->
            <div class="flexslider">
                <ul class="slides">

<substitute1>

                </ul>
            </div>
        </div>

END_OF_API_LOCAL_CODE;

	private $apiGlobalCode = <<<END_OF_API_GLOBAL_CODE

	jQuery(document).ready(function($){

	    $('.flexslider').flexslider({
	        //itemHeight: 300,
	        //itemWidth: 610,
	        itemMargin: 5,
	        //minItems: 1,
	        //maxItems: 1,
	        animation: "fade",
	    });
	});

END_OF_API_GLOBAL_CODE;

}
?>
