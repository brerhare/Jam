<?php

/**
 * API for Elegant Accordion
 *
 * Notes
 * -----
 * None
 */

class elegant
{
	//Defaults
	private $defaultWidth = "620px";
	private $defaultHeight = "200px";

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
		$onReady = "";
		$inputMode = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "width":
					$tmp = str_replace("<substitute-width>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;
				case "height":
					$tmp = str_replace("<substitute-height>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden

		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
		{
			$tmp = str_replace("<substitute-width>", $this->defaultWidth, $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		if (strstr($this->apiHtml, "<substitute-height>"))
		{
			$tmp = str_replace("<substitute-height>", $this->defaultHeight, $this->apiHtml);
			$this->apiHtml = $tmp;
		}

		// JS

		// Substitute paths for includes
        $tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
        $this->apiHtml = $tmp;

		// Generate the HTML
		$data = "";
		$data .= "<ul class='accordion' id='accordion'>";
		$accordionBlocks = AccordionBlock::model()->findAll(array('order'=>'sequence'));
		foreach ($accordionBlocks as $accordionBlock):
			$data .= "<a href='" . $accordionBlock->url . "'>";
			$data .= "<li style='background-image:url(/userdata/accordion/" . $accordionBlock->image . ");  background-size: cover ;  '>";
            $data .= "<div class='heading'>" . $accordionBlock->title . "</div>";
            $data .= "<div class='bgDescription'></div>";
            $data .= "<div class='description'>";
            $data .= "<h2>" . $accordionBlock->title . "</h2>";
            $data .= "<p>" . $accordionBlock->content . "</p>";
            $data .= "</div>";
            $data .= "</li>";
			$data .= "</a>";
		endforeach;

		$html = str_replace("<substitute-data>", $data, $this->apiHtml);
		$js   = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

//---------------------------------------------------------------------------------------------------------

	private $apiHtml = <<<END_OF_API_HTML

	<div id="jelly-elegant-accordion-container">
		<link rel="stylesheet" href="<substitute-path>/css/style.css" type="text/css" media="screen"/>
		<substitute-data>
	</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS


		$(document).ready(function ()
		{
		});

		$(function() {
            $('#accordion > li').hover(
                function () {
                    var \$this = $(this);
/*kim 480px - to align with the css*/
                    \$this.stop().animate({'width':'281px'},500);
                    $('.heading',\$this).stop(true,true).fadeOut();
                    $('.bgDescription',\$this).stop(true,true).slideDown(500);
                    $('.description',\$this).stop(true,true).fadeIn();
                },
                function () {
                    var \$this = $(this);
/*kim 115px - to align with the css*/
                    \$this.stop().animate({'width':'95px'},1000);
                    $('.heading',\$this).stop(true,true).fadeIn();
                    $('.description',\$this).stop(true,true).fadeOut(500);
                    $('.bgDescription',\$this).stop(true,true).slideUp(700);
                }
            );
        });



END_OF_API_JS;

}
?>
