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
        $dt = '';
        if (isset($_GET['date']))
            $dt = $_GET['date'];
        $content .= "<br>";
        $content .= "<div class='filter-header'>Date<br>";
        $content .= "<div class='filter-detail hasDatepicker'>";
        $content .= "<input type='text' id='datepicker' style='width:70px' value='" . $dt . "'>";
        $content .= "</div>";
        $content .= "</div>";              

        // Interest
        $interests  = Interest::model()->findAll(array('order'=>'id'));
        if ($interests)
        {
            $interestSel = array();
            if (isset($_GET['interest']))
                $interestSel = explode('|', $_GET['interest']);
            $content .= "<br>";
            $content .= "<div class='filter-header'>Interest<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($interests as $interest):
                $match = false;
                if (in_array($interest->id, $interestSel))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='interest[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $interest->id . "' onClick=makeSel()>" . $interest->name;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Price band (always shown if exists)
        $prices  = PriceBand::model()->findAll(array('order'=>'id'));
        if ($prices)
        {
            $pbSel = array();
            if (isset($_GET['pb']))
                $pbSel = explode('|', $_GET['pb']);
            $content .= "<br>";
            $content .= "<div class='filter-header'>Price Band<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($prices as $price):
                $match = false;
                if (in_array($price->id, $pbSel))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='price[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $price->id . "' onClick=makeSel()>" . $price->name;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Wild Seasons fields start here
        // ------------------------------

         // Grade
        $grades = array( "Family", "Easy", "Medium", "Hard", "Task");
        if ($grades)
        {   
            $gradeSel = array();
            if (isset($_GET['grade']))
                $gradeSel = explode('|', $_GET['grade']);
            $content .= "<br>";
            $content .= "<div class='filter-header'>grade<br>";
            $content .= "<div class='filter-detail'>";
            foreach ($grades as $grade):
                $match = false;
                if (in_array($grade, $gradeSel))
                    $match = true;
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='grade[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $grade . "' onClick=makeSel()>" . $grade;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

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

            <!-- @@NB This is a themeroller cookup of the smoothness theme -->
            <link rel="stylesheet" href="<substitute-path>/jquery-ui-1.9.2.custom.css">
            
            <script src="//code.jquery.com/ui/1.9.1/jquery-ui.js"></script>


            <substitute-data>
        </div>

END_OF_API_HTML;

    private $apiJs = <<<END_OF_API_JS

    var isDet = 0;
    selDate = '';
    priceBand = '';
    grade = '';

    function makeSel()
    {
        // Date
        sel = '?layout=index&date=' + selDate;

        // Interest
        av=document.getElementsByName("interest[]");
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
            sel += '&interest=' + str; 
        }

        // Price band
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
            sel += '&pb=' + str; 
        }

        // Grade
        av=document.getElementsByName("grade[]");
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
            sel += '&grade=' + str; 
        }
        // Activate the link
        window.location.href = sel;
    }

    jQuery(document).ready(function($){
        selDate = document.getElementById("datepicker").value;
    });


	// @@EG Datepicker styling - has no effect anywhere else
	// Roll your own themeroller with css scope of .hasDatepicker
	// Make the datepicker container div class .hasDatepicker (see above)
	// The next line must be added right after the $('#datepicker').datepicker({ etc up till the });
	// $('#ui-datepicker-div').wrap('<div class="hasDatepicker"></div>');


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
    $('#datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        timeFormat: "hh:mm",    // HH is 24 hour clock, hh is 12 hour clock
        onSelect: function(
        dateText, inst) {
            selDate = dateText;
            makeSel();
        }
    });
	// @@EG next line applies to the Datepicker styling about 30 lines up
	$('#ui-datepicker-div').wrap('<div class="hasDatepicker"></div>');


END_OF_API_JS;

}
?>
