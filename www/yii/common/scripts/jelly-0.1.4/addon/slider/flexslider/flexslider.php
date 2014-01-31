<?php

/**
 * API for Flexslider
 *
 * Notes
 * -----
 * For best results dont set the height/width of your container
 */

class flexslider
{
	//Defaults
	private $defaultMode = 'html';		// html, image
	private $defaultWidth = "900px";
	private $defaultHeight = "250px";
	private $defaultAnimation = "fade";
	private $defaultBorderWidth = "4";
	private $defaultBorderColor = "#fff";
	private $defaultActiveDotColor = "rgba(0,0,0,0.9)";
	private $defaultInactiveDotColor = "rgba(0,0,0,0.5)";

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
				case "width":
					$this->defaultWidth = $val;
					break;
				case "height":
					$this->defaultHeight = $val;
					break;
				case "mode":
					if (strtoupper($val) == "IMAGE")
						$this->defaultMode = 'image';
					break;
				case "border-width":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-border-width>", $val, $this->apiHtml);
					break;
				case "dot-margin-top":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-dot-margin-top>", $val, $this->apiHtml);
					break;
				case "dot-margin-bottom":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-dot-margin-bottom>", $val, $this->apiHtml);
					break;
				case "dot-margin-left":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-dot-margin-left>", $val, $this->apiHtml);
					break;
				case "dot-margin-right":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-dot-margin-right>", $val, $this->apiHtml);
					break;
				case "active-dotcolor":
					$this->apiHtml = str_replace("<substitute-active-dotcolor>", $val, $this->apiHtml);
					break;
				case "inactive-dotcolor":
					$this->apiHtml = str_replace("<substitute-inactive-dotcolor>", $val, $this->apiHtml);
					break;
				case "border-color":
					$this->apiHtml = str_replace("<substitute-border-color>", $val, $this->apiHtml);
					break;
				case "source":
					if ($this->defaultMode == 'image')
					{
						if ($val == "db")
						{
							// If db based content
							$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
							foreach ($sliderItems as $sliderItem):
								$content .= "<li>";

$content .= "<a href='" . $sliderItem->url . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/sliderimage/" . $sliderItem->image . "' style='margin:0px; width:" . $this->defaultWidth . "; height:" . $this->defaultHeight . "; background: url(/userdata/jelly/sliderimage/" . $sliderItem->image  . " no-repeat center center; background-size:cover;' alt=''></a>";

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
					}
					else	// HTML
					{
						if ($val == "db")
						{
							// If db based content
							$sliderItems = JellySliderHtml::model()->findAll(array('order'=>'sequence'));
							foreach ($sliderItems as $sliderItem):
								$content .= "<li>";
								$content .= $sliderItem->content;
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
					$tmp = str_replace("<substitute-animation>", "'" . $val . "'", $this->apiJs);
					$this->apiJs = $tmp;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
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

		// JS
		if (strstr($this->apiJs, "<substitute-animation>"))
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

/* This is a direct override of flexslider css */
.flexslider {margin: 0 0 60px; background: #fff; border: <substitute-border-width>px solid <substitute-border-color>; position: relative; -webkit-border-radius:<substitute-border-width>px; -moz-border-radius: <substitute-border-width>px; -o-border-radius: <substitute-border-width>px; border-radius: <substitute-border-width>px; box-shadow: 0 1px <substitute-border-width>px rgba(0,0,0,.2); -webkit-box-shadow: 0 1px <substitute-border-width>px rgba(0,0,0,.2); -moz-box-shadow: 0 1px <substitute-border-width>px rgba(0,0,0,.2); -o-box-shadow: 0 1px <substitute-border-width>px rgba(0,0,0,.2); zoom: 1;}

/* This is a direct override of flexslider css */
.flex-control-paging li a.flex-active { margin-top: <substitute-dot-margin-top>px; margin-bottom: <substitute-dot-margin-bottom>px; margin-left: <substitute-dot-margin-left>px; margin-right: <substitute-dot-margin-right>px; background: #000; background: <substitute-active-dotcolor>; cursor: default; }
.flex-control-paging li a {margin-top: <substitute-dot-margin-top>px; margin-bottom: <substitute-dot-margin-bottom>px; margin-left: <substitute-dot-margin-left>px; margin-right: <substitute-dot-margin-right>px; width: 11px; height: 11px; display: block; background: #666; background: <substitute-inactive-dotcolor>; 

			<substitute-border-width>

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
