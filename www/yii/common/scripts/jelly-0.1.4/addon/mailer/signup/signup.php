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
	private $optionInputSpacing = "5";
	private $optionInputWidth = "145";
	private $optionSuccessTextColor = "green";
	private $optionFailureTextColor = "red";
	private $optionTextColor = 'black';
	private $optionBackColor = '#d3d3d3';

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
				case "buttoncolour":
					$this->optionButtonColor = $val;
					break;
				case "buttontextcolor":
				case "buttontextcolour":
					$this->optionButtonTextColor = $val;
					break;
				case "buttontext":
					$this->optionButtonText = $val;
					break;
				case "successtextcolor":
				case "successtextcolour":
					$this->optionSuccessTextColor = $val;
					break;
				case "failuretextcolor":
				case "failuretextcolour":
					$this->optionFailureTextColor = $val;
					break;
				case "textcolor":
				case "textcolour":
					$this->optionTextColor = $val;
					break;
				case "backcolor":
				case "backcolour":
					$this->optionBackColor = $val;
					break;
				case "inputspacing":
					$val = str_replace("px", "", $val);
					$this->optionInputSpacing = $val;
					break;
				case "inputwidth":
					$val = str_replace("px", "", $val);
					$this->optionInputWidth = $val;
					break;
				default:
					// Not all array items are action items
					break;
			}
		}

		// Create a separator defaulting to vertical
		$separator = "<div style='height:" . $this->optionInputSpacing . "px'>&nbsp</div>";
		if ($this->optionOrientation == "horizontal")
			$separator = "<span style='margin-left:" . $this->optionInputSpacing . "px'>&nbsp</span>";

		// Generate the content
		$content = "<div ng-app>";




		//@@TODO: This is temporarily to disable the background color until we can set it properly in {{name=value}}
		$content .= "<div style='XXXbackground-color=" . $this->optionBackColor . "' ng-controller='signupController'>";




		$content .= "<input id='signup-name' class='signup-input' type='text' style='width:" . $this->optionInputWidth . "px' title='Name' />";
		$content .= $separator;
		$content .= "<input id='signup-email' class='signup-input' type='text' style='width:" . $this->optionInputWidth . "px' title='Email' />";
		$content .= $separator;
		$content .= "<button ng-click='addSignup()' id='signup-send-button' class='signup-visible signup-send-button' style='background:" . $this->optionButtonColor . "; color:" . $this->optionButtonTextColor . "' class='signup-send-button' id='save'>" . $this->optionButtonText . "</button>";
		$content .= "<span id='signup-message' class='signup-invisible'>Message Area</span>";
		$content .= "</div>";
		$content .= "</div>";

		// Get SID to send to plugin
		$content .= "<script> var SID='" . Yii::app()->params['sid'] . "'</script>";

		// Apply all substitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-successtextcolor>", $this->optionSuccessTextColor, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-failuretextcolor>", $this->optionFailureTextColor, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-textcolor>", $this->optionTextColor, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-backcolor>", $this->optionBackColor, $this->apiHtml);
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
			margin:6px;
		}
		.signup-send-button {
			padding: 2px 2px 2px 2px;
			cursor: pointer; cursor: hand;
			border: 0px solid #666;
			text-decoration:none;
			-webkit-box-shadow:none;
			-moz-box-shadow:none;
			box-shadow:none;
			text-shadow:none;
			//background: #dcdcdc url(icon.png) no-repeat scroll 5px center;
		}
		.signup-send-button:hover {
			cursor: pointer; cursor: hand;
			border: 0px solid #666;
			text-decoration:none;
			-webkit-box-shadow:none;
			-moz-box-shadow:none;
			box-shadow:none;
			text-shadow:none;
		}

		.signup-visible {
			display:inline;
		}
		.signup-invisible {
			display:none;
		}
		.signup-error {
			margin-left:5px;
			font-weight:bold;
			color: <substitute-failuretextcolor>;
		}
		.signup-noerror {
			/*margin-left:5px;*/
			font-weight:bold;
			color: <substitute-successtextcolor>;
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

function signupController(\$scope, \$http)
{
	\$scope.addSignup = function() {
		var name = document.getElementById('signup-name').value;
		var email = document.getElementById('signup-email').value;
		name = name.trim();
		if (name.length == 0)
		{
			document.getElementById('signup-message').innerHTML = "Name is required";
			document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			document.getElementById('signup-message').setAttribute('class', 'signup-error');
			return;
		}
		if ((email.indexOf("@") == -1) || (email.indexOf(".") == -1))
		{
			document.getElementById('signup-message').innerHTML = "Invalid email";
			document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			document.getElementById('signup-message').setAttribute('class', 'signup-error');
			return;
		}
		// All ok - register the user


		var url = 'http://plugin.wireflydesign.com/mailer/index.php/site/ajaxAddSignupMember/?sid=' + SID + '&name=' + name + '&email=' + email;
//alert(url);

		\$http.get(url).
		success(function(data, status, headers, config) {
			document.getElementById('signup-send-button').setAttribute('class', 'signup-invisible');
			document.getElementById('signup-message').innerHTML = "Thank you";
			document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			document.getElementById('signup-message').setAttribute('class', 'signup-noerror');
		}).
		error(function(data, status, headers, config) {
			//document.getElementById('signup-message').innerHTML = "Invalid response";
			//document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			//document.getElementById('signup-message').setAttribute('class', 'signup-error');
			// We thank regardless...
			document.getElementById('signup-send-button').setAttribute('class', 'signup-invisible');
			document.getElementById('signup-message').innerHTML = "Thank you";
			document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			document.getElementById('signup-message').setAttribute('class', 'signup-noerror');
		});
	}
}

END_OF_API_JS;

}
?>
