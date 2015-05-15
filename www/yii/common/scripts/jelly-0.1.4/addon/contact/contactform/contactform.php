
<?php

/**
 * API for Contact Form
 *
 * Notes
 * -----

 * ============================================================
 * NBNBNB:!!! THIS USES THE MAILER PLUGIN TO SEND THE EMAIL! ||
 * This is to avoid creating a whole plugin just for emails. ||
 * ============================================================
 */

class contactform
{
	// Defaults
	private $optionEdgePadding = "10px";
	private $optionButtonColor = "grey";
	private $optionButtonTextColor = "white";
	private $optionButtonText = "Contact Us";
	private $optionInputSpacing = "0px";
	private $optionInputWidth = "100%";
	private $optionSuccessTextColor = "green";
	private $optionFailureTextColor = "red";
	private $optionTextColor = 'black';
	private $optionBackColor = '#d3d3d3';
	private $optionFontFamily = 'arial';
	private $optionFontSize = '14';

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
				case "edgepadding":
					$this->optionEdgePadding = str_replace("px", "", $val);
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
				case "inputwidth":
//					$val = str_replace("px", "", $val);
					$this->optionInputWidth = $val;
					break;
				case "inputspacing":
					$val = str_replace("px", "", $val);
					$this->optionInputSpacing = $val;
					break;
				case "font-family":
					$this->optionFontFamily = $val;
					break;
				case "font-size":
					$val = str_replace("px", "", $val);
					$this->optionFontSize = $val;
					break;
				default:
					// Not all array items are action items
					break;
			}
		}

		// Create a separator defaulting to vertical
		$separator = "<div style='height:" . $this->optionInputSpacing . "px'>&nbsp</div>";

		// Make the website sid available to js
		$SID = Yii::app()->params['sid'];
		//$content .= "<script> var SID=" . $SID . "</script>";

$content = "<style> input, textarea{  
    font-family: Arial, sans-serif;
    font-size: 100%;
    Xwidth: 26em; /* fallback for the next one, for browsers not recognizing ch */
    Xwidth: 40ch; /* sets the width to 40 times the width of the digit '0' */
width:98%
}</style>";


		// Generate the content
		$content .= "<div style='width:" . $this->optionInputWidth . ";'>";
$content .= "<center>";
		$content .= "<div style='padding:" . $this->optionEdgePadding . "px' ; background-color=" . $this->optionBackColor . "' ng-controller='contactController'>";
		$content .= "<input id='contact-name' class='contact-input' type='text' placeholder='Your Name' />";
		$content .= $separator;
		$content .= "<input id='contact-email' class='contact-input' type='text' placeholder='Your Email Address' />";
		$content .= $separator;
		$content .= "<input id='contact-subject' class='contact-input' type='text' placeholder='Subject' />";
		$content .= $separator;

		$content .= "<textarea id='contact-body' class='contact-input' rows='5 type='text' placeholder='Message''></textarea>";
		$content .= "<div id='contact-body-div' style='display:none; white-space: pre-wrap;'></div>";

		//$content .= "<input id='contact-body' class='contact-input' type='text' title='Message' />";
		$content .= $separator;
		$content .= "<button ng-click='addContact()' id='contact-send-button' class='contact-visible contact-send-button' style='padding:5px; background:" . $this->optionButtonColor . "; color:" . $this->optionButtonTextColor . "' class='contact-send-button' id='save'>" . $this->optionButtonText . "</button>";
		$content .= "<span id='contact-message' class='contact-invisible'>Message Area</span>";
		$content .= "</div>";
