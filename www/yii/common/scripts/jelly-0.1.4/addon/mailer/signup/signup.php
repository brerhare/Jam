<?php

/**
 * API for Download
 *
 * Notes
 * -----
 * No external code
 */

class signup
{
	//Defaults
	private $optionOrientation = "vertical";
	private $optionButtonColor = "grey";
	private $optionButtonTextColor = "white";
	private $optionButtonText = "Signup";
	private $optionInputSpacing = "0px";

	public $apiOption = array(
	);

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]HTML [1]JS
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
				case "orientation":
					$this->optionOrientation = $val;
					break;
				case "buttoncolor":
					$this->optionButtonColor = $val;
					break;
				case "buttoncolour":
					$this->optionButtonColor = $val;
					break;
				case "buttontextcolor":
					$this->optionButtonTextColor = $val;
					break;
				case "buttontextcolour":
					$this->optionButtonTextColor = $val;
					break;
				case "buttontext":
					$this->optionButtonText = $val;
					break;
				case "inputspacing":
					$val = str_replace("px", "", $val);
					$this->optionInputSpacing = $val;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Create a separator defaulting to vertical
		$separator = "<div style='height:" . $this->optionInputSpacing . "px'>&nbsp</div>";
		if ($this->optionOrientation == "horizontal")
			$separator = "<span style='margin-left:" . $this->optionInputSpacing . "px'>&nbsp</span>";

		// Generate the content
		$content = "<div>";
		$content .= "<input class='signup-input' type='text' title='Name' />";
		$content .= $separator;
		$content .= "<input class='signup-input' type='text' title='Email' />";
		$content .= $separator;
		$content .= "<button style='background-color:" . $this->optionButtonColor . "; color:" . $this->optionButtonTextColor . "' class='signup-button' id='save'>" . $this->optionButtonText . "</button>";
		$content .= "</div>";

		// Apply all substitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// JS

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML
		<style>
		.input-prompt {
			position: absolute;
			font-style: italic;
			color: #aaa;
			/*margin: 0.2em 0 0 0.5em;*/
			margin:5px;
		}
		.signup-button {
			padding: 2px 2px 2px 2px;
			border: 0px solid #666;
			text-decoration:none;
			background: #dcdcdc url(icon.png) no-repeat scroll 5px center;
		}
		</style>
		<substitute-data>
END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
$(document).ready(function(){
$('.signup-input[type=text][title],.signup-input[type=password][title],textarea[title]').each(function(i){
    $(this).addClass('input-prompt-' + i);
    var promptSpan = $('<span class="input-prompt"/>');
    $(promptSpan).attr('id', 'input-prompt-' + i);
    $(promptSpan).append($(this).attr('title'));
    $(promptSpan).click(function(){
      $(this).hide();
      $('.' + $(this).attr('id')).focus();
    });
    if($(this).val() != ''){
      $(promptSpan).hide();
    }
    $(this).before(promptSpan);
    $(this).focus(function(){
      $('#input-prompt-' + i).hide();
    });
    $(this).blur(function(){
      if($(this).val() == ''){
        $('#input-prompt-' + i).show();
      }
    });
  });
});
END_OF_API_JS;

}
?>
