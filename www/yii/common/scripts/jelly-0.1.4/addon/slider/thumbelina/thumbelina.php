<?php

/**
 * API for addons
 *
 * Notes
 * -----
 * None
 */

class thumbelina
{
	//Defaults
	private $defaultOrientation = "horizontal";		// 'vertical' or 'horizontal'
	private $defaultWidth = "auto";
	private $defaultHeight = "auto";

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
					$this->defaultWidth = $val . "px";
					break;
				case "height":
					$this->defaultHeight = $val . "px";
					break;
				default:
					// Not all array items are action items
			}
		}

		// Content
		$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
		foreach ($sliderItems as $sliderItem):
			$content .= "<li> <img class='thumbelinaThumb' src='" . Yii::app()->baseUrl . "/userdata/jelly/sliderimage/" . $sliderItem->image . "'> </li>";
		endforeach;

		// Subsitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-orientation>", $this->defaultOrientation, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-height>", $this->defaultHeight, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-width>", $this->defaultWidth, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		if ($this->defaultOrientation == "horizontal")
		{
			$substituteA = " horiz left ";
			$substituteB = " horiz right ";
		}
		else
		{
			$substituteA = " vert top ";
			$substituteB = " vert bottom ";
		}
		$this->apiHtml = str_replace("<substitute-A>", $substituteA, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-B>", $substituteB, $this->apiHtml);

		// JS
		$this->apiJs = str_replace("<substitute-orientation>", $this->defaultOrientation, $this->apiJs);


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

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

        <style>
        #thumbelinaSliderControl {
            position:relative;
            margin-top:40px;
            width:93px;
            height:256px;
            border-left:1px solid #aaa;
            border-right:1px solid #aaa;
            margin-bottom:40px;
        }
        .thumbelinaThumb {
            width:80px;
            Xheight:80px;
        }
        </style>

        <div id="jelly-thumbelina-container">
            <!--Thumbelina Slider-->
            <link rel="stylesheet" href="<substitute-path>/thumbelina.css" type="text/css">
            <script src="<substitute-path>/thumbelina.js"></script>

			
            <!-- <div id="thumbelinaSliderFrame" style="height:<substitute-height>; width:<substitute-width>; overflow:hidden"> -->
            <div id="thumbelinaSliderFrame" style="height:auto; overflow:hidden">
                <div  id="thumbelinaSliderControl">
                    <div class="thumbelina-but <substitute-A>">&#708;</div>
                        <ul>
                            <substitute-data>
                        </ul>
                    <div class="thumbelina-but <substitute-B>">&#709;</div>
                </div>
            <div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	// Any custom js and/or startup functions

    $(function(){
        $('#thumbelinaSliderControl').Thumbelina({
            orientation:'<substitute-orientation>',  // horizontal (default) or vertical
            \$bwdBut:$('#thumbelinaSliderControl .top'),     // Selector to top button.
            \$fwdBut:$('#thumbelinaSliderControl .bottom')   // Selector to bottom button.
        });
    })

END_OF_API_JS;

}
?>
