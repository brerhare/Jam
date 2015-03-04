<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Product filter
 *
 * Notes
 * -----
 * This will use the width and height of your container
 */

class products
{

    //Defaults
    private $mode = 'filter';

    // Globals
    private $clipBoard = "";
    private $uid = "";//Yii::app()->session['uid'];
    private $defaultDepartment = "";
    private $defaultWidth = "100%";
    private $departmentSel = array();
    private $featureSel = array();
    private $durationCheck = array();
    private $durationSel = array();
    private $priceCheck = array();
    private $priceSel = array();

// Not used ...
    public $apiOption = array(
    );

    /*
     * Set up any code pre-requisites (onload/document-ready reqs)
     * Apply options
     * Return an array containing [0]localContent [1]globalContent
     */
    public function init($options, $jellyRootUrl)
    {
//      var_dump( $options );
        $this->uid = Yii::app()->session['uid'];

        // Generate the content into the html, replacing any <substituteN> tags
        $content = "";
        foreach ($options as $opt => $val)
        {
            switch ($opt)
            {
                case "mode":
                    $this->mode = $val;
                    break;
                default:
                    break;
            }
        }

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
//file_put_contents('/tmp/xyz.txt', $retArr[0]);
//die('xy='.strlen($retArr[0]));
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

    private function buildUserInputs()
    {
        $content = "";

		// If we are in 'preset' mode this still needs to run to build the lists, but we hide it
		if ($this->mode == 'preset')
			$content .= "<div id='filter-hidden-in-preset-mode' style='display:none'>";
		if (!($this->hasFilterOrFeature()))
			$content .= "<div id='filter-hidden-in-no-selected-department-mode' style='display:none'>";

        // Duration band (always shown if exists)
        $lastShown = 0;
        $durations = DurationBand::model()->findAll(array('order'=>'max', 'condition'=>'uid=' . $this->uid));
        if ($durations)
        {
            if (isset($_GET['duration']))
                $this->durationSel = explode('|', $_GET['duration']);
            else
                array_push($this->durationSel, '*');
            $content .= "<br>";
            $content .= "<div class='filter-header'>Duration<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($durations as $duration):
                $match = false;
                if (in_array($duration->id, $this->durationSel))
                    $match = true;
                if (!(isset($_GET['duration'])))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='duration[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $duration->id . "' onClick=makeSel()>" . $lastShown . " - " . $duration->max . " mins";
                array_push($this->durationCheck, $duration->id . '_' . $lastShown . '_' . $duration->max);
                $lastShown = $duration->max;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Price band (always shown if exists)
        $lastShown = 0;
        $prices  = PriceBand::model()->findAll(array('order'=>'max', 'condition'=>'uid=' . $this->uid));
        if ($prices)
        {
            if (isset($_GET['price']))
                $this->priceSel = explode('|', $_GET['price']);
            else
                array_push($this->priceSel, '*');
            $content .= "<br>";
            $content .= "<div class='filter-header'>Price<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($prices as $price):
                $match = false;
                if (in_array($price->id, $this->priceSel))
                    $match = true;
                if (!(isset($_GET['price'])))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='price[]' "; 
                if ($match) $content .= " checked='checked' ";

                if ($lastShown == 0)
                {
                    $from = "";
                    $range = "Under £";
                }
                else
                {
                    $from = "£" . str_replace(".00", "", $lastShown);
                    $range = " - £";
                }
                $to = str_replace(".00", "", $price->max);
                $content .= "type='checkbox' value='" . $price->id . "' onClick=makeSel()> " . $from . $range . $to;
                array_push($this->priceCheck, $price->id . '_' . $lastShown . '_' . $price->max);                
                $lastShown = $price->max;
                $content .= "</label><br>";
            endforeach;
            // Handle over max
            $match = false;
            if (in_array(99999, $this->priceSel))
                $match = true;
            if (!(isset($_GET['price'])))
                $match = true;
            $content .= "<label class='checkbox'> ";
            $content .= "<input name='price[]' "; 
            if ($match) $content .= " checked='checked' ";
            $content .= "type='checkbox' value='" . 99999 . "' onClick=makeSel()> " . "Over £" . str_replace(".00", "", $lastShown);
            array_push($this->priceCheck, 99999 . '_' . $lastShown . '_' . 9999999.99);      
            $content .= "</div>";
            $content .= "</div>";
        }
       // Departments with features
        $departments  = Department::model()->findAll(array('order'=>'name', 'condition'=>'uid=' . $this->uid));
        if ($departments)
        {
            if (isset($_GET['department']))
                $this->departmentSel = explode('|', $_GET['department']);
			else if (isset(Yii::app()->session['department']))
				$this->departmentSel = explode('|', Yii::app()->session['department']); 

            if (isset($_GET['feature']))
                $this->featureSel = explode('|', $_GET['feature']);
            else
                array_push($this->featureSel, '*');

            foreach ($departments as $department):

				if (!($this->hasFilterOrFeature()))
					array_push($this->departmentSel, $department->id);

                $vis = "";
                if (!(in_array($department->id, $this->departmentSel)))
                    $vis = " style='display:none;' ";
                $content .= "<br>";
                $content .= "<div id='h' class='filter-header'> <a style='color:#575757;' href='#' >" . $department->name . "</a><br>";
                $features  = Feature::model()->findAll(array('order'=>'name', 'condition'=>'product_department_id=' . $department->id));
                $content .= "<div id='d' class='filter-detail'" . $vis . ">";
                foreach ($features as $feature):
                    $match = false;
//echo "looking for [" . $department->id . "+" . $feature->id . "] in " . $this->featureSel[0] . "<br>";
                    if ((in_array($department->id . "." . $feature->id, $this->featureSel)))
                        $match = true;
                    if ((in_array($department->id, $this->departmentSel)) &&(!(isset($_GET['feature']))))
                        $match = true;
                    $content .= "<label class='checkbox'> ";
                    $content .= "<input id='dummy' name='feature[]' ";
                    if ($match) $content .= " checked='checked' ";
                    $content .= "type='checkbox' value='" . $department->id . '.' . $feature->id . "' onClick=makeSel()>" . $feature->name;
                    $content .= "</label><br>";
                endforeach;
                $content .= "</div>";
                $content .= "</div>";
            endforeach;
        }
        $content .= "</div>";

		if ($this->mode == 'preset')
			$content .= "<div>";
		if (!($this->hasFilterOrFeature()))
			$content .= "</div>";

        return $content;
    }

    private function selectMatchingProducts()
    {
//die('xx');
        $productList = "";
        // Each selected department one at a time
        $deptStr = "";
        $deptFeatureStr = "";
        for ($i = 0; $i < count($this->departmentSel); $i++)
        {
            $deptStr = "";
            if ($this->departmentSel[$i] == "")
                continue;
            if ($deptStr != "")
                $deptStr .= " or ";
            $deptStr .= "product_department_id=" . $this->departmentSel[$i];

            // Get all the products for this department
            $criteria = new CDbCriteria;
            $criteria->addCondition("uid=" . $this->uid);
            $criteria->addCondition($deptStr);
            $products = Product::model()->findAll($criteria);
            foreach ($products as $product)
            {

				// KKK
				if ((isset(Yii::app()->session['department'])) && ($product->product_department_id != Yii::app()->session['department']))
					continue;

//echo 'dept ' . $this->departmentSel[$i] . ' product ' . $product->id . '<br>';
                // Each selected feature for this particular department (to match against this particular product)
                $deptFeatureStr = "";
                for ($j = 0; $j < count($this->featureSel); $j++)
                {
//echo 'feature<br>';
                    if ($this->featureSel[$j] == "")
                        continue;
//echo $this->departmentSel[$i] . ':' . $this->featureSel[$j] . '<br>';
                    // The feature is listed as dept.feature eg 5.9 so we only want the '9' part
                    $f = explode('.', $this->featureSel[$j]);
//echo 'lookup' . $f[1] . '<br>';
                    if (($f[0] != $this->departmentSel[$i]) && (isset($_GET['feature'])))
                        continue;
                    // Is there a feature record for this product?
                    $criteria = new CDbCriteria;
                    $criteria->addCondition("product_product_id = " . $product->id);
                    if ($this->featureSel[$j] != '*')
                        $criteria->addCondition("product_feature_id = " . $f[1]);
                    $feature = ProductHasFeature::model()->find($criteria);
                    if ($feature)
                    {
                        // Now check duration range
                        $found = false;
                        for ($k = 0; $k < count($this->durationCheck); $k++)
                        {
                            $arr = explode('_', $this->durationCheck[$k]);
                            if (((int)$product->duration >= (int)$arr[1]) && ((int)$product->duration <= (int)$arr[2]))
                            {
                                if ((in_array($arr[0], $this->durationSel)) || (!(isset($_GET['duration']))))
                                {
                                    $found = true;
                                    break;
                                }
                            }
                        }
                        if ((!($found)) && (count($this->durationCheck) > 0))
                            continue;

                        // Now check price range
                        $found = false;
                        for ($l = 0; $l < count($this->priceCheck); $l++)
                        {
                            $arr = explode('_', $this->priceCheck[$l]);

                            $criteria = new CDbCriteria;
                            $criteria->addCondition("product_product_id = " . $product->id);
                            $options = ProductHasOption::model()->findAll($criteria);
                            foreach ($options as $option)
                            {
//echo $product->name . ' price='.(int)$option->price.' min='.(int)$arr[1].' max='.(int)$arr[2].'<br>';
                                if (((int)$option->price >= (int)$arr[1]) && ((int)$option->price <= (int)$arr[2]))
                                {
                                    if ((in_array($arr[0], $this->priceSel)) || (!(isset($_GET['price']))))
                                    {
                                        $found = true;
                                        break;
                                    }
                                }
                            }
                        }
                        if ((!($found)) && (count($this->priceCheck) > 0))
                            continue;

                        // We have a winner
                        if ($productList != "")
                            $productList .= "|";
                        $productList .= $product->id;
                    }
					else if (!($this->hasFilterOrFeature()))
					{
                        // We have a winner
                        if ($productList != "")
                            $productList .= "|";
                        $productList .= $product->id;
					}
                }
            }
        }
        return $productList;
    }

private function hasFilterOrFeature()
{
	$ret = Filter::model()->findAll(array('condition'=>'uid=' . $this->uid));
	if ($ret)
		return $ret;
	$ret = Feature::model()->findAll(array('condition'=>'uid=' . $this->uid));
	if ($ret)
		return $ret;
	return false;
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
