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

		<!-- JQuery UI for dialog -->
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js" ></script>
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/sunny/jquery-ui.css" type="text/css" rel="stylesheet" />

		<div class="login-container">
			<link rel="stylesheet" type="text/css" href="<substitute-path>/fadguidecode.css" />

			<style> /* overrides */
				/* JQuery dialog needs to be higher (than the menu at least) */
				.ui-dialog { z-index: 12000 !important ;}

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

		<!-- Edit dialog container -->
		<div id="editDialog" style="display:none;/*border:1px solid #e2f0f8*/" title="Event">
		<form enctype="multipart/form-data">
			<input type="hidden" name="mode" id="editMode"> <!-- 'signup' or 'login' -->
			<table>




<tr>
<td> <label for="logo">Filename</label> </td>
<td>
	<input size="60" maxlength="255" name="logo" id="logo" type="file" />
</td>
</tr>


				<tr>
					<td> <label for="editBusinessName">Business name</label> </td>
					<td> <input type="text" style="width:250px" name="editBusinessName" id="editBusinessName" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editAddress1">Address 1</label> </td>
					<td> <input type="text" style="width:300px" name="editAddress1" id="editAddress1" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editAddress2">Address 2</label> </td>
					<td> <input type="text" style="width:300px" name="editAddress2" id="editAddress2" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editAddress3">Address 3</label> </td>
					<td> <input type="text" style="width:300px" name="editAddress3" id="editAddress3" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editAddress4">Address 4</label> </td>
					<td> <input type="text" style="width:300px" name="editAddress4" id="editAddress4" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editPostCode">Post code</label> </td>
					<td> <input type="text" style="width:100px" name="editPostCode" id="editPostCode" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editContact">Contact</label> </td>
					<td> <input type="text" style="width:250px" name="editContact" id="editContact" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editWeb">Web</label> </td>
					<td> <input type="text" style="width:100px" name="editWeb" id="editWeb" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editEmail">Email Address</label> </td>
					<td> <input type="text" style="width:100px" name="editEmail" id="editEmail" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editPhone">Telephone</label> </td>
					<td> <input type="text" style="width:150px" name="editPhone" id="editPhone" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editOpeningHours">Opening Hours</label> </td>
					<td> <input type="text" style="width:300px" name="editOpeningHours" id="editOpeningHours" class="text ui-widget-content"> </td>
				</tr>

				<tr>
					<td> <label for="editHtmlContent">Freeform content</label> </td>
					<td> <textarea cols="40" rows="5" name="editHtmlContent" id="editHtmlContent" class="text ui-widget-content"></textarea> </td>
				</tr>

				<tr>
					<td> <label for="editLogoPath">Upload logo file (150x150)</label> </td>
					<td> <input type="text" style="width:100px" name="editLogoPath" id="editLogoPath" class="text ui-widget-content"> </td>
				</tr>

				<tr>
					<td> <label for="editSliderImagePath">Upload slider image file (700x200)</label> </td>
					<td> <input type="text" style="width:100px" name="editSliderImagePath" id="editSliderImagePath" class="text ui-widget-content"> </td>
				</tr>

				<tr>
					<td> <label for="editPublic">Open to the public?</label> </td>
					<td>
						<select id="editPublic" name="editPublic">
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</td>
				</tr>

			</table>
			<center>
			<table>
				<tr>
					<td width="25%">
					<td width="25%"> <input type='button' id='editSave' style='float:left;padding:3px; width:60px' onClick='saveEditDialog("save")' value='Save'> </td>
					<td width="25%"> <input type='button' id='editCancel' style='float:left;padding:3px; width:60px' onClick='cancelEditDialog()' value='Cancel'> </td>
					<td width="25%">
				</tr>
			</table>
			</center>
		</form>
		</div>	<!-- Edit dialog container -->


