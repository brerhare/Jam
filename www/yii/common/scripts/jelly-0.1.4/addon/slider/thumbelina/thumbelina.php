<?php

/**
 * API for addons
 *
 * Notes
 * -----
 * This includes a separate package - jquery zoom - to show the selected image
 */

class thumbelina
{
	//Defaults
	private $defaultOrientation = "horizontal";		// 'vertical' or 'horizontal'
//	private $defaultThumbs = "left";				// 'left' or 'right'
//	private $defaultWidth = "auto";
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
//				case "width":
//					$this->defaultWidth = $val;
//					break;
				case "height":
					$this->defaultHeight = $val;
					break;
//				case "thumbs":
//					$this->defaultThumbs = $val;
//					break;
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
		$this->apiHtml = str_replace("<substitute-control-height>", ($this->defaultHeight - 40 - 40), $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-image-height>", ($this->defaultHeight - 40), $this->apiHtml);
//		$this->apiHtml = str_replace("<substitute-width>", $this->defaultWidth, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);
//		if ($this->defaultThumbs == 'left')
//		{
//			$this->apiHtml = str_replace("<substitute-hide-left>", "", $this->apiHtml);
//			$this->apiHtml = str_replace("<substitute-hide-right>", " display:none; ", $this->apiHtml);
//		}
//		if ($this->defaultThumbs == 'right')
//		{
//			$this->apiHtml = str_replace("<substitute-hide-right>", "", $this->apiHtml);
//			$this->apiHtml = str_replace("<substitute-hide-left>", " display:none; ", $this->apiHtml);
//		}

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
		$this->apiJs = str_replace("<substitute-path>", $jellyRootUrl, $this->apiJs);


		// Apply all defaults that werent overridden
		// HTML
//		$this->apiHtml = str_replace("<substitute-width>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-height>", "", $this->apiHtml);

		// JS

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-thumbelina-container">

            <!--Thumbelina Slider-->
            <link rel="stylesheet" href="<substitute-path>/thumbelina.css" type="text/css">
            <script src="<substitute-path>/thumbelina.js"></script>
            <script src="<substitute-path>/jqueryZoom/jquery.zoom.js" type="text/javascript"></script>
			
            <style>
            #thumbelinaSliderControl {
                position:relative;
                margin-top:40px;
                width:93px;
                height:<substitute-control-height>px;
                border-left:1px solid #aaa;
                border-right:1px solid #aaa;
                margin-bottom:40px;
            }
            .thumbelinaThumb {
                width:80px;
                Xheight:80px;
            }
			.thumbelina-but {
				border: 1px solid #444;
				color: #444;
				background-color: #fc0;
			}
            .thumbelina-but.disabled, .thumbelina-but.disabled:hover {
                background-color: #fe8;
                color: #444;
                cursor: default;
                box-shadow: none;
            }
            </style>

            <style>
            .zoomIcon {
                width:33px;
                height:33px;
                position:absolute;
                top:0;
                right:0;
                background:url(/zoom/icon.png);
            }
            .zoom {
                display:inline-block;
                position:relative;
            }
            .zoom img {
                display: block;
                max-width:none;
            }
            .zoom p {
                position:absolute;
                top:3px;
                right:28px;
                color:#555;
                font:bold 13px/1 sans-serif;
            }
            </style>

            <div id="thumbelinaSliderFrame" style="height:<substitute-height>; overflow:hidden">
				<!-- jquery-zoom -->
                <div id="zoom-block" style="float:left; height:<substitute-image-height>; padding:20px">
                    <span class='zoom' id='zoom-container'>
                        <p>Hover</p>
                        <img src="/userdata/jelly/sliderimage/poppy bowl.jpg" height='<substitute-image-height>' id='zoom-block-image'/>
                    </span>
                </div>

                <div  id="thumbelinaSliderControl" style="float:left; <substitute-hide-left>">
                    <div class="thumbelina-but <substitute-A>">&#708;</div>
                        <ul>
                            <substitute-data>
                        </ul>
                    <div class="thumbelina-but <substitute-B>">&#709;</div>
                </div>

            </div>    <!-- thumbelinaSliderFrame -->

        </div>    <!-- jelly-thumbelina-container -->

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	// Thumbelina stuff

    $(function(){
        $('#thumbelinaSliderControl').Thumbelina({
            orientation:'<substitute-orientation>',  // horizontal (default) or vertical
            \$bwdBut:$('#thumbelinaSliderControl .top'),     // Selector to top button.
            \$fwdBut:$('#thumbelinaSliderControl .bottom')   // Selector to bottom button.
        });
    })

	// Jquery-zoom stuff

	$(document).ready(function(){
    	$("#zoom-block span img").hover(function(){
        	$('#zoom-block-image').attr({src: $(this).attr('alt')});
        	$('#zoom-container').zoom();
    	});
    	$('#zoom-container').zoom();
    	var imgSwap = [];
    	$("#zoom-block li img").each(function(){
        	imgUrl = this.alt;
        	imgSwap.push(imgUrl);
    	});
    	$(imgSwap).preload();
	});

	$.fn.preload = function(){
    	this.each(function(){
        	$('<img/>')[0].src = this;
    	});
	}



END_OF_API_JS;

}
?>
