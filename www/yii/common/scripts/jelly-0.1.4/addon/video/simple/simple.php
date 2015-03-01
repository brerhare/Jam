<?php

require ('detectMobile.php');

/**
 * API for a simple video viewer in a jquery thingie
 *
 * Notes
 * -----
 * None
 */

class simple
{
	//Defaults
	private $width = "800";
	private $height = "600";
	private $video = "";						// Dont supply an extension. If one is supplied then remove it
	private $clickFunction = "playVideo";		// Trigger

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
			$val = str_replace("px", "", $val);
			$val = str_replace("%", "", $val);
			$val = trim($val);

			switch ($opt)
			{
				case "width":
					$this->width = $val;
					break;
				case "height":
					$this->height = $val;
					break;
				case "video":
					$vidArr = explode(".", $val);
					$this->video = $vidArr[0];			// Lose any extension
					break;
				case "click":
					$this->clickFunction = $val;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Is this a mobile?
		$isMobile = detectMobile();
		$playControl = " autoplay ";
		if ($isMobile)
			$playControl = " controls ";
		$this->apiHtml = str_replace("<substitute-controls>", $playControl, $this->apiHtml);

		$this->apiHtml = str_replace("<substitute-video>", Yii::app()->getBaseUrl(true) . "/userdata/jelly/video/" . $this->video, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-width>", $this->width, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-height>", $this->height, $this->apiHtml);

		$this->apiJs   = str_replace("<substitute-width>", $this->width, $this->apiJs);
		$this->apiJs   = str_replace("<substitute-height>", $this->height, $this->apiJs);

		// Apply all defaults that werent overridden
		// -----------------------------------------

		// HTML
		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

		// JS
		$this->apiJs   = str_replace("<substitute-click-function>", $this->clickFunction, $this->apiJs);

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

		<link rel="stylesheet" href="<substitute-path>/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="<substitute-path>/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

		<div id="jelly-video-simple-container" class="fancybox">
		<div id='displayBox' style='height:<substitute-height>px; width:<substitute-width>px;'>
				<video width=<substitute-width> height=<substitute-height> <substitute-controls> >
					<source src='<substitute-video>.m4v' type='video/mp4'>
					<source src='<substitute-video>.webm' type='video/webm'>
					<source src='<substitute-video>.ogv' type='video/ogg'>
					"Your browser does not support the video tag."
				</video>
			</div>
		</div>
		<style>
		.fancybox-skin { background-color: #CFFFFF; }
		.fancybox-inner { overflow-x: hidden !important; }
		</style>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	$(document).ready(function(){
		var video = document.getElementsByTagName('video')[0];
		//video.currentTime = 0;	// Firefox doesnt like this here (not loaded properly yet?). So only do it when play is clicked
		video.pause();	// Dont want the damn thing to play until asked
    });

	<substitute-click-function> = function() {
		var video = document.getElementsByTagName('video')[0];
		video.currentTime = 0;
		video.play();	// This will only work on desktop. Detected mobiles get the controls
		$.fancybox(
       		$('#displayBox').html(),
       		{
				'width': '<substitute-width>',
				'height': '<substitute-height>',
				'autoDimensions': false,
				padding: '0px',
				//'modal': true,
				afterClose: function() {
					pauseVid();
				},
         	}
		);
	}

	$(document).ready(function() { 
	}); 

	var video = document.getElementsByTagName('video')[0];

	// Autoclose when video ends
	pauseVid = function() {
		if ((video.ended == false) && (video.paused == false))
			video.pause();
	}
	video.onended = function(e) {
		document.getElementById('displayBox').style.visibility="hidden";
		$.fancybox.close();
	};


END_OF_API_JS;

}
?>
