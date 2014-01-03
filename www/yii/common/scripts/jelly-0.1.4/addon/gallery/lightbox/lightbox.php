<?php

/**
 * API for Lightbox2
 *
 * Notes
 * -----
 * None
 */

class lightbox
{
	//Defaults
	//private $defaultWidth = '100px';
	//private $defaultHeight = '100px';
	private $defaultGroup = 'nogroup';

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
                case "group":
					$this->apiHtml = str_replace("<substitute-group>", Yii::app()->baseUrl . $val, $this->apiHtml);
					break;
                case "width":
					$width = str_replace("px", "", $val) . "px";
					$this->apiHtml = str_replace("<substitute-width>", " width $width ", $this->apiHtml);
					break;
                case "height":
					$height = str_replace("px", "", $val) . "px";
					$this->apiHtml = str_replace("<substitute-height>", " height=$height ", $this->apiHtml);
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
		if (strstr($this->apiHtml, "<substitute-group>"))
			$this->apiHtml = str_replace("<substitute-group>", $this->defaultGroup, $this->apiHtml);

		// JS

		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// 0 is inlineHtml
		// 1 is inline JS
		// 2 is clipboard
		// 3 is header html
		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		$retArr[2] = "";	// Clipboard
		$retArr[3] = "<script src=$jellyRootUrl/js/lightbox-2.6.min.js></script>";
		$retArr[3] .= "<link href=$jellyRootUrl/css/lightbox.css rel='stylesheet' />";
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

<style>
#element {
    z-index: 12000;
}
</style>

		<div id="jelly-lightbox2-container">
			<!--Lightbox2-->

			<a href="<substitute-image>" data-lightbox="<substitute-group>" title=""><img src="<substitute-thumb>" <substitute-width> <substitute-height> /></a>
		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
	});

END_OF_API_JS;

}
?>
