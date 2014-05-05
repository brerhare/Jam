<?php

/**
 * API for Glitz
 *
 * Notes
 * -----
 * For best results dont set the height/width of your container
 */

class glitz
{
	//Defaults
	private $defaultMode = 'html';		// html, image
	private $defaultWidth = "900px";
	private $defaultHeight = "250px";
	private $defaultSlider = -1;

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
				case "slider":
					$this->defaultSlider = $val;
					break;
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
				case "source":
					$content .= '<div id="myCarousel" class="imageflow">';
					if ($this->defaultMode == 'image')
					{
						if ($val == "db")
						{
							// If db based content
							$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
							foreach ($sliderItems as $sliderItem):
								if (($this->defaultSlider != -1) && ($sliderItem->slider != $this->defaultSlider))
									continue;
								$http = 'http://';
								if (strstr($sliderItem->url, 'http://'))
									$http = '';
								$content .= '<img src=' . Yii::app()->basePath . '/../userdata/jelly/sliderimage/' . $sliderItem->image . ' longdesc= "' . $http . $sliderItem->url . '" alt="' . $sliderItem->title . '" />';
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
					$content .= '</div>';
					break;
				case "width":
					$tmp = str_replace("<substitute-width>", "width:" . $val . ";", $this->apiHtml);		// Optional field
					$this->apiHtml = $tmp;
					break;
				case "height":
					$tmp = str_replace("<substitute-height>", "height:" . $val . ";", $this->apiHtml);	// Optional field
					$this->apiHtml = $tmp;
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
		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// JS
		$this->apiJs = str_replace("<substitute-path>", $jellyRootUrl, $this->apiJs);
		//$this->apiJs = str_replace("<substitute-image-path>", Yii::app()->basePath , $this->apiJs);
		$this->apiJs = str_replace("<substitute-image-path>", "" , $this->apiJs);

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-glitz-container">
            <!--Glitz Slider-->
            <link rel="stylesheet" href="<substitute-path>/imageflow/imageflow.css" type="text/css">
            <script src="<substitute-path>/imageflow/imageflow.js"></script>


            <!-- Glitz Slider custom CSS to handle sizing/clipping-->
            <style>
            /* NOTE! height wins in case of conflict */
            .glitzslider {
                <substitute-width>   /* Setting this clips the calculated height */
            }
            .slides {
                overflow:hidden;
                <substitute-height> /* Setting this clips the calculated width */
            }

            </style>

            <!--Glitz Slider-->
            <div class="glitzslider">
					<substitute-data>
            </div>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

domReady(function(){
    var instanceOne = new ImageFlow();
    instanceOne.init({
        ImageFlowID:'myCarousel',
        reflectPath:"<substitute-path>/imageflow/",
        imagePath: "<substitute-image-path>",
        slideshow:true,
        slideshowSpeed:2000,
        slideshowAutoplay:true,
        imageCursor:'pointer',
        circular:true});
    });

    $(".gallerybutton").click(function(){
     window.location=$(this).find("a").attr("href");
     return false;
});

END_OF_API_JS;

}
?>
