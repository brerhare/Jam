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
						$content .= "<ul id='tabs'>";
						$cnt = 0;
						$tabItems = CarouselBlock::model()->findAll(array('order'=>'sequence'));
						foreach ($tabItems as $tabItem):
							$content .= "<li><a href='#tab" . ++$cnt . "'>" . $tabItem->title . "</a></li>";
						endforeach;
						$content .= "</ul>";
						$cnt = 0;
						foreach ($tabItems as $tabItem):
							$content .= "<div class='container' id='tab" . ++$cnt . "'>";
							$content .= "XXXXX";
							$content .= "</div>";
						endforeach;
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
		$('.container:not(:first)').hide();	
	
		$('#tabs li a').click(function(){		
			var t = $(this).attr('href');
			if($(this).hasClass('inactive')){ //added to not animate when active
				$('#tabs li a').addClass('inactive');		
				$(this).removeClass('inactive');
				$('.container').hide();
				$(t).fadeIn('slow');	
			}			
			return false;
		}) //end click
	
	
	
});

END_OF_API_JS;

}
?>
