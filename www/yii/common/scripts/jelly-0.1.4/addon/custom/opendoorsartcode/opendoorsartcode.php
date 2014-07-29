<?php

/**
 * API for Opendoorsart code
 *
 * Notes
 * -----
 * None
 */

class opendoorsartcode
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
		$listCategory = "";

		$this->jellyRootUrl = $jellyRootUrl;

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "category":
					$listCategory = $val;
					break;
				case "run":
					if ($val == 'login')
						return $this->login();
					if ($val == 'listMembers')
						return $this->listMembers($listCategory);
					if ($val == 'showMember')
						return $this->showMember($_GET['member']);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/***************************************************************************************************/

    private function listMembers($listCategory)
    {
		$content = "";

		$apiHtml = <<<END_OF_API_HTML

		<div class="listCategory-container">

			<style> /* overrides */
			</style>

			<table style="width:100%">
				<tr>
					<td width="30%">&nbsp</td>	<!-- filter -->
					<td width="70%">				<!-- member header lines -->
						<table>
							<substitute-member-header>
						</table>
					</td>
				</tr>
			</table>
		</div>

END_OF_API_HTML;

		$apiJs = <<<END_OF_API_JS
END_OF_API_JS;

        // Substitute paths for includes
        $apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
        // This addon has no defaults that can be overridden

		// Member headers displayed as a list
		$endLinkTag = "</a>";
		$content = "";
		$criteria = new CDbCriteria;
		//$criteria->addCondition("id != " . $model->id);
		$members = Member::model()->findAll($criteria);
		foreach ($members as $member):
			// Is this member in the category being displayed?
			$criteria = new CDbCriteria;
			$criteria->addCondition("member_id = " . $member->id);
			$criteria->addCondition("category_id = " . $listCategory);
			$memberHasCategory = MemberHasCategory::model()->find($criteria);
			if (!($memberHasCategory))
				continue;
			$address = $member->address1 . ", " . $member->address2 . ", " . $member->address3 . ", " . $member->address4 . ", " . $member->postcode;
			$address = rtrim($address, ", ");
			for ($i = 0; $i < 4; $i++)
				$address = str_replace(", , ", ", ", $address);
			$content .= "<tr style='background-color:#FFECF8;' onClick='javascript:alert(" . "'x')" . ">";
			$content .= "<td width='75%' style='padding:5px;'>";
			$startLinkTag = "<a href='http://www.opendoorsart.com/?layout=index&page=category-member&member=$member->id'>";
			$content .= $startLinkTag;
			$content .= "<i><p style='color:#A70055; font-weight:bold'>" . $member->business_name . "</p></i>";
			$content .= "<i><p style=''>" . $address . "</p></i>";
$content .= $endLinkTag;
			$content .= "<a style='color:#A70055; text-decoration:underline' href='http://" . $member->web . " 'target='_blank''>Web site</a>";
$content .= $startLinkTag;
			$content .= "<p style='font-size:small'>" . $member->email . " / " . $member->phone . "</p>";
			$content .= $endLinkTag;
			$content .= "</td>";
			$content .= "<td width='20%'>";
			$content .= $startLinkTag;
			$content .= "<img src='userdata/image/logo/" . $member->logo_path . "' width='150px' height='150px'>";
			$content .= $endLinkTag;
			$content .= "</td>";
			$content .= "</tr>";
			$content .= "<tr height='10px'>";
			$content .= "<td colspan='2'>";
			$content .= "<hr style='height:2px; background-color:#A70055'/>";
			$content .= "</td>";
			$content .= "</tr>";
		endforeach;
		$apiHtml = str_replace("<substitute-member-header>", $content, $apiHtml);

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

	/***************************************************************************************************/

    private function showMember($id)
    {
		$content = "";

		$apiHtml = <<<END_OF_API_HTML

		<div class="showCategoryMember-container">

			<style> /* overrides */
			</style>

			<table style="width:100%">
				<tr>
					<!-- <td width="30%">&nbsp</td>	--> <!-- filter -->
					<td width="70%">				<!-- member header lines -->
						<table>
							<substitute-member-header>
						</table>
					</td>
				</tr>
			</table>
		</div>

END_OF_API_HTML;

		$apiJs = <<<END_OF_API_JS
END_OF_API_JS;

        // Substitute paths for includes
        $apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
        // This addon has no defaults that can be overridden

		// Member headers displayed as a list
		$content = "";
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $id);
		$member = Member::model()->find($criteria);
		if ($member)
		{
			$address = $member->address1 . ", " . $member->address2 . ", " . $member->address3 . ", " . $member->address4 . ", " . $member->postcode;
			$address = rtrim($address, ", ");
			for ($i = 0; $i < 4; $i++)
				$address = str_replace(", , ", ", ", $address);
			$content .= "<tr style='background-color:#FFECF8;' onClick='javascript:alert(" . "'x')" . ">";
			$content .= "<td width='20%'>";
			$content .= "<img src='userdata/image/logo/" . $member->logo_path . "' width='150px' height='150px'>";
			$content .= "</td>";
			$content .= "<td width='75%' style='padding:5px;'>";
			$content .= "<i><p style='color:#A70055; font-weight:bold'>" . $member->business_name . "</p></i>";
			$content .= "<i><p style=''>" . $address . "</p></i>";
			$content .= "<a style='color:#A70055; text-decoration:underline' href='http://" . $member->web . " 'target='_blank''>Web site</a>";
			$content .= "<p style='font-size:small'>" . $member->email . " / " . $member->phone . "</p>";
			$content .= "</td>";
			$content .= "</tr>";
			$content .= "<tr height='10px'>";
			$content .= "<td colspan='2'>";
			$content .= "<hr style='height:2px; background-color:#A70055'/>";
			$content .= "</td>";
			$content .= "</tr>";
$content .= "<tr><td colspan=2 style='color:#A70055'>";
$content .= nl2br($member->html_content);
$content .= "</td></tr>";
		}
		$apiHtml = str_replace("<substitute-member-header>", $content, $apiHtml);

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
			<link rel="stylesheet" type="text/css" href="<substitute-path>/opendoorsartcode.css" />

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
			<form enctype="multipart/form-data" class="form-vertical" id="image-form" action="/index.php/site/submit" method="POST">
			<input type="hidden" name="editMode" id="editMode"> <!-- 'signup' or 'login' -->

			<table style="display:inline-block">	<!-- Name/address TOP LHS -->
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
					<td> <label for="editPublic">Open to the public?</label> </td>
					<td>
						<select id="editPublic" name="editPublic">
							<option value="1">Yes</option>
							<option value="0">No</option>
						</select>
					</td>
				</tr>
			</table>

			<table style="display:inline-block; padding-left:20px">		<!-- Misc details TOP RHS -->
				<tr>
					<td> <label for="editContact">Contact person</label> </td>
					<td> <input type="text" style="width:250px" name="editContact" id="editContact" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editWeb">Web address</label> </td>
					<td> <input type="text" style="width:250px" name="editWeb" id="editWeb" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editEmail">Email address</label> </td>
					<td> <input type="text" style="width:250px" name="editEmail" id="editEmail" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editPhone">Telephone</label> </td>
					<td> <input type="text" style="width:150px" name="editPhone" id="editPhone" class="text ui-widget-content"> </td>
				</tr>
				<tr>
					<td> <label for="editOpeningHours">Opening Hours</label> </td>
					<td> <input type="text" style="width:250px" name="editOpeningHours" id="editOpeningHours" class="text ui-widget-content"> </td>
				</tr>
                <tr>
                    <td> <label for="editLogoPath">Upload logo (150x150)</label> </td>
                    <td> <input type="file" name="editLogoPath" id="editLogo"/> </td>
                </tr>
                <tr>
                    <td> <label for="editSliderImagePath">Upload Slider (700x200)</label> </td>
                    <td> <input type="file" name="editSliderImagePath" id="editSlider"/> </td>
                </tr>
			</table>

			<hr/>
			<!-- Checkboxes for Category -->
			<table>
				<tr>
					<td><b>Categories&nbsp&nbsp&nbsp&nbsp</b></td>
					<td width="300px">
						<div id='editCategories'></div>		<!-- Checkboxes for Category -->
					</td>
					<td><b>Types&nbsp&nbsp&nbsp&nbsp</b></td>
					<td width="300px">
						<div id='editFoodtypes'></div>		<!-- Checkboxes for Food Type -->
					</td>
				</tr>
			</table>
			<hr/>

			<center>
			<table>
				<tr>
					<td> <label for="editHtmlContent">Freeform content</label> </td>
					<td> <textarea cols="70" rows="10" name="editHtmlContent" id="editHtmlContent" class="text ui-widget-content"></textarea> </td>
				</tr>
			</table>
			</center>

			<center>
			<table>
				<tr>
					<td width="25%">
					<td width="25%"> <input type='submit' id='editSave' style='float:left;padding:3px; width:60px' onClick='saveEditDialog("save")' value='Save'> </td>
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

			// Categories
			var container = document.getElementById("editCategories");
			while (container.firstChild)
				container.removeChild(container.firstChild);
			for (var i = 0; i < val.categoryCount; i++)
			{
				var vId = eval("val.category_" + i + ".id");
				var vName = eval("val.category_" + i + ".name");
				var vChecked = eval("val.category_" + i + ".checked");
//alert(vId+':'+vName+':'+vChecked);
				var checkbox = document.createElement('input');
				checkbox.type = "checkbox";
				checkbox.name = "editCategory[]";
				checkbox.value = vId;
				checkbox.checked = vChecked;
				//checkbox.id = "id";

				var label = document.createElement('label')
				label.htmlFor = "id";
				label.appendChild(document.createTextNode(vName));

				container = document.getElementById('editCategories');
				container.appendChild(checkbox);
				container.appendChild(label);
				container.appendChild(document.createElement('br'));
			}

			// Types
			var container = document.getElementById("editFoodtypes");
			while (container.firstChild)
				container.removeChild(container.firstChild);
			for (var i = 0; i < val.foodtypeCount; i++)
			{
				var vId = eval("val.foodtype_" + i + ".id");
				var vName = eval("val.foodtype_" + i + ".name");
				var vChecked = eval("val.foodtype_" + i + ".checked");
				var checkbox = document.createElement('input');
				checkbox.type = "checkbox";
				checkbox.name = "editFoodtype[]";
				checkbox.value = vId;
				checkbox.checked = vChecked;
				//checkbox.id = "id";

				var label = document.createElement('label')
				label.htmlFor = "id";
				label.appendChild(document.createTextNode(vName));

				container = document.getElementById('editFoodtypes');
				container.appendChild(checkbox);
				container.appendChild(label);
				container.appendChild(document.createElement('br'));
			}

			$("#editDialog").dialog({width:'auto'});
			$("#editDialog").dialog('option', 'title', 'Input Your Business Details');
		}
		function saveEditDialog()		/* Save */
		{
return;
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

			// Category checkboxes
			var cats = "";
			var collection = document.getElementById('editCategories').getElementsByTagName('INPUT');
			for (var x=0; x<collection.length; x++)
			{
				if (collection[x].type.toUpperCase()=='CHECKBOX')
				{
					if (cats != "") cats += "|";
					cats += collection[x].value + "_" + collection[x].checked;
				}
			}

			// Foodtype checkboxes
			var fts = "";
			var collection = document.getElementById('editFoodtypes').getElementsByTagName('INPUT');
			for (var x=0; x<collection.length; x++)
			{
				if (collection[x].type.toUpperCase()=='CHECKBOX')
				{
					if (fts != "") fts += "|";
					fts += collection[x].value + "_" + collection[x].checked;;
				}
			}

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
                'cats'=>'js:cats',
                'fts'=>'js:fts',
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
