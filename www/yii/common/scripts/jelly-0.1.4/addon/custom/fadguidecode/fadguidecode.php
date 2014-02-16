<?php

/**
 * API for Fadguide code
 *
 * Notes
 * -----
 * None
 */

class fadguidecode
{
    // Globals
    private $jellyRootUrl = "";

	//Defaults
	// None

	// Globals
	private $clipBoard = "";

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

		$this->jellyRootUrl = $jellyRootUrl;

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "run":
					if ($val == 'login')
						return $this->login();
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/***************************************************************************************************/

    private function login()
    {
		$content = "";

		// Apply all defaults that werent overridden, if any

		$apiHtml = <<<END_OF_API_HTML
		<div class="login-container">
			<link rel="stylesheet" type="text/css" href="<substitute-path>/fadguidecode.css" />
			<style> /* overrides */
				.login-holder { background: #A70055; }
			</style>
			<div class="login-holder">
    			<div id="login-signup">
        			<ul>
						<div id="login-controls-container">
							<li class="input">
                				<input type="text" id="username" name="username" placeholder="Username" required />
							</li>
            				<li class="input">
                				<input type="password" id="password" name="password" placeholder="Password" required />
            				</li>
							<li>
                				<button style="color:#5C5C5E; background:#CCCCCC; background: -moz-linear-gradient(0% 100% 90deg, #CCCCCC, #999988); background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#CCCCCC), to(#999988));" onClick='loginClick()'>Log in</button>
            				</li>
							<li>
                				<button onClick='signupClick()'>Sign up</button>
                				<!-- <button disabled="disabled">Download iTunes</button> -->
            				</li>
        				</ul>
					</div>
    			</div>
			</div>
		</div>
END_OF_API_HTML;

		$apiJs = <<<END_OF_API_JS

		function signupClick()
		{
			var username = document.getElementById('username').value;
			var password = document.getElementById('password').value;
			if ((username.trim() == "") || (password.trim() == ""))
			{
				alert('Please enter both username and password');
				return;
			}
			//alert('sending signup to server: username='+username+' password='+password);
			<substitute-ajax-signup-code>
		}

		function ajaxSignupForm(val)
		{
			if (val.error != "")
				alert(val.error);
			else
				inputForm(val);
		} 

		function loginClick()
		{
			var username = document.getElementById('username').value;
			var password = document.getElementById('password').value;
			if ((username.trim() == "") || (password.trim() == ""))
			{
				alert('Please enter both username and password');
				return;
			}
			//alert('sending login to server: username='+username+' password='+password);
			<substitute-ajax-login-code>
		}

		function ajaxLoginForm(val)
		{
			if (val.error != "")
				alert(val.error);
			else
				inputForm(val);
		} 

		function inputForm(val)
		{
			alert('form');
		}

END_OF_API_JS;

		// Substitute paths for includes
		$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
		// This addon has no defaults that can be overridden

        // Substitutes for ajax signup code
        $ajaxSignupString = CHtml::ajax(array(
            'url'=>Yii::app()->createUrl('site/ajaxSignup'),
            'data'=>array(
                'username'=>'js:username',
                'password'=>'js:password',
                ),
            'type'=>'POST',
            'dataType'=>'json',
            'success' => 'function(val){ajaxSignupForm(val);}',
        ));
        $apiJs = str_replace("<substitute-ajax-signup-code>", $ajaxSignupString, $apiJs);

        // Substitutes for ajax login code
        $ajaxLoginString = CHtml::ajax(array(
            'url'=>Yii::app()->createUrl('site/ajaxLogin'),
            'data'=>array(
                'username'=>'js:username',
                'password'=>'js:password',
                ),
            'type'=>'POST',
            'dataType'=>'json',
            'success' => 'function(val){ajaxLoginForm(val);}',
        ));
        $apiJs = str_replace("<substitute-ajax-login-code>", $ajaxLoginString, $apiJs);

		// Wrapup
		$clipBoard = "";
		$apiHeader = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		$retArr[3] = $apiHeader;
		return $retArr;
	}

}
?>
