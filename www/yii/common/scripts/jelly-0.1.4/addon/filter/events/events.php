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

        $content .= "<b>Search</b><br/>";
        $content .= "<div style='float:left'>";
        $content .= "<input type='text' id='textsearchbox' style='width:80px' title='Input text to search for' value='" . '' . "'>";
        $content .= "<input type='button' id='textsearchbutton' style='width:30px' onClick='searchEvents()' value='Go'>";
        $content .= "</div>";
        $content .= "<div style='clear:both'></div>";

        // Start Datepicker
        $dt = '';
        if (isset($_GET['sdate']))
            $dt = $_GET['sdate'];
        $content .= "<br>";
        $content .= "<a href='#'><div class='filter-header'>From</a><br>";
        $content .= "<div class='filter-detail hasDatepicker'>";
        $content .= "<input type='text' id='sdatepicker' style='width:80px' value='" . $dt . "'>";
        $content .= "</div>";
        $content .= "</div>";

        // End Datepicker
        $dt = '';
        if (isset($_GET['edate']))
            $dt = $_GET['edate'];
        $content .= "<br>";
        $content .= "<a href='#'><div class='filter-header'>Until</a><br>";
        $content .= "<div class='filter-detail hasDatepicker'>";
        $content .= "<input type='text' id='edatepicker' style='width:80px' value='" . $dt . "'>";
        $content .= "</div>";
        $content .= "</div>";

        // Interest
        $interests  = Interest::model()->findAll(array('order'=>'id'));
        $openInterest = false;
        if ($interests)
        {
            $interestSel = array();
            if (isset($_GET['interest']))
                $interestSel = explode('|', $_GET['interest']);
            $content .= "<br>";
            $content .= "<a href='#'></img><div class='filter-header'>Interest</a><br>";
            $content .= "<div id='interest-detail' class='filter-detail'>";
            foreach ($interests as $interest):
                $match = false;
                if (in_array($interest->id, $interestSel))
                {
                    $match = true;
                    $openInterest = true;
                }
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='interest[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $interest->id . "' onClick=makeSel()>" . $interest->name;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Format
        $formats  = Format::model()->findAll(array('order'=>'id'));
        $openFormat = false;
        if ($formats)
        {
            $formatSel = array();
            if (isset($_GET['format']))
                $formatSel = explode('|', $_GET['format']);
            $content .= "<br>";
            $content .= "<a href='#'><div class='filter-header'>Format</a><br>";
            $content .= "<div id='format-detail' class='filter-detail'>";
            foreach ($formats as $format):
                $match = false;
                if (in_array($format->id, $formatSel))
                {
                    $match = true;
                    $openFormat = true;
                }
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='format[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $format->id . "' onClick=makeSel()>" . $format->name;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Facility
        $facilities  = Facility::model()->findAll(array('order'=>'id'));
        $openFacility = false;
        if ($facilities)
        {
            $facilitySel = array();
            if (isset($_GET['facility']))
                $facilitySel = explode('|', $_GET['facility']);
            $content .= "<br>";
            $content .= "<a href='#'><div class='filter-header'>Facility</a><br>";
            $content .= "<div id='facility-detail' class='filter-detail'>";
            foreach ($facilities as $facility):
                $match = false;
                if (in_array($facility->id, $facilitySel))
                {
                    $match = true;
                    $openFacility = true;
                }
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='facility[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $facility->id . "' onClick=makeSel()>" . $facility->name;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Price band (always shown if exists)
        $prices  = PriceBand::model()->findAll(array('order'=>'id'));
        $openPrice = false;
        if ($prices)
        {
            $pbSel = array();
            if (isset($_GET['pb']))
                $pbSel = explode('|', $_GET['pb']);
            $content .= "<br>";
            $content .= "<a href='#'><div class='filter-header'>Price Band</a><br>";
            $content .= "<div id='price-detail' class='filter-detail'>";
            foreach ($prices as $price):
                $match = false;
                if (in_array($price->id, $pbSel))
                {
                    $match = true;
                    $openPrice = true;
                }
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
        $openGrade = false;
        if ($grades)
        {   
            $gradeSel = array();
            if (isset($_GET['grade']))
                $gradeSel = explode('|', $_GET['grade']);
            $content .= "<br>";
            $content .= "<a href='#'><div class='filter-header'>grade</a><br>";
            $content .= "<div id='grade-detail' class='filter-detail'>";
            foreach ($grades as $grade):
                $match = false;
                if (in_array($grade, $gradeSel))
                {
                    $match = true;
                    $openGrade = true;
                }
                $content .= "<label class='checkbox'> ";
                $content .= "<input name='grade[]' "; 
                if ($match) $content .= " checked='checked' ";
                $content .= "type='checkbox' value='" . $grade . "' onClick=makeSel()>" . $grade;
                $content .= "</label><br>";
            endforeach;
            $content .= "</div>";
            $content .= "</div>";
        }

        // Open twisty any selected groups of filters
        $content .= "<script>";
        if (!($openInterest))
            $content .= "document.getElementById('interest-detail').style.display='none';";
        if (!($openFormat))
           $content .= "document.getElementById('format-detail').style.display='none';";
        if (!($openFacility))
            $content .= "document.getElementById('facility-detail').style.display='none';";
        if (!($openPrice))
            $content .= "document.getElementById('price-detail').style.display='none';";
        if (!($openGrade))
            $content .= "document.getElementById('grade-detail').style.display='none';";
        $content .= "</script>";

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
    selSDate = '';
    selEDate = '';
    priceBand = '';
    textSearch = '';
    grade = '';

    function makeSel()
    {
        // Date
        sel = '?layout=index&sdate=' + selSDate;
        sel += '&edate=' + selEDate;
        sel += '&textsearch=' + textSearch;

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

        // Format
        av=document.getElementsByName("format[]");
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
            sel += '&format=' + str; 
        }

        // Facility
        av=document.getElementsByName("facility[]");
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
            sel += '&facility=' + str; 
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

    function searchEvents()
    {
        textSearch = document.getElementById('textsearchbox').value;
        makeSel();
    }

    jQuery(document).ready(function($){
        selSDate = document.getElementById("sdatepicker").value;
        selEDate = document.getElementById("edatepicker").value;
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
    $('#sdatepicker').datepicker({
        dateFormat: 'dd-mm-yy',
		showButtonPanel:  true,
        timeFormat: "hh:mm",    // HH is 24 hour clock, hh is 12 hour clock
        onSelect: function(
        dateText, inst) {
            selSDate = dateText;
            makeSel();
        }
    });
    //Datepicker
    $('#edatepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        showButtonPanel:  true,
        timeFormat: "hh:mm",    // HH is 24 hour clock, hh is 12 hour clock
        onSelect: function(
        dateText, inst) {
            selEDate = dateText;
            makeSel();
        }
    });
	// @@EG next line applies to the Datepicker styling about 30 lines up
	$('#ui-datepicker-div').wrap('<div class="hasDatepicker"></div>');


END_OF_API_JS;

}
?>
