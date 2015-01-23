<?php

/**
 * API for Parallax Slider--
 *
 * Notes
 * -----
 * For best results don't set the height/width of your container
 *
 * Remember you have 4 areas to add your Jelly code inserts.
 * 	A) the default settings
 *  B) the jelly word defined
 *  Either D) or E) for the substition tags (CSS subs go in the HTML area) 
 *  F) the API for either the HTML or the JS
 */

class parallax
{
	// (A) Declare some sensible default values for all options
	// --------------------------------------------------------

	private $defaultSliderHeight = "400px";
	private $defaultBackgroundImage =  "/scripts/jelly/addon/slider/parallax/images/slider-background.jpg";
	private $defaultContentWidth = "90%";
	private $defaultContentLeft = "5%";
	private $defaultTitleTextColor = "#fff";
	private $defaultTitleFontSize = "48px";
	private $defaultTitleMarginTop = "20px";
	private $defaultTitleShadow = "2px 2px 2px";
	private $defaultTitleShadowOpacity = "0.5";
	private $defaultTitleFont = "'Verdana', Arial, sans-serif";
	private $defaultTitleWeight = "600";
	private $defaultTitleStyle = "normal";
	private $defaultBlurbMarginTop = "100px";
	private $defaultBlurbColor = "black";
	private $defaultBlurbSize = "18px";
	private $defaultBlurbLineHeight = "26px";
	private $defaultBlurbHeight = "90px";
	private $defaultBlurbStyle = "normal";
	private $defaultBlurbFont = "'Verdana', Arial, sans-serif";
	private $defaultBlurbWeight = "400";
	private $defaultImageMarginTop = "50px";
	private $defaultLinkMarginTop = "220px";
	private $defaultLinkImg = "/scripts/jelly/addon/slider/parallax/images/link.png";
	private $defaultLinkHover = "/scripts/jelly/addon/slider/parallax/images/link-hover.png";
	private $defaultDotTop = "200px";
	private $defaultDotColor = "lightblue";
	private $defaultDotAlignment = "center";
	private $defaultDotMarginLeft = "0%";
	private $defaultNavMargin = "25px";
	private $defaultNavPrev = "/scripts/jelly/addon/slider/parallax/images/arrow-left.png";
	private $defaultNavNext = "/scripts/jelly/addon/slider/parallax/images/arrow-right.png";
	private $defaultNavPrevHover = "/scripts/jelly/addon/slider/parallax/images/arrow-left-hover.png";
	private $defaultNavNextHover = "/scripts/jelly/addon/slider/parallax/images/arrow-right-hover.png";
	
	private $defaultInterval = 10;
	private $defaultImageWidth = 150;
	private $defaultImageHeight = 150;

		

