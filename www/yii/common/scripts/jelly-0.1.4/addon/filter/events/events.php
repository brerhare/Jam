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
	private $programId = 0;

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

        // Check if any program has been selected in the iframe
        if (isset($_GET['programid']))
            $this->programId = (int) $_GET['programid'];

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
        $content = "";
		$content .= "<script>var programId = " . $this->programId . ";</script>";
        $content .= "<div style='position:fixed; color:#575757;'>";      // Your basic solemn grey font color
        $uid = Yii::app()->session['uid'];

        $twistyIcon = "<img style='padding-right:3px' title='" . 'Show more' . "' src='img/" . 'open-twisty.png' . "' >";

        $content .= "<input type='button' id='textresetbutton' style='float:left;padding:3px; width:60px' onClick='resetEvents()' value='Reset'>";
        $content .= "<input type='button' id='textsearchbutton' style='float:right;padding:3px; width:60px' onClick='searchEvents()' value='Search'>";
		$content .= "<br/>";
        $content .= "<div style='float:left'>";
        $content .= "<input type='text' id='textsearchbox' style='width:116px' title='Input text to search for' value='" . '' . "'>";
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

/*****/
       	$openProgram = false;
		if ($this->programId == 0)
		{
        	// Program
        	$programs = Program::model()->findAll(array('order'=>'id'));
        	if ($programs)
        	{
            	$programSel = array();
            	if (isset($_GET['program']))
                	$programSel = explode('|', $_GET['program']);
            	$content .= "<br>";
	
            	// $content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Program</a><br>";
            	// $content .= "<a href='#'></a><div class='filter-header'>" . $twistyIcon . "Program<br>";
            	$content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Program</a><br>";
	
            	$content .= "<div id='program-detail' class='filter-detail'>";
            	foreach ($programs as $program):
                	$match = false;
                	if (in_array($program->id, $programSel))
                	{
                    	$match = true;
                    	$openProgram = true;
                	}
                	$content .= "<label class='checkbox'> ";
                	$content .= "<input name='program[]' "; 
                	if ($match) $content .= " checked='checked' ";
                	$content .= "type='checkbox' value='" . $program->id . "' onClick=makeSel()>" . $program->name;
                	$content .= "</label><br>";
            	endforeach;
            	$content .= "</div>";
            	$content .= "</div>";
        	}
		}
