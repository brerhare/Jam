<?php

/**
 * API for Hoverzoom
 *
 * Notes
 * -----
 * None
 */

class hoverzoom
{
	//Defaults
	private $defaultWidth = '100px';
	private $defaultHeight = '100px';

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
                case "image":
					$this->apiHtml = str_replace("<substitute-image>", Yii::app()->baseUrl . $val, $this->apiHtml);
					break;
                case "thumb":
					$this->apiHtml = str_replace("<substitute-thumb>", Yii::app()->baseUrl . $val, $this->apiHtml);
					break;
                case "width":
					$defaultWidth = str_replace("px", "", $val) . "px";
					break;
                case "height":
					$defaultHeight = str_replace("px", "", $val) . "px";
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
			$this->apiHtml = str_replace("<substitute-width>", $this->defaultWidth, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-height>"))
			$this->apiHtml = str_replace("<substitute-height>", $this->defaultHeight, $this->apiHtml);

		// JS

		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

<style>
#element {
    z-index: 12000;
}
</style>

		<div id="jelly-hoverzoom-container">
			<!--HoverZoom-->

			<script src="<substitute-path>/jquery.hoverZoom.js"></script>
			<link rel="stylesheet" href="<substitute-path>/jquery.hoverZoom.css">

			<ul class="thumb">
				<li><a href="<substitute-image>" /><img src="<substitute-thumb>" /></a></li>
			</ul>

		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
	});

	$(document).ready(function(){
	/*  $('.thumb img').hoverZoom();    */
		$('.thumb img').hoverZoom({speedView:600, speedRemove:400, showCaption:true, speedCaption:600, debug:true, hoverIntent: true, loadingIndicatorPos: 'center'});
    });


$(document).ready(function() {

       $.modal("<img src='/product/userdata/'>");
alert('x');
});



END_OF_API_JS;

}
?>
