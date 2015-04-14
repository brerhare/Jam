<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Products custom code
 *
 * Notes
 * -----
 * None
 */

class eventcode
{
	// Globals
	private $uid = "";
	private $sid = "";
	private $jellyRootUrl = "";
	private $programId = 0;
	private $showMap = false;

	// This is an array list we populate and map points for
	// Contains 4 arrays (ref, icon, hovertip, content)
	private $mapEventId = array();
	private $mapPoint = array();
	private $mapPointProgramId = array();
	private $mapIcon = array();
	private $mapTip = array();
	private $mapContent = array();

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
	  //var_dump( $options );
		$this->jellyRootUrl = $jellyRootUrl;

		$this->uid = Yii::app()->session['uid'];
		$this->sid = Yii::app()->session['sid'];

		// Check if any program has been selected in the iframe
        if (isset($_GET['programid']))
		{
            $this->programId = (int) $_GET['programid'];
//die('isset p='.$this->programId);
		}
//die('isNOTset p='.$this->programId);

		// Check if the big map is required
		$this->showMap = false;
		if ((isset($_GET['map'])) && ($_GET['map'] == "yes"))
			$this->showMap = true;
		if ($this->programId == 6)
			$this->showMap = true;

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "fill_headers":
					return $this->fill_headers($val);
					break;
				case "main_google_map":
					return $this->main_google_map($val);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/*********************************************************************************************************/
	// Invoked by index.jel to create the google map. Val is 'os' only at present
	private function main_google_map($val)
	{

//if ($this->programId == 12)
//////////////////////////////////////////////////////if ($this->programId != 6)
//////////////////////////////////////////////////////////	return;
// NB! I have put in 'nomap' var handling in the filter code. It does nothing but pass it on via JS, but I can use it here

		require(Yii::app()->basePath . "/../scripts/jelly/addon/map/google_os/google_os.php");
		$mapId = 'main_google_map';
		$center = 'NX696834';
		//$center = 'NX976762';

		$content = "";

		// @@EG: Calling a jelly addon directly, not using a jelly script
		$addon = new google_os;
		$optArr = array();
		$optArr['single'] = '1';
		$optArr['id'] = $mapId;
		$optArr['width'] = '700px';
		$optArr['height'] = '370px';
		//$optArr['maptype'] = 'terrain';
		$optArr['inputmode'] = $val;
		$optArr['center'] = $center;
		$optArr['zoom'] = '8';
		$ret = $addon->init($optArr, '/event/scripts/jelly/addon/map/google_os');



		// Suppress the big map if so requested
		if (!($this->showMap))
			$content .= "<div style='display:none'>";
		$content .= $ret[0];
		$content .= '<script>' . $ret[1] . '</script>';
		if (!($this->showMap))
			$content .= "</div>";

		$content .= "<script> centerByOs('" . $center . "'); </script>";

		$apiHtml = $content;
		$apiJs = "";
		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	/*********************************************************************************************************/
	// Invoked by index.jel to create the accordion headers according to the event filter which has just been run
	private function fill_headers($val)
	{
		$content = "";

		$content .= "<style>#hdr { cursor: pointer; cursor: hand; } </style>";	// Hover on header shows hand

		$jsEvents = "var jsEvents=[";

		$content .= "<div id='fb-root'></div>";	// Facebook
		$content .=	"<script>(function(d, s, id) {" .
  						"var js, fjs = d.getElementsByTagName(s)[0];" .
  						"if (d.getElementById(id)) return;" .
  						"js = d.createElement(s); js.id = id;" .
  						"js.src = '//connect.facebook.net/en_GB/all.js#xfbml=1&appId=503217659701725';" .
  						"fjs.parentNode.insertBefore(js, fjs);" .
					"}(document, 'script', 'facebook-jssdk'));</script>" ;

		$content .= "<div id='accordion'>";

		// Check date filter (dd-mm-yyyy)
		if ((isset($_GET['sdate'])) && trim($_GET['sdate'] != ''))
			$dt = $_GET['sdate'];
		else
			$dt = date('d-m-Y');
		$sdate = date("Y-m-d H:i:s", strtotime($dt));

		// Check date filter (dd-mm-yyyy)
		if ((isset($_GET['edate'])) && trim($_GET['edate'] != ''))
			$dt = $_GET['edate'];
		else
			$dt = date('d-m-Y');
		$edate = date("Y-m-d H:i:s", strtotime($dt));
		$edate = str_replace("00:00:00", "23:59:59", $edate);

		// @@NB: Be aware that this should be kept in some kind of sync with the event filters used by main_google_map() (above)
		$criteria = new CDbCriteria;

//		if (!(isset($_GET['edate'])))
//			$criteria->condition = "start >= '" . $sdate . "'"; 
//		else
//			$criteria->condition = "start >= '" . $sdate . "'";

		$criteria->addCondition("start >= '" . $sdate . "'");
		if ((isset($_GET['edate'])) && trim($_GET['edate'] != ''))
			$criteria->addCondition("start <= '" . $edate . "'");
//$criteria->addCondition("ii <= '" . $edate . "'");
		$criteria->order = 'start ASC';
		$events = Event::model()->findAll($criteria);
		$hasEvents = false;
		foreach ($events as $event)
		{
			if ($event->active != 1)
				continue;

			// Check if we are filtering program
			if ($this->programId != 0)
			{
				// Check this event should appear in the selected program
				$criteria = new CDbCriteria;
       			$criteria->addCondition("event_event_id = " .  $event->id);
       			$criteria->addCondition("program_id = " . $this->programId);
				$eventHasProgram=EventHasProgram::model()->find($criteria);
				if (!($eventHasProgram))
					continue;
			}
			else
			{
				if ((isset($_GET['program'])) && trim($_GET['program'] != ''))
				{
					$showProgram = trim($_GET['program']);
					if ($event->program_id != $showProgram)
						continue;

					// Check this event should appear in the selected program
					$criteria = new CDbCriteria;
        			$criteria->addCondition("event_event_id = " .  $event->id);
        			$criteria->addCondition("program_id = " . $showProgram);
					$eventHasProgram=EventHasProgram::model()->find($criteria);
					if (!($eventHasProgram))
						continue;
				}
			}

			// Check this event is approved
			$criteria = new CDbCriteria;
 			$criteria->addCondition("event_event_id = " .  $event->id);
  			$criteria->addCondition("program_id = " . $this->programId != 0 ? $this->programId : 13);
			$eventHasProgram=EventHasProgram::model()->find($criteria);
			if (!($eventHasProgram))
				continue;
			if (!($eventHasProgram->approved))
				continue;

			// Check text search
			if ((isset($_GET['textsearch'])) && trim($_GET['textsearch'] != ''))
			{
				$needle = $_GET['textsearch'];
				//if ($needle = "The Studio")
				//echo $event->address;
				if (((stripos($event->title, $needle)) === false)
				&& ((stripos($event->description, $needle)) == false)
				&& ((stripos($event->address, $needle)) === false)
				&& ((strpos($event->post_code, $needle)) === false)
				&& ((strpos($event->contact, $needle)) === false)
				)
					continue;
			}

			// Check Interest filter
			if ((isset($_GET['interest'])) && trim($_GET['interest'] != ''))
			{
				$arr = explode('|', $_GET['interest']);
				// See if there are any interest record matches
				$criteria = new CDbCriteria;
				$criteria->addCondition ("event_event_id = " . $event->id);
				$cond = "";
				foreach ($arr as $arrItem)
				{
					if ($cond != "")
						$cond .= " or ";
					$cond .= " event_interest_id = " . $arrItem;
				}
				$criteria->addCondition($cond);
				$eventHasInterests = EventHasInterest::model()->findAll($criteria);
				if (count($eventHasInterests) != count($arr))
					continue;
			}

			// Check Format filter
			if ((isset($_GET['format'])) && trim($_GET['format'] != ''))
			{
				$arr = explode('|', $_GET['format']);
				// See if there are any format record matches
				$criteria = new CDbCriteria;
				$criteria->addCondition ("event_event_id = " . $event->id);
				$cond = "";
				foreach ($arr as $arrItem)
				{
					if ($cond != "")
						$cond .= " or ";
					$cond .= " event_format_id = " . $arrItem;
				}
				$criteria->addCondition($cond);
				$eventHasFormats = EventHasFormat::model()->findAll($criteria);
				if (count($eventHasFormats) != count($arr))
					continue;
			}

			// Check Facility filter
			if ((isset($_GET['facility'])) && trim($_GET['facility'] != ''))
			{
				$arr = explode('|', $_GET['facility']);
				// See if there are any facility record matches
				$criteria = new CDbCriteria;
				$criteria->addCondition ("event_event_id = " . $event->id);
				$cond = "";
				foreach ($arr as $arrItem)
				{
					if ($cond != "")
						$cond .= " or ";
					$cond .= " event_facility_id = " . $arrItem;
				}
				$criteria->addCondition($cond);
				$eventHasFacilities = EventHasFacility::model()->findAll($criteria);
				if (count($eventHasFacilities) != count($arr))
					continue;
			}

			// Check Location filter
			if ((isset($_GET['location'])) && trim($_GET['location'] != ''))
			{
				if (trim($event->post_code) == "")
					continue;
				$match = 0;
				$arr = explode('|', $_GET['location']);
				foreach ($arr as $loc)
				{
					if (stristr($event->post_code, $loc))
					{
						$match = 1;
						break;
					}
				}
				if ($match == 0)
					continue;
			}

			// Check Price band filter
			if ((isset($_GET['pb'])) && trim($_GET['pb'] != ''))
			{
				$arr = explode('|', $_GET['pb']);
				if (!(in_array($event->event_price_band_id, $arr)))
					continue;
			}

			// WS Wild Seasons filter
			// Check Grade filter
			if ((isset($_GET['grade'])) && trim($_GET['grade'] != ''))
			{
				// Pick up the Ws record
				$criteria = new CDbCriteria;
				$criteria->condition = "event_id = " . $event->id;
				$ws = Ws::model()->find($criteria);
				if ($ws)
				{
					$arr = explode('|', $_GET['grade']);
					if (!(in_array($ws->grade, $arr)))
						continue;
				}
			}

			// From this point we're committed to adding this record, because of the jsEvents table syncing

			$jsEvents .= '"' . $event->id . '",';
			// Pick up the member
			$criteria = new CDbCriteria;
			$criteria->condition = "id = " . $event->member_id;
			$member = Member::model()->find($criteria);

			// Pick up the program
			$criteria = new CDbCriteria;
			$criteria->condition = 'id = ' . $event->program_id;
			$program = Program::model()->find($criteria);

			$osGridRefOrPostCode = $event->post_code;
			$infoWindowContent = $event->title;

			if ($event->program_id == 6)
			{
				// Pick up the Ws record
				$criteria = new CDbCriteria;
				$criteria->condition = "event_id = " . $event->id;
				$ws = Ws::model()->find($criteria);
				if (!($ws))
					continue;
				$osGridRefOrPostCode = str_replace(' ', '', $ws->os_grid_ref);
				$infoWindowContent = $ws->short_description;
			}

			if (!(in_array($osGridRefOrPostCode, $this->mapPoint)))
			{
				array_push($this->mapPoint, $osGridRefOrPostCode);
				array_push($this->mapPointProgramId, $event->program_id);
				array_push($this->mapTip, "<b>" . $event->title . "</b>". "<br>" . $event->address);
				$infoWindow = "<div style='height:150px; width:300px; '>";
				$infoWindow .= "<h3>" . $event->title . "</h3>";
				$infoWindow .= "<i>" . $this->formatDateString($event->start, $event->end) . "</i><br><br>";

				if (trim($event->thumb_path) != "")
				{
					if (file_exists('userdata/event/thumb/' . $event->thumb_path))
					{
						$img = 'userdata/event/thumb/' . $event->thumb_path;
						$infoWindow .= "<img style='padding-right:10px' align='left' title='" . $member->organisation . "' src='" . $img . "' width='140' height='115'>";
					}
				}

				$infoWindow .= $infoWindowContent;
				$infoWindow .= "</div>";
				array_push($this->mapContent, $infoWindow);
				if (trim($member->avatar_path) != "")
					array_push($this->mapIcon, 'userdata/member/avatar/' . trim($member->avatar_path));
				else
					array_push($this->mapIcon, 'userdata/program/icon/' . trim($program->icon_path));
			}


			$hasEvents = true;

			// The header block
			$content .= "<div id='hdr-" . $event->id . "'> <!-- header -->";

			$content .= "  <div style=float:left>";

			$content .= "    <div id='header-title'>";

			$content .= "      <span style='display:inline-block;width:300px;height:0px;text-overflow:ellipsis'>";
			if ($member)
			{
				if (trim($member->avatar_path) != '')
					$content .= "<img style='padding-right:5px;margin-top:0px; margin-right:0px' title='" . $member->organisation . "' src='userdata/member/avatar/" . $member->avatar_path . "' width='20' height='20'>";
			}

			$content .= $event->title;
			$content .= "      </span>";

			// Ticketing info (if applicable)
			if (($event->ticket_event_id != 0) && ($member))
			{
				// Check this event has any tickets to sell before showing a clickable book button!
		        $eventHasTickets = 0;
        		$criteria = new CDbCriteria;
        		$criteria->addCondition("ticket_event_id = " . $event->ticket_event_id);
        		$areas = Area::model()->findAll($criteria);
        		foreach ($areas as $area):
            		$criteria = new CDbCriteria;
            		$criteria->addCondition("ticket_area_id = " . $area->id);
            		$ticketTypes = TicketType::model()->findAll($criteria);
            		if ($ticketTypes)
						$eventHasTickets = 1;
				endforeach;
				if (($eventHasTickets) && ($event->ticket_event_id != 1))  // Ticket event has tickets set up and its not Test Event 1 (BUG - ppl can generate ticket events without a SID. @@FIX!)
				{
					$ticketUrl = "https://plugin.wireflydesign.com/ticket/index.php/ticket/book/" . $event->ticket_event_id . "?sid=" . $member->sid . "&ref=event";

if (isset($_GET['test']))
{
  $ticketUrl = "https://plugin.wireflydesign.com/ticket/index.php/ticket/book/" . $event->ticket_event_id . "?sid=" . $member->sid . "&ref=event" . "&ticket_event_id=" . $event->ticket_event_id;
}

					$content .= "<script>function goBook(where){window.open(where, '_blank');}</script>";
					$content .= "<div style='float:right'><a target='_blank' href='" . $ticketUrl . "'><img style='margin-top:0px; margin-left:0px' onClick=goBook('" . $ticketUrl . "') title='Click to book' src='img/book-s.jpg'></a></div><br/>";
				}
				else	// Ticket event hasn't tickets set up
				{
					$content .= "<div style='float:right'><a target='_blank' href='#'><img style='opacity:0.4; filter: alpha(opacity=40); margin-top:0px; margin-left:0px' title='Booking not yet available for this event.\nVendor has still to set up ticketing.\nPlease check back later.' src='img/book-s.jpg'></a></div><br/>";
				}
			}

			$content .= "    </div>";

			$content .= "    <div id='header-date'>";
			$content .= $this->formatDateString($event->start, $event->end);
			$content .= "    </div>";

			$content .= "    <div id='header-venue'>";	
			$content .= $event->address;
			$content .= "    </div>";

			$content .= "    <div id='header-icons' style='float:left; padding-left:7px;'>";

			// Twisty
			$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Show more' . "' src='img/" . 'open-twisty.png' . "' >";

			// Interest icons
			$criteria = new CDbCriteria;
			$criteria->condition = 'event_event_id = ' . $event->id;
			$criteria->order = 'event_interest_id ASC';
			$eventHasInterests = EventHasInterest::model()->findAll($criteria);
			foreach ($eventHasInterests as $eventHasInterest)
			{
				// Pick up the Icon
				$criteria = new CDbCriteria;
				$criteria->condition = 'id = ' . $eventHasInterest->event_interest_id;
				$interest = Interest::model()->find($criteria);
				{
					if ($interest)
						$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . $interest->name . "' src='userdata/icon/" . $interest->icon_path . "' width='20' height='20'>";
				}
			}

			// Facility icons
			$criteria = new CDbCriteria;
			$criteria->condition = 'event_event_id = ' . $event->id;
			$criteria->order = 'event_facility_id ASC';
			$eventHasFacilities = EventHasFacility::model()->findAll($criteria);
			foreach ($eventHasFacilities as $eventHasFacility)
			{
				// Pick up the Icon
				$criteria = new CDbCriteria;
				$criteria->condition = 'id = ' . $eventHasFacility->event_facility_id;
				$facility = Facility::model()->find($criteria);
				{
					if ($facility)
						$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . $facility->name . "' src='userdata/icon/" . $facility->icon_path . "' width='20' height='20'>";
				}
			}

			// @@TODO: These are hardcoded. Neednt be anymore now that theres 1 icon per band
			// Price Band icons
			if ($event->event_price_band_id == 1)	// Free
				$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Free' . "' src='userdata/icon/" . 'Free x20.png' . "' width='20' height='20'>";
			else
			{
				// 1st Price
				if ($event->event_price_band_id == 2)	// 1st price
					$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Under £5' . "' src='userdata/icon/" . 'Pound x20.png' . "' width='20' height='20'>";
				if ($event->event_price_band_id == 3)	// 2nd price
				{
					$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . '£5 - £10' . "' src='userdata/icon/" . '2pound.png' . "' width='20' height='20'>";
				}
				if ($event->event_price_band_id == 4)	// 3rd price
				{
					$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Over £10' . "' src='userdata/icon/" . '3pound.png' . "' width='20' height='20'>";
				}
			}

			$content .= "    </div>";
			$content .= "  </div>	<!-- /float left -->";


			$content .= "  <div style=float:right;width:120px;height:100%>";
			if (trim($event->thumb_path) != '')
			{
				if (file_exists('userdata/event/thumb/' . $event->thumb_path))
					$content .= "<img style='margin-top:-10px; margin-left:-20px' title='" . $event->thumb_path . "' src='userdata/event/thumb/" . $event->thumb_path . "' width='140' height='115'>";
			}
			else if ($program)
				$content .= "<img style='margin-top:-10px; margin-left:-20px' title='" . $program->thumb_path . "' src='userdata/program/thumb/" . $program->thumb_path . "' width='140' height='115'>";
			$content .= "  </div>";

			$content .= "</div> <!-- /header -->";

			// The content block
			$content .= "<div>";
			//$content .= $event->id;
			$content .= "</div>";
		}
		$content .= "</div>";	// 'accordion'


/**********/
		$content .= "<script>";
		$content .= "var mapMarkers = new Array();";
		$content .= "var mapMarkersP = new Array();";
		//$content .= "var mapMarkers = new array();";
$aa="";
		for ($i = 0; $i < count($this->mapPoint); $i++)
		{
			// WS
			if ($this->mapPointProgramId[$i] == 6)
			{
/**/
				if (strlen($this->mapPoint[$i]) < 8)
					continue;
				$content .= "markerByOs('" . $this->mapPoint[$i] . "', '" . $this->mapIcon[$i] . "', '" . urlencode($this->mapTip[$i]) . "', '"    . urlencode($this->mapContent[$i])     . "');";
				$content .= "mapMarkers[mapMarkers.length] = '" . $this->mapPoint[$i] . "';";
/**/
			}
			else	// Non-WS
			{
				// GOOGLE MAPS POSTCODE TO LATLNG
				$address = $this->mapPoint[$i];
				$coords = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true');
				$coords = json_decode($coords);
				$lat = $coords->results[0]->geometry->location->lat;
				$lng = $coords->results[0]->geometry->location->lng;
//if ($this->mapPoint[$i] != "EH3 6AQ.")
// continue;
				$content .= "markerByLatLongBigMap('" . $lat . "', '" . $lng . "', '" . urlencode($this->mapTip[$i]) . "');";
				$content .= "mapMarkersP[mapMarkersP.length] = '" . $lat."~".$lng . "';";
$aa .= $this->mapPoint[$i] . " = " . $lat."~".$lng . "<br>";
			}
		}
//var_dump($this->mapPoint);
//echo "<br><br>";
//var_dump($this->mapPointProgramId);
		$content .= "markerBounds(mapMarkers, mapMarkersP);";
		$content .= "</script>";
/**********/

//echo $aa;die;
		// Terminate the generated js array
		if ($hasEvents)
			$jsEvents = substr($jsEvents, 0, -1) . "];";
		else
			$jsEvents .= "];";
	
		$apiHtml = <<<END_OF_API_HTML_fill_headers

			<div id="jelly-fill_headers-container">
				<link rel="stylesheet" href="<substitute-path>/eventcode.css" type="text/css">
				<substitute-data>
				<substitute-xcss>
			</div>

END_OF_API_HTML_fill_headers;

		$apiJs = <<<END_OF_API_JS_fill_headers

			<substitute-eventarray>

			$(function() {
			    $( "#accordion" ).accordion({
					//header: 'a' ,
					//header: '> div.wrap' ,
					heightStyle: 'content',
			        collapsible: true,
        			active: true,
        			beforeActivate: function (event, ui) {
						if (ui.newHeader[0]) {
							ajaxGetEvent(ui.newPanel[0].id);
						}
			        }
			    });
			});

			// Ajax call to get the event details when a header is clicked
			var ajaxGetEvent = function(paneId) {
				var ev = paneId.split("-");
				var evIx = ev[ev.length - 1];
				evId = jsEvents[evIx];
//alert('paneId='+paneId+ 'evId='+evId);
				jQuery.ajax({'url':'<substitute-ajaxurl>','data':{'paneId':paneId, 'eventId':evId},'type':'POST','dataType':'json','success':function(val){ajaxShowEvent(val);},'cache':false});
			};

			// Return from Ajax call with event details
			var ajaxShowEvent = function(val) {
				//alert('Server sent ' + val.paneId + ' and ' + val.eventId);
				$('#' + val.paneId).html(val.content);
				FB.XFBML.parse();
			}

			$( document ).ready(function() {
				ajaxGetEvent(0);	// Dummy first ajax to 'initialise' the google map (who knows why its needed...)
			});

			function printSelectedHeads()
			{
				content = '<style> body { font-family: Sans-Serif; } #header-date {margin-top:40px;}</style>';
				content += '<body>';

				// For each
			for (i = 0; i < jsEvents.length; i++)
			{
				header = "";
				header += "<div style='height:100px;width:700px'>";
				header += document.getElementById('hdr-'+jsEvents[i]).innerHTML;
				header += "</div>";
				content += header;
content += "<div style='clear:both'></div>";
				content +=  "<hr>";
				content +=  "<br>";
				content +=  "<br>";
			}
				content += "</body>";
			
			
				var WinPrint = window.open('', '', 'letf=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
				WinPrint.document.write(content);
				WinPrint.document.close();
				WinPrint.focus();
				WinPrint.print();
				WinPrint.close();
			}


			function printDiv(eventId) {
				header = '<body>' + document.getElementById('hdr-'+eventId).innerHTML;
				css = '<link rel="stylesheet" href="<substitute-path>/eventcode.css" type="text/css">';
header += '<style> body { font-family: Sans-Serif; } #header-date {margin-top:40px;}</style>';


				separator = '<div style="clear:both"></div>';

				pShortDesc = document.getElementById('pShortDesc-'+eventId).innerHTML;
				pMap = document.getElementById('detailMap_'+eventId+'-map').innerHTML;	// Not currently used
				pDetails = document.getElementById('pDetails-'+eventId).innerHTML;
				pDesc = document.getElementById('pDesc-'+eventId).innerHTML;

				headerSeparator = "<div><br><br><br><br><br><br><br></div>";

				content = header + headerSeparator + pShortDesc + "<hr>" + pDetails + "<hr>" + pDesc;
content += '</body>';
				//content = document.getElementById('main_google_map-map').innerHTML;			// WORKS!
				//content = document.getElementById('detailMap_'+eventId+'-map').innerHTML;		// WORKS!

/***** start testing ****

content = document.getElementById('accordion').innerHTML;
content = "";
for (i = 0; i < jsEvents.length; i++)
{
	var evId = jsEvents[i];
	var header = document.getElementById('hdr-'+evId).innerHTML;
	content += header;
	content += headerSeparator;
}

***** end testing ******/

				var WinPrint = window.open('', '', 'letf=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
				WinPrint.document.write(content);
				WinPrint.document.close();
				WinPrint.focus();
				WinPrint.print();
				WinPrint.close();
			}

END_OF_API_JS_fill_headers;

		// Handle 
		$xcss = "";
		// The color of the header (WS is green, Ab wants white)
		if ($this->programId == 12)	// Absolute classics
		{
			$xcss .= "<style> #accordion .ui-accordion-header { border: 1px solid #a9b68b; background-color: #ffffff;} </style>";
		}
		if ((Yii::app()->session['headercolor']) && (Yii::app()->session['headercolor'] != ""))
		{
			$xcss .= "<style> #accordion .ui-accordion-header { border: 1px solid #a9b68b; background-color: #" . Yii::app()->session['headercolor'] . ";} </style>";
		}

		$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
        $apiHtml = str_replace("<substitute-data>", $content, $apiHtml);
        $apiHtml = str_replace("<substitute-xcss>", $xcss, $apiHtml);

		$apiJs = str_replace("<substitute-path>", $this->jellyRootUrl, $apiJs);
		$apiJs = str_replace("<substitute-eventarray>", $jsEvents, $apiJs);
		if (gethostname() == 'spleen')
			$apiJs = str_replace("<substitute-ajaxurl>", 'http://localhost/event/index.php/site/ajaxGetEvent', $apiJs);
		else
			$apiJs = str_replace("<substitute-ajaxurl>", 'http://plugin.wireflydesign.com/event/index.php/site/ajaxGetEvent', $apiJs);

		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	function formatDateString($startDate, $endDate)
	{
			//$content .= '['.$event->start . "]" . '['.$event->end . "]<br>";
			$start = strtotime($startDate);
			$end = strtotime($endDate);
			$dateString = date( 'l d/m/y', $start);
			if (date('H:i', $start) != '00:00')
				$dateString .= " " . date('H:i', $start);
			if ($endDate != $startDate)
			{
				$dateString .= " until ";
				if (date( 'l d/m/y', $start) != date( 'l d/m/y', $end))
					$dateString .= date( 'l d/m/y', $end);
				if (date('H:i', $end) != '00:00')
					$dateString .= " " . date('H:i', $end);
			}
			return $dateString;
	}

}

?>
