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

    //Defaults
    private $defaultDepartment = "";
    private $defaultWidth = "100%";

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
        $content = "<div style='color:#575757;'>";      // Your basic solemn grey font color
        $uid = Yii::app()->session['uid'];

        // Duration band (always shown if exists)
        $durations = DurationBand::model()->findAll(array('order'=>'max', 'condition'=>'uid=' . $uid));
        if ($durations)
        {
            $durationSel = array();
            if (isset($_GET['duration']))
                $durationSel = explode('|', $_GET['duration']);
            $content .= "<br>";
            $content .= "<div class='filter-header'>Duration<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($durations as $duration):
                $match = false;
                if (in_array($duration->id, $durationSel))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='duration[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $duration->id . "' onClick=makeSel('" . $_GET['sid'] . "')>" . $duration->max . " mins";
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Price band (always shown if exists)
        $lastShown = 0;
        $prices  = PriceBand::model()->findAll(array('order'=>'max', 'condition'=>'uid=' . $uid));
        if ($prices)
        {
            $priceSel = array();
            if (isset($_GET['price']))
                $priceSel = explode('|', $_GET['price']);
            $content .= "<br>";
            $content .= "<div class='filter-header'>Price<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($prices as $price):
                $match = false;
                if (in_array($price->id, $priceSel))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='price[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $price->id . "' onClick=makeSel('" . $_GET['sid'] . "')> £" . $lastShown . " - £" . $price->max;
                $lastShown = $price->max;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

       // Departments with features
        $departments  = Department::model()->findAll(array('order'=>'name', 'condition'=>'uid=' . $uid));
        if ($departments)
        {
            foreach ($departments as $department):
                $vis = "";
                if (!($this->selectDept($department->id)))
                    $vis = " style='display:none;' ";
                $content .= "<br>";
                $content .= "<div id='h' class='filter-header'> <a href='#' >" . $department->name . "</a><br>";
                $features  = Feature::model()->findAll(array('order'=>'name', 'condition'=>'product_department_id=' . $department->id));
                $content .= "<div id='d' class='filter-detail'" . $vis . ">";
                foreach ($features as $feature):
                    $match = false;
                    $content .= "<label class='checkbox'> ";
                    $content .= "<input name='feature[]' "; 
                    if ($match) $content .= " checked='checked' ";
                    $content .= "type='checkbox' value='" . $department->id . '+' . $feature->id . "' onClick=makeSel('" . $_GET['sid'] . "')>" . $feature->name;
                    $content .= "</label><br>";
                endforeach;
                $content .= "</div>";
                $content .= "</div>";
            endforeach;
        }
        $content .= "</div>";
        $html = str_replace("<substitute-data>", $content, $this->apiHtml);
        $this->apiHtml = $html;

        $this->clipBoard = '2|22|222|4|5|6';


        // This is kind of a standard replace
        $this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

        $retArr = array();
        $retArr[0] = $this->apiHtml;
        $retArr[1] = $this->apiJs;
        $retArr[2] = $this->clipBoard;
        return $retArr;
    }

    // Is the department in the request list? If so we want to 'open' it
    private function selectDept($dept)
    {
        $deptArr = explode("|", $_GET['department']);
        foreach ($deptArr as $department)
        {
            $featArr = explode("+", $department);
            if ($dept == $department)
                return true;
        }
        return false;
    }

    // @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

    private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-products-filter-container">
            <!--Products Filter-->
            <link rel="stylesheet" type="text/css" href="<substitute-path>/products.css" />
            <substitute-data>
        </div>

END_OF_API_HTML;

    private $apiJs = <<<END_OF_API_JS

    var isDet = 0;

    durationBand = '';
    department = '';

    function makeSel(sid)
    {
        sel = '?layout=index&sid=' + sid;

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
                    d = av[i].value.split("+");
                    if (department != '') department += '|';
                    department += d[0];
                }
            }
            sel += '&feature=' + str; 
        }

        sel+='&department='+department;
//alert(sel);
        // Activate the link
        window.location.href = sel;
    }

    jQuery(document).ready(function($){
    });

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
    });

END_OF_API_JS;

}
?>
