<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Products custom code
 *
 * Notes
 * -----
 * None
 */

class products
{
	// Globals
	private $uid = "";
	private $sid = "";

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
	  //var_dump( $options );

		$this->uid = Yii::app()->session['uid'];
		$this->sid = Yii::app()->session['sid'];

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "product_page_options_dropdown":
					return $this->product_page_options_dropdown($val);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	// Invoked by product.jel to show the product options for 
	private function product_page_options_dropdown($val)
	{
		$productId = $val;
		$content = "";

		// each product option
		$criteria = new CDbCriteria;
		$criteria->addCondition("product_product_id = " . $productId);
		$productHasOptions = ProductHasOption::model()->findAll($criteria);

		$content .= "<select id='choose_product_option'>";
		foreach ($productHasOptions as $productHasOption)
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $productHasOption->product_option_id);
			$option = Option::model()->find($criteria);
			if ($option)
			{
				$content .= "<option value='" . $option->id . "'>£" . $productHasOption->price . "&nbsp&nbsp&nbsp&nbsp&nbsp" . $option->name . "</option>";
			}
		}
		$content .= "</select>";

		$apiHtml = $content;
		$apiJs = "";
		$clipBoard = "";

/*
<select name="mydropdown">
<option value="Milk">Fresh Milk</option>
<option value="Cheese">Old Cheese</option>
<option value="Bread">Hot Bread</option>
</select>
*/

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	private function selectMatchingProducts()
	{
		// Apply all defaults that werent overridden

		// HTML

		// JS
		if (strstr($this->apiJs, "<substitute-department>"))
		{
			$tmp = str_replace("<substitute-department>", $this->defaultDepartment, $this->apiJs);
			$this->apiJs = $tmp;
		}

		// Insert the data
		$data = '';
		$data .= "<script> var SID = '" . $_GET['sid'] . "'; </script>";
		$data .= "<div style='color:#575757;'>";      // Your basic solemn grey font color

		// The 'show filter string' button and div
		if (isset($_GET['showurl']))
		{
			$data .= "<button type='button' onClick='showUrl()' style='color:#ffffff; background-color:#0064cc;'>Show filter string</button><br/>";
			$data .= "<center><div id='showFilterString' style='display:none; border:1px solid black; width:160px; padding:5px; white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word; '></div></center>";
		}

		// Generate the preset questions if in 'preset' mode
		if ($this->mode == 'preset')
			$data .= $this->buildPrefixInputs();

		// Generate twistys and their checkboxes for user input. Default to current $_GET
		$data .= $this->buildUserInputs();
		$this->apiHtml = str_replace("<substitute-data>", $data, $this->apiHtml);
		// This is kind of a standard replace
		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

		$this->clipBoard = $this->selectMatchingProducts();     // Eg '2|22|222|4|5|6'

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		$retArr[2] = $this->clipBoard;
		return $retArr;
	}

	private function buildPrefixInputs()
	{
		$filterSel = array();
		$content = "";
		$content .= "<script>presetArr = [];</script>";
		$content .= "<br/><center><table>";
		$filters = Filter::model()->findAll(array('order'=>'id', 'condition'=>'uid=' . $this->uid));
		if ($filters)
		{
			if (isset($_GET['filter']))
				$filterSel = explode('|', $_GET['filter']);
			foreach ($filters as $filter):
				// Store the preset values
				$content .= '<script>presetArr.push("' . $filter->filter_string . '");</script>';

				$content .= "<tr>";
				$content .=   "<td width=5%></td>";
				$content .=   "<td width=80%>" . $filter->text . "</td>";
				$content .=   "<td width=10%>";
				$content .=     "<input name='preset[]' "; 
				$match = false;
				if (in_array($filter->id, $filterSel))
					$match = true;
				if ($match) $content .= " checked='checked' ";
				$content .=       "type='checkbox' value='" . $filter->id . "' onClick=makePrefixSel()>";
				$content .=   "</td>";
				$content .=   "<td width=5%></td>";
				$content .= "</tr>";
			endforeach;
		}
		$content .= "</table></center><br/>";
		return $content;
	}



	private $apiHtml = <<<END_OF_API_HTML

		<div id="jelly-products-filter-container">
			<!--Products Filter-->
			<link rel="stylesheet" type="text/css" href="<substitute-path>/products.css" />
			<substitute-data>
		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	var isDet = 0;

	durationAll = '';
	priceAll = '';

	department = Array();

	function makePrefixSel()
	{
		// 1D arrays
		mastDuration = Array();
		mastPrice = Array();
		mastDepartment = Array();
		mastFeature = Array();
		// 2D arrays (each preset)
		checkDuration = Array();
		checkPrice = Array();
		checkDepartment = Array();
		checkFeature = Array();
		av = document.getElementsByName("preset[]");
		// Presets
		if (av.length > 0)
		{
			for (i = 0; i < av.length; i++)
			{
				if (av[i].checked)
				{
					checkDuration[i] = Array();
					checkPrice[i] = Array();
					checkDepartment[i] = Array();
					checkFeature[i] = Array();
//					alert(presetArr[i]);
					strArr = presetArr[i].split("&");
					for (j = 0; j < strArr.length; j++)
					{
						// Eg duration=6|7|8
						//    price=1|2|3
						//    feature=29.209|29.210|30.50
						//    department=29|30
						//alert(strArr[j]);
						itemArr = strArr[j].split("=");

//alert(itemArr[0]); //department
//alert(itemArr[1]); //27|30

						itemValueArr = itemArr[1].split("|");
						for (k = 0; k < itemValueArr.length; k++)
						{
							if (itemArr[0] == 'duration')
								checkDuration[i][k] = itemValueArr[k];
							else if (itemArr[0] == 'price')
								checkPrice[i][k] = itemValueArr[k];
							else if (itemArr[0] == 'department')
								checkDepartment[i][k] = itemValueArr[k];
							else if (itemArr[0] == 'feature')
								checkFeature[i][k] = itemValueArr[k];
						}
					}
				}
			}
			// Now build the master list
			// Duration
			for (i = 0; i < checkDuration.length; i++)
			{
				if (av[i].checked == false)
					continue;
				for (j = 0; j < checkDuration[i].length; j++)
				{
					reject = 0;
					for (ii = 0; ii < checkDuration.length; ii++)
					{
						if (av[ii].checked == false)
							continue;
						if (checkDuration[ii].indexOf(checkDuration[i][j]) == -1)
						{
							reject = 1;
							break;
						}
					}
					if (reject == 0)
					{
						if (mastDuration.indexOf(checkDuration[i][j]) == -1)
							mastDuration.push(checkDuration[i][j]);
					}
				}
			}
			// Price
			for (i = 0; i < checkPrice.length; i++)
			{
				if (av[i].checked == false)
					continue;
				for (j = 0; j < checkPrice[i].length; j++)
				{
					reject = 0;
					for (ii = 0; ii < checkPrice.length; ii++)
					{
						if (av[ii].checked == false)
							continue;
						if (checkPrice[ii].indexOf(checkPrice[i][j]) == -1)
						{
							reject = 1;
							break;
						}
					}
					if (reject == 0)
					{
						if (mastPrice.indexOf(checkPrice[i][j]) == -1)
							mastPrice.push(checkPrice[i][j]);
					}
				}
			}
			// Department
			for (i = 0; i < checkDepartment.length; i++)
			{
				if (av[i].checked == false)
					continue;
				for (j = 0; j < checkDepartment[i].length; j++)
				{
					reject = 0;
					for (ii = 0; ii < checkDepartment.length; ii++)
					{
						if (av[ii].checked == false)
							continue;
						if (checkDepartment[ii].indexOf(checkDepartment[i][j]) == -1)
						{
							reject = 1;
							break;
						}
					}
					if (reject == 0)
					{
						if (mastDepartment.indexOf(checkDepartment[i][j]) == -1)
							mastDepartment.push(checkDepartment[i][j]);
					}
				}
			}
			// Feature
			for (i = 0; i < checkFeature.length; i++)
			{
				if (av[i].checked == false)
					continue;
				for (j = 0; j < checkFeature[i].length; j++)
				{
					reject = 0;
					for (ii = 0; ii < checkFeature.length; ii++)
					{
						if (av[ii].checked == false)
							continue;
						if (checkFeature[ii].indexOf(checkFeature[i][j]) == -1)
						{
							reject = 1;
							break;
						}
					}
					if (reject == 0)
					{
						if (mastFeature.indexOf(checkFeature[i][j]) == -1)
							mastFeature.push(checkFeature[i][j]);
					}
				}
			}
		}

		sel = '?layout=preset&sid=' + SID;

		// Store preset selections
		var str = '';
		for (i = 0; i < av.length; i++)
		{
			if (av[i].checked)
			{
				if (str != '') str += '|';
				str += av[i].value;
			}
		}
		sel += '&filter=' + str;

		// Duration
		str = '';
		for (i = 0; i < mastDuration.length; i++)
		{
			if (str != '') str += '|';
			str += mastDuration[i];
		}
		sel += '&duration=' + str;

		// Price
		str = '';
		for (i = 0; i < mastPrice.length; i++)
		{
			if (str != '') str += '|';
			str += mastPrice[i];
		}
		sel += '&price=' + str;

		// Department
		str = '';
		for (i = 0; i < mastDepartment.length; i++)
		{
			if (str != '') str += '|';
			str += mastDepartment[i];
		}
		sel += '&department=' + str;

		// Feature
		str = '';
		for (i = 0; i < mastFeature.length; i++)
		{
			if (str != '') str += '|';
			str += mastFeature[i];
		}
		sel += '&feature=' + str;

//		alert(sel);

		// Activate the link
		window.location.href = sel;
	}

	function makeSel()
	{
		sel = '?layout=index&sid=' + SID;

		// Duration
		av=document.getElementsByName("duration[]");
		if (av.length > 0)
		{
			var str = '';
		   for (var i = 0; i < av.length; i++)
		   {
			   if (av[i].checked)
				{
					if (str != '') str += '|';
					str += av[i].value;
				}
			}
			sel += '&duration=' + str; 
		}

		// Price
		av=document.getElementsByName("price[]");
		if (av.length > 0)
		{
			var str = '';
		   for (var i = 0; i < av.length; i++)
		   {
			   if (av[i].checked)
				{
					if (str != '') str += '|';
					str += av[i].value;
				}
			}
			sel += '&price=' + str; 
		}

		// Feature
		av=document.getElementsByName("feature[]");
		if (av.length > 0)
		{
			var str = '';
		   for (var i = 0; i < av.length; i++)
		   {
				if (av[i].checked)
				{
					if (str != '') str += '|';
					str += av[i].value;
					d = av[i].value.split(".");
					if (department.indexOf(d[0]) == -1)
						department.push(d[0]);
				}
			}
			sel += '&feature=' + str; 
		}
		sel+='&department=' + department.join('|');


		// If we're in the backend keep the 'showurl' going
		chkUrl = document.URL;    // Old url we came in with (currently displayed in browser)
		if (chkUrl.indexOf("&showurl") != -1)
			sel += "&showurl=true";

		// Activate the link
		window.location.href = sel;
	}

	jQuery(document).ready(function($){
	});

	function showUrl()
	{
		// If we're in the backend we can pop up the url (would be displayed in browser if werent an iframe)
		chkUrl = document.URL;
		chkUrl = chkUrl.substring(0, chkUrl.length - 13);   // Chop off the 'showurl=true' at the end
		box = document.getElementById("showFilterString");
		box.innerHTML = chkUrl;
		box.style.display = "block";
		//alert(chkUrl);
	}

	$('.filter-detail').click(function(){
			isDet = 1;
		$('.filter-detail', this).toggle(); // p00f
	});

	$('.filter-header').click(function(){
		if (isDet == 1)
		{
			isDet = 0;
			return;
		}
		$('.filter-detail', this).toggle(); // p00f
		if ($('.filter-detail', this).is(":visible"))
		{
			var divElem = $("div.filter-detail", this).children();
			var cbElem = divElem.find(':checkbox');
			cbElem.prop('checked', true);
			makeSel();
		}
		else
		{
			var divElem = $("div.filter-detail", this).children();
			var cbElem = divElem.find(':checkbox');
			cbElem.prop('checked', false);
			makeSel();
		}
	});

END_OF_API_JS;

}
?>