$content .= "</center>";
		$content .= "</div>";


        // Pick up our only record for the recipient email (from the backend)
		$settingsEmail = "no recipient email";
		$id = 1;
		$jellySetting=JellySetting::model()->findByPk($id);
        if($jellySetting!=null)
        	$settingsEmail = $jellySetting->email;
		$content .= "<script>var settingsemail = '" . $settingsEmail . "';</script>";

		// Get SID to send to plugin
		$content .= "<script> var SID='" . Yii::app()->params['sid'] . "'</script>";

		// Apply all substitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-successtextcolor>", $this->optionSuccessTextColor, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-failuretextcolor>", $this->optionFailureTextColor, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-textcolor>", $this->optionTextColor, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-backcolor>", $this->optionBackColor, $this->apiHtml);
		//$this->apiHtml = str_replace("<substitute-inputwidth>", "98%", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-font-size>", $this->optionFontSize . "px", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-font-family>", $this->optionFontFamily, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// JS

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML
		<style>
		.contact-input {
			background-color: <substitute-backcolor>;
			width: <substitute-inputwidth>;
			font-size: <substitute-font-size>;
			font-family: <substitute-font-family>;
			padding-left:3px;
			color: <substitute-textcolor>;
		}
		.input-prompt {
			position: absolute;
			font-style: italic;
			color: <substitute-textcolor>;
			margin:6px;
margin-left:30px;
		}
		.contact-send-button {
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
		.contact-send-button:hover {
			cursor: pointer; cursor: hand;
			border: 0px solid #666;
			text-decoration:none;
			-webkit-box-shadow:none;
			-moz-box-shadow:none;
			box-shadow:none;
			text-shadow:none;
		}

		.contact-visible {
			display:inline;
		}
		.contact-invisible {
			display:none;
		}
		.contact-error {
			margin-left:5px;
			font-weight:bold;
			color: <substitute-failuretextcolor>;
		}
		.contact-noerror {
			/*margin-left:5px;*/
			font-weight:bold;
			color: <substitute-successtextcolor>;
		}
		</style>
		<substitute-data>
END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
$(document).ready(function(){
});

// Handle newlines between textarea getting lost
$( "#contact-body" ).keyup( function() {
	$( "#contact-body-div" ).html( $( this ).val() );
}); 

function contactController(\$scope, \$http)
{
	\$scope.addContact = function() {
		var name = document.getElementById('contact-name').value;
		var email = document.getElementById('contact-email').value;
		var subject = document.getElementById('contact-subject').value;
		var body = document.getElementById('contact-body').value;

		name = name.trim();
		if (name.length == 0)
		{
			document.getElementById('contact-message').innerHTML = "Name is required";
			document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			document.getElementById('contact-message').setAttribute('class', 'contact-error');
			return;
		}
		if ((email.indexOf("@") == -1) || (email.indexOf(".") == -1))
		{
			document.getElementById('contact-message').innerHTML = "Invalid email";
			document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			document.getElementById('contact-message').setAttribute('class', 'contact-error');
			return;
		}
		if (subject.length == 0)
		{
			document.getElementById('contact-message').innerHTML = "Subject is required";
			document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			document.getElementById('contact-message').setAttribute('class', 'contact-error');
			return;
		}
		if (body.length == 0)
		{
			document.getElementById('contact-message').innerHTML = "Message is required";
			document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			document.getElementById('contact-message').setAttribute('class', 'contact-error');
			return;
		}

		// All ok, send the message for emailing
		document.getElementById('contact-name').value = "";
		document.getElementById('contact-email').value = "";
		document.getElementById('contact-subject').value = "";
		document.getElementById('contact-body').value = "";

		var url = 'http://plugin.wireflydesign.com/mailer/index.php/site/ajaxContactUs/?sid=' + SID + '&name=' + name + '&email=' + email + '&subject=' + subject + '&body=' + body + '&settingsemail=' + settingsemail;
//alert(url);


		\$http.get(url).
		success(function(data, status, headers, config) {
			document.getElementById('contact-send-button').setAttribute('class', 'contact-invisible');
			document.getElementById('contact-message').innerHTML = "Thank you";
			document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			document.getElementById('contact-message').setAttribute('class', 'contact-noerror');
		}).
		error(function(data, status, headers, config) {
			//document.getElementById('contact-message').innerHTML = "Invalid response";
			//document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			//document.getElementById('contact-message').setAttribute('class', 'contact-error');
			// We thank regardless...
			document.getElementById('contact-send-button').setAttribute('class', 'contact-invisible');
			document.getElementById('contact-message').innerHTML = "Thank you";
			document.getElementById('contact-message').setAttribute('class', 'contact-visible');
			document.getElementById('contact-message').setAttribute('class', 'contact-noerror');
		});
	}
}

END_OF_API_JS;

}
?>