END_OF_API_HTML;

		$apiJs = <<<END_OF_API_JS

		/* Signup / login */
		/* ---------------*/
		function signupClick()	/* Ajax send */
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
		function ajaxSignupForm(val)	/* Ajax return */
		{
			if (val.error != "")
				alert(val.error);
			else
				showEditDialog(val);
		} 

		function loginClick()	/* Ajax send */
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
		function ajaxLoginForm(val)	/* Ajax return */
		{
			if (val.error != "")
				alert(val.error);
			else
				showEditDialog(val);
		} 

		/* Edit dialog form */
		/* ---------------- */
		function showEditDialog(val)	/* Start editing */
		{
			$("#editMode").val(val.mode);

			$("#editBusinessName").val(val.businessName);
			$("#editAddress1").val(val.address1);
			$("#editAddress2").val(val.address2);
			$("#editAddress3").val(val.address3);
			$("#editAddress4").val(val.address4);
			$("#editPostCode").val(val.postCode);
			$("#editContact").val(val.contact);
			$("#editWeb").val(val.web);
			$("#editEmail").val(val.email);
			$("#editPhone").val(val.phone);
			$("#editOpeningHours").val(val.openingHours);
			$("#editHtmlContent").val(val.htmlContent);
			$("#editLogoPath").val(val.logoPath);
			$("#editSliderImagePath").val(val.sliderImagePath);
			$("#editPublic").val(val.public);

			$("#editDialog").dialog({width:'auto'});
			$("#editDialog").dialog('option', 'title', 'Input Your Business Details');
		}
$('#logo').change(function(){
    var file = this.files[0];
    var name = file.name;
    var size = file.size;
    var type = file.type;
    //Your validation
alert('xx');
});
		function saveEditDialog()		/* Save */
		{
			//alert('preparing fields to send to server');
            var editMode = document.getElementById('editMode').value;
            var username = document.getElementById('username').value;	// Global
            var password = document.getElementById('password').value;	// Global
            var businessName = document.getElementById('editBusinessName').value;
            var address1 = document.getElementById('editAddress1').value;
            var address2 = document.getElementById('editAddress2').value;
            var address3 = document.getElementById('editAddress3').value;
            var address4 = document.getElementById('editAddress4').value;
            var postCode = document.getElementById('editPostCode').value;
            var contact = document.getElementById('editContact').value;
            var web = document.getElementById('editWeb').value;
            var email = document.getElementById('editEmail').value;
            var phone = document.getElementById('editPhone').value;
            var openingHours = document.getElementById('editOpeningHours').value;
            var htmlContent = document.getElementById('editHtmlContent').value;
            var logoPath = document.getElementById('editLogoPath').value;
            var sliderImagePath = document.getElementById('editSliderImagePath').value;
            var public = document.getElementById('editPublic').value;
			//alert('sending to server');
            $( "#editDialog" ).dialog('close');
            <substitute-ajax-edit-code>
		}
		function cancelEditDialog()		/* Cancel */
		{
			$( "#editDialog" ).dialog('close');
		}
		function ajaxShowEditResult(val)
		{
			if (val.error != "")
				alert(val.error);
			else
				alert('Saved');
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

        // Substitutes for ajax edit code
        $ajaxEditString = CHtml::ajax(array(
            'url'=>Yii::app()->createUrl('site/ajaxEdit'),
            'data'=>array(
                'editMode'=>'js:editMode',
                'username'=>'js:username',
                'password'=>'js:password',
                'businessName'=>'js:businessName',
                'address1'=>'js:address1',
                'address2'=>'js:address2',
                'address3'=>'js:address3',
                'address4'=>'js:address4',
                'postCode'=>'js:postCode',
                'contact'=>'js:contact',
                'web'=>'js:web',
                'email'=>'js:email',
                'phone'=>'js:phone',
                'openingHours'=>'js:openingHours',
                'htmlContent'=>'js:htmlContent',
                'logoPath'=>'js:logoPath',
                'sliderImagePath'=>'js:sliderImagePath',
                'public'=>'js:public',
                ),
            'type'=>'POST',
            'dataType'=>'json',
            'success' => 'function(val){ajaxShowEditResult(val);}',
        ));
        $apiJs = str_replace("<substitute-ajax-edit-code>", $ajaxEditString, $apiJs);

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
