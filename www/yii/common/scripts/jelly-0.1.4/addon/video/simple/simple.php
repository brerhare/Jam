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
	private $loop = "";
	private $poster = "";
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
				case "loop":
					$this->loop = $val;
					break;
				case "poster":
					$this->poster = $val;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Is this a mobile?
		$isMobile = detectMobile();
		$jsVideoControl = "";
		$playControl = " autoplay ";
		if ($this->loop != "")
			$playControl .= " loop ";
		if ($this->poster != "")
			$playControl .= " poster='" . $this->poster . "' ";

		if ($isMobile)
		{
			$this->apiJs  = str_replace("<substitute-is-mobile>", "var isMobile=1;", $this->apiJs);
			$playControl = " controls ";
			if ($this->loop != "")
				$playControl .= " loop ";
			if ($this->poster != "")
				$playControl .= " poster='" . $this->poster . "' ";
			//$jsVideoControl = " data-setup='{}' class='video-js vjs-default-skin' ";
			$jsVideoControl = " class='video-js vjs-default-skin' ";						// Only use the css, not the js
		}
		else
		{
			$this->apiJs  = str_replace("<substitute-is-mobile>", "var isMobile=0;", $this->apiJs);
		}
		$this->apiHtml = str_replace("<substitute-controls>", $playControl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-js-video-controls>", $jsVideoControl, $this->apiHtml);

		$this->apiHtml = str_replace("<substitute-video>", Yii::app()->getBaseUrl(true) . $this->video, $this->apiHtml);
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
		if ($this->clickFunction == "playVideo")
			$this->apiHtmls .= "<script> $(document).ready(function(){ playVideo(); }); </script>";

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

		<link rel="stylesheet" href="<substitute-path>/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="<substitute-path>/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

		<link rel="stylesheet" href="<substitute-path>/video-js/video-js.min.css" type="text/css" media="screen" />
		<script type="text/javascript" src="<substitute-path>/video-js/video.js"></script>

		<div id="jelly-video-simple-container" class="fancybox">
		<div id='displayBox' style='height:<substitute-height>px; width:<substitute-width>px;'>
				<video id="html5Vid" width=<substitute-width> height=<substitute-height> <substitute-js-video-controls> <substitute-controls> >
					<source src='<substitute-video>.m4v' type='video/mp4'>
					<source src='<substitute-video>.mp4' type='video/mp4'>
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

	<substitute-is-mobile>

	$(document).ready(function(){
		var video = document.getElementsByTagName('video')[0];
		//video.currentTime = 0;	// Firefox doesnt like this here (not loaded properly yet?). So only do it when play is clicked
		video.pause();	//ont want the damn thing to play until asked
    });

	<substitute-click-function> = function() {
		var video = document.getElementsByTagName('video')[0];
		video.currentTime = 0;
		//if (!(isMobile))
			//video.play();	// This will fail on mobiles UNLESS a play has already happened. So differentiate to remove weirdness
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
