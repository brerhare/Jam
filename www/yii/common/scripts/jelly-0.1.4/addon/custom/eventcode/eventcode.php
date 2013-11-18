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

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "fill_headers":
					return $this->fill_headers($val);
					break;
				default:
					break;
			}
		}
		return array("","");
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
		if ((isset($_GET['date'])) && trim($_GET['date'] != ''))
			$dt = $_GET['date'];
		else
			$dt = $myDate = date('d-m-Y');
		$date = date("Y-m-d H:i:s", strtotime($dt));

		$criteria = new CDbCriteria;
		$criteria->condition = "start >= '" . $date . "'";
		$criteria->order = 'start ASC';
		$events = Event::model()->findAll($criteria);
		$hasEvents = false;
		foreach ($events as $event)
		{
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

			// Check Price band filter
			if ((isset($_GET['pb'])) && trim($_GET['pb'] != ''))
			{
				$arr = explode('|', $_GET['pb']);
				if (!(in_array($event->event_price_band_id, $arr)))
					continue;
			}

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

			$hasEvents = true;

			// The header block
			$content .= "<div id='hdr'> <!-- header -->";

			$content .= "  <div style=float:left>";

			$content .= "    <div id='header-title'>";
/*
Moved Tom Henry's icon to the end of the regular icons on the bottom line
			if ($member)
			{
				if (trim($member->avatar_path) != '')
					$content .= "<img style='padding-right:5px;margin-top:0px; margin-right:0px' title='Member Avatar' src='userdata/member/avatar/" . $member->avatar_path . "' width='20' height='20'>";

			}
*/
			$content .= $event->title;

			// Ticketing info (if applicable)
			if (($event->ticket_event_id != 0) && ($member))
			{
				$ticketUrl = "https://plugin.wireflydesign.com/ticket/index.php/ticket/book/" . $event->ticket_event_id . "?sid=" . $member->sid . "&ref=event";
				$content .= "<script>function goBook(where){window.open(where, '_blank');}</script>";
				$content .= "<div style='float:right'><a target='_blank' href='" . $ticketUrl . "'><img style='margin-top:0px; margin-left:0px' onClick=goBook('" . $ticketUrl . "') title='Book' src='img/book-s.jpg'></a></div><br/>";
			}

			$content .= "    </div>";

			$content .= "    <div id='header-date'>";	
			//$content .= '['.$event->start . "]" . '['.$event->end . "]<br>";
			$start = strtotime($event->start);
			$end = strtotime($event->end);
			$dateString = date( 'l d/m/y', $start);
			if (date('H:i', $start) != '00:00')
				$dateString .= " " . date('H:i', $start);
			if ($event->end != $event->start)
			{
				$dateString .= " until ";
				if (date( 'l d/m/y', $start) != date( 'l d/m/y', $end))
					$dateString .= date( 'l d/m/y', $end);
				if (date('H:i', $end) != '00:00')
					$dateString .= " " . date('H:i', $end);
			}
			$content .= $dateString;
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

			// Member avatar (aka Organisation icon)
			if ($member)
			{
				if (trim($member->avatar_path) != '')
					$content .= "<img style='margin-top:0px; margin-left:0px' title='" . $member->organisation . "' src='userdata/member/avatar/" . $member->avatar_path . "' width='20' height='20'>";

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
		$content .= "</div>";

		// Terminate the generated js array
		if ($hasEvents)
			$jsEvents = substr($jsEvents, 0, -1) . "];";
		else
			$jsEvents .= "];";
	
		$apiHtml = <<<END_OF_API_HTML_fill_headers

			<div id="jelly-fill_headers-container">
				<link rel="stylesheet" href="<substitute-path>/eventcode.css" type="text/css">
				<substitute-data>
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

END_OF_API_JS_fill_headers;

		$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);
        $apiHtml = str_replace("<substitute-data>", $content, $apiHtml);

		$apiJs = str_replace("<substitute-eventarray>", $jsEvents, $apiJs);
		if (gethostname() == 'spleen')
			$apiJs = str_replace("<substitute-ajaxurl>", 'http://localhost/event/index.php/site/ajaxGetEvent', $apiJs);
		else
			$apiJs = str_replace("<substitute-ajaxurl>", 'https://plugin.wireflydesign.com/event/index.php/site/ajaxGetEvent', $apiJs);

		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

}

?>