	public function init($options, $jellyRootUrl)
	{
		// (B) Override declared default values with any Jelly supplied values
		// -------------------------------------------------------------------

		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
			// .da-slider class
				case "slider-height":
					$this->defaultSliderHeight = $val;
					break;
				case "background-image":
					$this->defaultBackgroundImage = $val;
					break;
			// .da-slide
				case "content-width":
					$this->defaultContentWidth = $val;
					break;
				case "content-left":
					$this->defaultContentLeft = $val;
					break;
			// .da-slide h2
				case "title-text-colour":
					$this->defaultTitleTextColor = $val;
					break;
				case "title-text-color":
					$this->defaultTitleTextColor = $val;
					break;
				case "title-font-size":
					$this->defaultTitleFontSize = $val;
					break;
				case "title-margin-top":
					$this->defaultTitleMarginTop = $val;
					break;
				case "title-shadow":
					$this->defaultTitleShadow = $val;
					break;
				case "title-shadow-opacity":
					$this->defaultTitleShadowOpacity = $val;
					break;
				case "title-font":
					$this->defaultTitleFont = $val;
					break;
				case "title-weight":
					$this->defaultTitleWeight = $val;
					break;
				case "title-style":
					$this->defaultTitleStyle = $val;
					break;
			// .da-slide p
				case "blurb-margin-top":
					$this->defaultBlurbMarginTop = $val;
					break;
				case "blurb-color":
					$this->defaultBlurbColor = $val;
					break;
				case "blurb-colour":
					$this->defaultBlurbColor = $val;
					break;
				case "blurb-size":
					$this->defaultBlurbSize = $val;
					break;
				case "blurb-line-height":
					$this->defaultBlurbLineHeight = $val;
					break;
				case "blurb-height":
					$this->defaultBlurbHeight = $val;
					break;
				case "blurb-style":
					$this->defaultBlurbStyle = $val;
					break;
				case "blurb-font":
					$this->defaultBlurbFont = $val;
					break;
				case "blurb-weight":
					$this->defaultBlurbWeight = $val;
					break;
			// .da-slide .da-img
				case "image-margin-top":
					$this->defaultImageMarginTop = $val;
					break;
			// .da-slide .da-link
				case "link-margin-top":
					$this->defaultLinkMarginTop = $val;
					break;
				case "link-image":
					$this->defaultLinkImg = $val;
					break;
			// .da-slide .da-link:hover
				case "link-hover":
					$this->defaultLinkHover = $val;
					break;
			// .da-dots
				case "dot-top":
					$this->defaultDotTop = $val;
					break;
				case "dot-alignment";
					$this->defaultDotAlignment = $val;
					break;
				case "dot-margin-left";
					$this->defaultDotMarginLeft = $val;
					break;
			// .da-dots span
				case "dot-color":
					$this->defaultDotColor = $val;
					break;
				case "dot-colour":
					$this->defaultDotColor = $val;
					break;
			//.da-arrows...
				case "nav-prev":
					$this->defaultNavPrev = $val;
					break;
				case "nav-next":
					$this->defaultNavNext = $val;
					break;
				case "nav-margin":
					$this->defaultNavMargin = $val;
					break;
				case "nav-prev-hover":
					$this->defaultNavPrevHover = $val;
					break;
				case "nav-next-hover":
					$this->defaultNavNextHover = $val;
					break;
			
				
				case "interval":
					$this->defaultInterval = $val;
					break;
				case "imagewidth":
					$this->defaultImageWidth = $val;
					break;
				case "imageheight":
					$this->defaultImageHeight = $val;
					break;

			
			
				default:
					// Not all array items are action items
			}
		}


		// (C) Construct some HTML (like the originally downloaded index.html) but with our own database content and declared/overridden defaults
		//     (This will replace a <substitute-xxxxx> tag like any other substitutions)
		// -------------------------------------------------------------------------------------------------------------------------------------

		$sliderItems = JellySliderImage::model()->findAll(array('order'=>'sequence'));
		$content .= "<div id='da-slider' class='da-slider'>";
		foreach ($sliderItems as $sliderItem):
			$content .= "<div class='da-slide'>";
			$content .= 	"<h2>" . $sliderItem->title . "</h2>";
			$content .= 	"<p>" . $sliderItem->text . "</p>";
			$content .= 	"<a href='" . $sliderItem->url . "' class='da-link'></a>";
			$content .= 	"<div class='da-img'><img width='" . $this->defaultImageWidth . "' height='" . $this->defaultImageHeight . "' src='" . "/userdata/jelly/sliderimage/" . $sliderItem->image. "' alt='' /></div>";
			$content .= "</div>";
		endforeach;
		$content .= 	"<nav class='da-arrows'>";
		$content .=		 	"<span class='da-arrows-prev'></span>";
		$content .=		 	"<span class='da-arrows-next'></span>";
		$content .=		 "</nav>";
		$content .= "</div>";

		// (D) Make sure all <substitute-xxxx> tags have been substituted in $apiHtml
		// --------------------------------------------------------------------------
		
	// .da-slider
		if (strstr($this->apiHtml, "<substitute-slider-height>"))
			$this->apiHtml = str_replace("<substitute-slider-height>", $this->defaultSliderHeight, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-background-image>"))
			$this->apiHtml = str_replace("<substitute-background-image>", $this->defaultBackgroundImage, $this->apiHtml);
		
	// .da-slide
		if (strstr($this->apiHtml, "<substitute-content-width>"))
			$this->apiHtml = str_replace("<substitute-content-width>", $this->defaultContentWidth, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-content-left>"))
			$this->apiHtml = str_replace("<substitute-content-left>", $this->defaultContentLeft, $this->apiHtml);
	
	// .da-slide h2
		if (strstr($this->apiHtml, "<substitute-title-text-color>"))
			$this->apiHtml = str_replace("<substitute-title-text-color>", $this->defaultTitleTextColor, $this->apiHtml);	
		if (strstr($this->apiHtml, "<substitute-title-font-size>"))
			$this->apiHtml = str_replace("<substitute-title-font-size>", $this->defaultTitleFontSize, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-title-margin-top>"))
			$this->apiHtml = str_replace("<substitute-title-margin-top>", $this->defaultTitleMarginTop, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-title-shadow>"))
			$this->apiHtml = str_replace("<substitute-title-shadow>", $this->defaultTitleShadow, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-title-shadow-opacity>"))
			$this->apiHtml = str_replace("<substitute-title-shadow-opacity>", $this->defaultTitleShadowOpacity, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-title-font>"))
			$this->apiHtml = str_replace("<substitute-title-font>", $this->defaultTitleFont, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-title-weight>"))
			$this->apiHtml = str_replace("<substitute-title-weight>", $this->defaultTitleWeight, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-title-style>"))
			$this->apiHtml = str_replace("<substitute-title-style>", $this->defaultTitleStyle, $this->apiHtml);
	
	// .da-slide p
		if (strstr($this->apiHtml, "<substitute-blurb-margin-top>"))
			$this->apiHtml = str_replace("<substitute-blurb-margin-top>", $this->defaultBlurbMarginTop, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-color>"))
			$this->apiHtml = str_replace("<substitute-blurb-color>", $this->defaultBlurbColor, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-size>"))
			$this->apiHtml = str_replace("<substitute-blurb-size>", $this->defaultBlurbSize, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-line-height>"))
			$this->apiHtml = str_replace("<substitute-blurb-line-height>", $this->defaultBlurbLineHeight, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-height>"))
			$this->apiHtml = str_replace("<substitute-blurb-height>", $this->defaultBlurbHeight, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-style>"))
			$this->apiHtml = str_replace("<substitute-blurb-style>", $this->defaultBlurbStyle, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-font>"))
			$this->apiHtml = str_replace("<substitute-blurb-font>", $this->defaultBlurbFont, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-blurb-weight>"))
			$this->apiHtml = str_replace("<substitute-blurb-weight>", $this->defaultBlurbWeight, $this->apiHtml);

	//.da-slide .da-img
		if (strstr($this->apiHtml, "<substitute-image-margin-top>"))
			$this->apiHtml = str_replace("<substitute-image-margin-top>", $this->defaultImageMarginTop, $this->apiHtml);
		
	// .da-slide .da-link
		if (strstr($this->apiHtml, "<substitute-link-margin-top>"))
			$this->apiHtml = str_replace("<substitute-link-margin-top>", $this->defaultLinkMarginTop, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-link-image>"))
			$this->apiHtml = str_replace("<substitute-link-image>", $this->defaultLinkImg, $this->apiHtml);	
	
	// .da-slide .da-link:hover
		if (strstr($this->apiHtml, "<substitute-link-hover>"))
			$this->apiHtml = str_replace("<substitute-link-hover>", $this->defaultLinkHover, $this->apiHtml);

	// .da-dots
		if (strstr($this->apiHtml, "<substitute-dot-top>"))
			$this->apiHtml = str_replace("<substitute-dot-top>", $this->defaultDotTop, $this->apiHtml);	
		if (strstr($this->apiHtml, "<substitute-dot-alignment>"))
			$this->apiHtml = str_replace("<substitute-dot-alignment>", $this->defaultDotAlignment, $this->apiHtml);	
		if (strstr($this->apiHtml, "<substitute-dot-margin-left>"))
			$this->apiHtml = str_replace("<substitute-dot-margin-left>", $this->defaultDotMarginLeft, $this->apiHtml);
	// .da-dots span
		if (strstr($this->apiHtml, "<substitute-dot-color>"))
			$this->apiHtml = str_replace("<substitute-dot-color>", $this->defaultDotColor, $this->apiHtml);	
			
	//.da-arrows...
		if (strstr($this->apiHtml, "<substitute-nav-next>"))
			$this->apiHtml = str_replace("<substitute-nav-next>", $this->defaultNavNext, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-nav-prev>"))
			$this->apiHtml = str_replace("<substitute-nav-prev>", $this->defaultNavPrev, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-nav-margin>"))
			$this->apiHtml = str_replace("<substitute-nav-margin>", $this->defaultNavMargin, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-nav-next-hover>"))
			$this->apiHtml = str_replace("<substitute-nav-next-hover>", $this->defaultNavNextHover, $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-nav-prev-hover>"))
			$this->apiHtml = str_replace("<substitute-nav-prev-hover>", $this->defaultNavPrevHover, $this->apiHtml);
			

		// (E) Make sure all <substitute-xxxx> tags have been substituted in $apiJs
		// --------------------------------------------------------------------------

		if (strstr($this->apiJs, "<substitute-interval>"))
			$this->apiJs = str_replace("<substitute-interval>", ($this->defaultInterval * 1000), $this->apiJs);


		// Send the html and js to the jelly processor
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$html = str_replace("<substitute-data>", $content, $tmp);
		$js = $this->apiJs;
		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// (F) Make sure you have added your substitute 
	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-parallax-slider-container">
            <!--Parallax Slider-->
			<script type="text/javascript" src="<substitute-path>/js/modernizr.custom.28468.js"></script>
			<script type="text/javascript" src="<substitute-path>/js/jquery.cslider.js"></script>
			<link rel="stylesheet" type="text/css" href="<substitute-path>/css/style.css" />

			<style>
				.da-slider {
					height: <substitute-slider-height> !important;
					background: url(<substitute-background-image>) repeat 0% 0%;
							}
				
				.da-slide {
					width: <substitute-content-width>;
					left: <substitute-content-left>;
							}
				
				.da-slide h2 { 
					color: <substitute-title-text-color> !important; 
					font-size: <substitute-title-font-size>;
					top: <substitute-title-margin-top>;
					text-shadow: <substitute-title-shadow> rgba(0,0,0,<substitute-title-shadow-opacity>);
					font-family: <substitute-title-font> !important;
					font-weight: <substitute-title-weight>;
					font-style: <substitute-title-style>;
							}
							
				.da-slide p{
					top: <substitute-blurb-margin-top>;
					color: <substitute-blurb-color>;
					font-size: <substitute-blurb-size>;
					line-height: <substitute-blurb-line-height>;
					height: <substitute-blurb-height>;
					font-style: <substitute-blurb-style>;
					font-family: <substitute-blurb-font>;
					font-weight: <substitute-blurb-weight>;
							}
							
				.da-slide .da-img{
					top: <substitute-image-margin-top>;
							}
							
				.da-slide .da-link{
					top: <substitute-link-margin-top>;
					background: url(<substitute-link-image>) no-repeat top left;
							}
				
				.da-slide .da-link:hover{
					background: url(<substitute-link-hover>) no-repeat top left;
							}
				
				.da-dots {
					top: <substitute-dot-top>;
					text-align: <substitute-dot-alignment>;
					margin-left: <substitute-dot-margin-left>;
							}
							
				.da-dots span {
					background: <substitute-dot-color>;
							}
				
				.da-arrows span.da-arrows-prev{
					left: <substitute-nav-margin>;
					background: url(<substitute-nav-prev>) no-repeat top left;
							}
				
				.da-arrows span.da-arrows-prev:hover{
					background:  url(<substitute-nav-prev-hover>) no-repeat top left; 
							}
							
				.da-arrows span.da-arrows-next{
					right: <substitute-nav-margin>;
					background: url(<substitute-nav-next>) no-repeat top right;
							}
				
				.da-arrows span.da-arrows-next:hover{
					background:  url(<substitute-nav-next-hover>) no-repeat top right; 
							}
				
			</style>

			<substitute-data>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
		// Put any startup code in here
	});

	$('#da-slider').cslider({
		autoplay : true,
		interval : <substitute-interval>,
	});

END_OF_API_JS;

}
?>
