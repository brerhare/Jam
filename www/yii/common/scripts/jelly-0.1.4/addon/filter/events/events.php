<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Event filter
 *
 * Notes
 * -----
 * This will use the width and height of your container
 */

class events
{
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

        // Datepicker
        $content .= "<br>";
        $content .= "<div class='filter-header'>Date<br>";
        $content .= "<div class='filter-detail'>";



        $content .= "<input type='text' id='datepicker' style='width:70px'/>";



        $content .= "</div>";
        $content .= "</div>";              

        // Price band (always shown if exists)
        $prices  = PriceBand::model()->findAll(array('order'=>'id'));
        if ($prices)
        {
            $content .= "<br>";
            $content .= "<div class='filter-header'>Price Band<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($prices as $price):
                $match = false;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='price[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $price->id . "'>" . $price->name;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Wild Seasons fields start here
        // ------------------------------

         // Grade
        $grades = array("Easy", "Medium", "Family");
        if ($grades)
        {   
            $content .= "<br>";
            $content .= "<div class='filter-header'>grade<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($grades as $grade):
                $match = false;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='grade[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $grade . "'>" . $grade;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }


/*
       // Departments with features 
        $departments  = Department::model()->findAll(array('order'=>'name', 'condition'=>'uid=' . $uid));
        if ($departments)
        {
            foreach ($departments as $department):
                $content .= "<br>";
                $content .= "<div id='h' class='filter-header'> <a href='#' >" . $department->name . "</a><br>";
                $features  = Feature::model()->findAll(array('order'=>'name', 'condition'=>'product_department_id=' . $department->id));
                $content .= "<div id='d' class='filter-detail'>";
                foreach ($features as $feature):
                    $match = false;
                    $content .= "<label class='checkbox'> ";
                    $content .= "<input name='feature[]' "; 
                    if ($match) $content .= " checked='checked' ";
                    $content .= "type='checkbox' value='" . $feature->id . "'>" . $feature->name;
                    $content .= "</label><br>";
                endforeach;
                $content .= "</div>";
                $content .= "</div>";
            endforeach;
            //$content .= "</div>";
        }
*/
        $content .= "</div>";
        $html = str_replace("<substitute-data>", $content, $this->apiHtml);
        $this->apiHtml = $html;


        // This is kind of a standard replace
        $this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

        $retArr = array();
        $retArr[0] = $this->apiHtml;
        $retArr[1] = $this->apiJs;
        return $retArr;
    }
    // @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

    private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-products-filter-container">
            <!--Products Filter-->
            <link rel="stylesheet" type="text/css" href="<substitute-path>/events.css" />

            <!-- Date support -->
            <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
            
            <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
            <link rel="stylesheet" href="/resources/demos/style.css" />


            <substitute-data>
        </div>

END_OF_API_HTML;

    private $apiJs = <<<END_OF_API_JS

    var isDet = 0;

    jQuery(document).ready(function($){

        // datepicker with UK date format
        $( "#datepicker" ).datepicker({ dateFormat: 'dd/mm/yy' })

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

    //Datepicker
    $(function() {
        $( "#datepicker" ).datepicker();
        alert('xxx');
    });



END_OF_API_JS;

}
?>
