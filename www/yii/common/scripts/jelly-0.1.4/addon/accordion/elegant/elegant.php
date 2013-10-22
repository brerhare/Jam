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


		$html = $this->apiHtml;
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



			<ul class="accordion" id="accordion">
                <li class="bg1">
                    <div class="heading">Guler2</div>
                    <div class="bgDescription"></div>
                    <div class="description">
                        <h2>Guler22</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                            ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                            reprehenderit in voluptate velit esse cillum dolore eu fugiat
                            nulla pariatur.</p>
                        <a href="#">more &rarr;</a>
                    </div>
                </li>
                <li class="bg2">
                    <div class="heading">Phillips</div>
                    <div class="bgDescription"></div>
                    <div class="description">
                        <h2>Phillips</h2>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                            accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
                            quae ab illo inventore veritatis et quasi architecto beatae vitae
                            dicta sunt explicabo. </p>
                        <a href="#">more &rarr;</a>
                    </div>

                </li>
                <li class="bg3">
                    <div class="heading">Diamanti</div>
                    <div class="bgDescription"></div>
                    <div class="description">
                        <h2>Diamanti</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi
                            ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                            reprehenderit in voluptate velit esse cillum dolore eu fugiat
                            nulla pariatur.</p>
                        <a href="#">more &rarr;</a>
                    </div>

                </li>
                <li class="bg4 bleft">
                    <div class="heading">Meiklejohn</div>
                    <div class="bgDescription"></div>
                    <div class="description">
                        <h2>Meiklejohn</h2>
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                            accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
                            quae ab illo inventore veritatis et quasi architecto beatae vitae
                            dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                            sit aspernatur aut odit aut fugit, sed quia consequuntur magni
                            dolores eos qui ratione voluptatem sequi nesciunt.</p>
                        <a href="#">more &rarr;</a>
                    </div>

                </li>
            </ul>




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
                    \$this.stop().animate({'width':'75px'},1000);
                    $('.heading',\$this).stop(true,true).fadeIn();
                    $('.description',\$this).stop(true,true).fadeOut(500);
                    $('.bgDescription',\$this).stop(true,true).slideUp(700);
                }
            );
        });



END_OF_API_JS;

}
?>
