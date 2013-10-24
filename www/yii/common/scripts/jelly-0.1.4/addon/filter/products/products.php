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
    private $clipBoard = "";
    private $uid = "";//Yii::app()->session['uid'];

    //Defaults
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
                case "department":
                    $department = $val;
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
        if (isset($_GET['showurl']))
            $data .= "<button type='button' onClick='showUrl()' style='color:#ffffff; background-color:#0064cc;'>Show filter string</button><br/>";

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

    private function buildUserInputs()
    {
        $content ="";   
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
                $content .= "type='checkbox' value='" . $price->id . "' onClick=makeSel()> £" . $lastShown . " - £" . $price->max;
                array_push($this->priceCheck, $price->id . '_' . $lastShown . '_' . $price->max);                
                $lastShown = $price->max;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

       // Departments with features
        $departments  = Department::model()->findAll(array('order'=>'name', 'condition'=>'uid=' . $this->uid));
        if ($departments)
        {
            if (isset($_GET['department']))
                $this->departmentSel = explode('|', $_GET['department']);
            if (isset($_GET['feature']))
                $this->featureSel = explode('|', $_GET['feature']);
            else
                array_push($this->featureSel, '*');
            foreach ($departments as $department):
                $vis = "";
                if (!(in_array($department->id, $this->departmentSel)))
                    $vis = " style='display:none;' ";
                $content .= "<br>";
                $content .= "<div id='h' class='filter-header'> <a href='#' >" . $department->name . "</a><br>";
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
                    $content .= "<input id='crap' name='feature[]' ";
                    if ($match) $content .= " checked='checked' ";
                    $content .= "type='checkbox' value='" . $department->id . '.' . $feature->id . "' onClick=makeSel()>" . $feature->name;
                    $content .= "</label><br>";
                endforeach;
                $content .= "</div>";
                $content .= "</div>";
            endforeach;
        }
        $content .= "</div>";
        return $content;
    }

    private function selectMatchingProducts()
    {
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
                        if (!($found))
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
                        if (!($found))
                            continue;

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
        alert(chkUrl);
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
