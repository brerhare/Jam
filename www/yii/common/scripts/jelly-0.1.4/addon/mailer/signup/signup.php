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
				case "inputspacing":
					$val = str_replace("px", "", $val);
					$this->optionInputSpacing = $val;
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
		$content .= "<div ng-controller='signupController'>";
		$content .= "<input id='signup-name' class='signup-input' type='text' title='Name' />";
		$content .= $separator;
		$content .= "<input id='signup-email' class='signup-input' type='text' title='Email' />";
		$content .= $separator;
		$content .= "<button ng-click='addSignup()' id='signup-button' class='signup-visible' onClick='js:XaddSignup()' style='background-color:" . $this->optionButtonColor . "; color:" . $this->optionButtonTextColor . "' class='signup-button' id='save'>" . $this->optionButtonText . "</button>";
		$content .= "<span id='signup-message' class='signup-invisible'>Message Area</span>";
		$content .= "</div>";
		$content .= "</div>";

/*
		// Generate Ajax callback
		echo CHtml::ajax(array(
			'url'=>$this->createUrl('site/ajaxGetRoomPriceAvail'),
			'data'=>array(
				'name'=>'js:name',
				'email'=>'js:email',
			),
			'type'=>'POST',
			'dataType'=>'json',
			'success' => 'function(val){ajaxShowRoomPriceAvail(val);}',
		));
*/

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
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
		<style>
		.input-prompt {
			position: absolute;
			font-style: italic;
			color: #aaa;
			margin:6px;
		}
		.signup-button {
			padding: 2px 2px 2px 2px;
			cursor: pointer; cursor: hand;
			border: 0px solid #666;
			text-decoration:none;
			background: #dcdcdc url(icon.png) no-repeat scroll 5px center;
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
			color: red;
		}
		.signup-noerror {
			/*margin-left:5px;*/
			font-weight:bold;
			color: green;
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
		\$http.get('data/posts.json').
		success(function(data, status, headers, config) {
			document.getElementById('signup-button').setAttribute('class', 'signup-invisible');
			document.getElementById('signup-message').innerHTML = "Ta very much";
			document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			document.getElementById('signup-message').setAttribute('class', 'signup-noerror');
		}).
		error(function(data, status, headers, config) {
			document.getElementById('signup-message').innerHTML = "Invalid response";
			document.getElementById('signup-message').setAttribute('class', 'signup-visible');
			document.getElementById('signup-message').setAttribute('class', 'signup-error');
		});
	}
}

END_OF_API_JS;

}
?>
