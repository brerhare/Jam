<?php

/**
 * API for JQuery Rain Tabs
 *
 * URL: http://www.jqueryrain.com/?hqttCwkg
 *
 * Notes
 * -----
 * None
 *
 */

class rain
{
	//Defaults
	private $defaultValue = "900px";

	public $apiOption = array(
		"width" => "900px",
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
				case "source":
					if ($val == "db")
					{
						// If db based content
						// @@NB: OI! hardcoded to jacquies here
						$content .= "<div id='rain-wrapper'>";
						$content .= "<ul id='rain-tabs'>";
						$cnt = 0;
						$tabItems = TabBlock::model()->findAll(array('order'=>'sequence'));
						foreach ($tabItems as $tabItem):
							$content .= "<li><a href='#rain-tab" . ++$cnt . "'>" . $tabItem->title . "</a></li>";
						endforeach;
						$content .= "</ul>";
						$cnt = 0;
						foreach ($tabItems as $tabItem):
							$content .= "<div class='rain-tabcontent-container' id='rain-tab" . ++$cnt . "'>";
							$content .= $tabItem->content;
							$content .= "</div>";
						endforeach;
						$content .= "</div>";
					}
					else if ($val == "glob")
					{
						// get pattern
						$pattern = $options['pattern'];
						foreach (glob(Yii::app()->basePath . "/../" . $pattern) as $filename)
						{
							$content .= "<li>";
							$content .= "<img src='" . dirname($pattern) . "/". basename($filename) . "' style='float: none; margin: 0px;' alt=''>";
							$content .= "</li>";
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
		if (strstr($this->apiHtml, "<substitute-something>"))
		{
			$tmp = str_replace("<substitute-something>", $this->defaultValue, $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		// JS

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;

		// Insert the data
		$html = str_replace("<substitute-data>", $content, $this->apiHtml);
		$js = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

	<div id="jelly-rain-container">
		<!--JQuery Rain-->
		<link rel="stylesheet" type="text/css" href="<substitute-path>/rain.css" />
		<script type="text/javascript" src="<substitute-path>/rain.js"></script>
		<substitute-data>
	</div> <!-- jelly-rain-container -->

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
		$('#tabs li a:not(:first)').addClass('inactive');
		$('.rain-tabcontent-container:not(:first)').hide();	
	
		$('#tabs li a').click(function(){		
			var t = $(this).attr('href');
			if($(this).hasClass('inactive')){ //added to not animate when active
				$('#tabs li a').addClass('inactive');		
				$(this).removeClass('inactive');
				$('.rain-tabcontent-container').hide();
				$(t).fadeIn('slow');	
			}			
			return false;
		}) //end click

/* Fire the 'Equal Heights Plugin' for all items of class 'rain-tabcontent-container' */
$(".rain-tabcontent-container").equalHeights();

});

/**
 * Equal Heights Plugin
 * Equalize the heights of elements. Great for columns or any elements
 * that need to be the same size (floats, etc).
 * 
 * Version 1.0
 * Updated 12/10/2008
 *
 * Copyright (c) 2008 Rob Glazebrook (cssnewbie.com) 
 *
 * Usage: $(object).equalHeights([minHeight], [maxHeight]);
 * 
 * Example 1: $(".cols").equalHeights(); Sets all columns to the same height.
 * Example 2: $(".cols").equalHeights(400); Sets all cols to at least 400px tall.
 * Example 3: $(".cols").equalHeights(100,300); Cols are at least 100 but no more
 * than 300 pixels tall. Elements with too much content will gain a scrollbar.
 * 
 */
(function($) {
	$.fn.equalHeights = function(minHeight, maxHeight) {
		tallest = (minHeight) ? minHeight : 0;
		this.each(function() {
			if($(this).height() > tallest) {
				tallest = $(this).height();
			}
		});
		if((maxHeight) && tallest > maxHeight) tallest = maxHeight;
		return this.each(function() {
			$(this).height(tallest).css("overflow","auto");
		});
	}
})(jQuery);
/* End Equal Heights plugin */

END_OF_API_JS;

}
?>