/*****/

        // Interest
        $interests  = Interest::model()->findAll(array('order'=>'id'));
        $openInterest = false;
        if ($interests)
        {
            $interestSel = array();
            if (isset($_GET['interest']))
                $interestSel = explode('|', $_GET['interest']);
            $content .= "<br>";

            // $content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Interest</a><br>";
            // $content .= "<a href='#'></a><div class='filter-header'>" . $twistyIcon . "Interest<br>";
            $content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Interest</a><br>";

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

            //$content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Format</a><br>";
            $content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Format</a><br>";

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

            //$content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Facility</a><br>";
            $content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Facility</a><br>";

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

        // Location
		$locations = array(
			"DG1" => "Dumfries",
			"DG2" => "Dumfries",
			"DG3" => "Thornhill",
			"DG4" => "Sanquhar",
			"DG5" => "Dalbeattie",
			"DG6" => "K'cudbright",
			"DG7" => "C. Douglas",
			"DG8" => "N. Stewart",
			"DG9" => "Stranraer",
			"DG10" => "Moffat",
			"DG11" => "Lockerbie",
			"DG12" => "Annan",
			"DG13" => "Langholm",
			"DG14" => "Canonbie",
			"DG16" => "Gretna",
		);
        $openLocation = false;
        $locationSel = array();
        if (isset($_GET['location']))
            $locationSel = explode('|', $_GET['location']);
        $content .= "<br>";

        //$content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Location</a><br>";
        $content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Location</a><br>";

        $content .= "<div id='location-detail' class='filter-detail'>";
        foreach ($locations as $location => $name):
            $match = false;
			if (in_array($location, $locationSel))
            {
                $match = true;
                $openLocation = true;
            }
            $content .= "<label class='checkbox'> ";
            $content .= "<input name='location[]' "; 
            if ($match) $content .= " checked='checked' ";
            $content .= "type='checkbox' value='" . $location . "' onClick=makeSel()>" . $location . ' ' . $name;
            $content .= "</label><br>";
        endforeach;
        $content .= "</div>";
        $content .= "</div>";

        // Price band (always shown if exists)
        $prices  = PriceBand::model()->findAll(array('order'=>'id'));
        $openPrice = false;
        if ($prices)
        {
            $pbSel = array();
            if (isset($_GET['pb']))
                $pbSel = explode('|', $_GET['pb']);
            $content .= "<br>";

            //$content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Price Band</a><br>";
            $content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Price Band</a><br>";

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

        $openGrade = false;
		if ($this->programId == 6)
		{

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

            	//$content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Grade</a><br>";
            	$content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Grade</a><br>";

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
		}

        $openAbType = false;
		if ($this->programId == 12)
		{

        	// Absolute Classics fields start here
        	// -----------------------------------

         	// Type
        	$abtypes = array( "Festival", "Series");
        	if ($abtypes)
        	{   
            	$abtypesel = array();
            	if (isset($_GET['abtype']))
                	$abtypesel = explode('|', $_GET['abtype']);
            	$content .= "<br>";

            	//$content .= "<a href='#'><div class='filter-header'>" . $twistyIcon . "Type</a><br>";
            	$content .= "<div class='filter-header'>" . $twistyIcon . "<a href='#'>Type</a><br>";

            	$content .= "<div id='abtype-detail' class='filter-detail'>";
            	foreach ($abtypes as $abtype):
                	$match = false;
                	if (in_array($abtype, $abtypesel))
                	{
                    	$match = true;
                    	$openAbType = true;
                	}
                	$content .= "<label class='checkbox'> ";
                	$content .= "<input name='abtype[]' "; 
                	if ($match) $content .= " checked='checked' ";
                	$content .= "type='checkbox' value='" . $abtype . "' onClick=makeSel()>" . $abtype;
                	$content .= "</label><br>";
            	endforeach;
            	$content .= "</div>";
            	$content .= "</div>";
        	}
		}

		$content .= "<br/>";
		$content .= "<div style='float:left;padding-left:40px'><a href=javascript:printSelectedHeads()><b><img style='margin-top:0px; margin-left:0px' title='Print' src='img/print.jpg'></a></div>";

        // Open twisty any selected groups of filters
        $content .= "<script>";
		if ($this->programId == 0)
		{
        	if (!($openProgram))
            	$content .= "document.getElementById('program-detail').style.display='none';";
		}
        if (!($openInterest))
            $content .= "document.getElementById('interest-detail').style.display='none';";
        if (!($openFormat))
           $content .= "document.getElementById('format-detail').style.display='none';";
        if (!($openFacility))
            $content .= "document.getElementById('facility-detail').style.display='none';";
        if (!($openLocation))
            $content .= "document.getElementById('location-detail').style.display='none';";
        if (!($openPrice))
            $content .= "document.getElementById('price-detail').style.display='none';";

		// Wild Seasons
		if ($this->programId == 6)
		{
        	if (!($openGrade))
            	$content .= "document.getElementById('grade-detail').style.display='none';";
		}

		// Absolute Classics
		if ($this->programId == 12)
		{
        	if (!($openAbType))
            	$content .= "document.getElementById('abtype-detail').style.display='none';";
		}

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
    abtype = '';

    function makeSel()
    {
		// Basic start
        sel = '?layout=index';

		// Program lock?
		sel += '&programid=' + programId;

        // Date
        sel += '&sdate=' + selSDate;
        sel += '&edate=' + selEDate;
        sel += '&textsearch=' + textSearch;

        // Program
        av=document.getElementsByName("program[]");
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
            sel += '&program=' + str; 
        }

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

        // Location
        av=document.getElementsByName("location[]");
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
            sel += '&location=' + str; 
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

        // AbType
        av=document.getElementsByName("abtype[]");
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
            sel += '&abtype=' + str; 
        }

        // Activate the link
//alert(sel);
        window.location.href = sel;
    }

    function searchEvents()
    {
        textSearch = document.getElementById('textsearchbox').value;
        makeSel();
    }

    function resetEvents()
    {
        document.getElementById('textsearchbox').value = "";
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
