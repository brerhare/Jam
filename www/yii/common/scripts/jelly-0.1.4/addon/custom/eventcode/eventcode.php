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
				case "checkout":
					return $this->checkout($val);
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/*********************************************************************************************************/
	// Invoked by product.jel to show the product price options
	private function fill_headers($val)
	{
		$content = "";
		$jsEvents = "var jsEvents=[";

		$content .= "<div id='accordion'>";

		// Check date (dd-mm-yyyy)
		if (isset($_GET['date']))
			$dt = $_GET['date'];
		else
			$dt = $myDate = date('d-m-Y');
		$date = date("Y-m-d H:i:s", strtotime($dt));

		$criteria = new CDbCriteria;
		$criteria->condition = "start >= '" . $date . "'";
		$criteria->order = 'start ASC';
		$events = Event::model()->findAll($criteria);
		foreach ($events as $event)
		{
			$jsEvents .= '"' . $event->id . '",';
			// Pick up the member
			$criteria = new CDbCriteria;
			$criteria->condition = "id = " . $event->member_id;
			$member = Member::model()->find($criteria);

			// Pick up the program
			$criteria = new CDbCriteria;
			$criteria->condition = 'id = ' . $event->program_id;
			$program = Program::model()->find($criteria);

			// The header block
			$content .= "<div> <!-- header -->";

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

			$content .= "    <div id='header-icons' style=float:left>";	
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
					$content .= "<img style='margin-top:0px; margin-left:0px' title='Organisation' src='userdata/member/avatar/" . $member->avatar_path . "' width='20' height='20'>";

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
		$jsEvents = substr($jsEvents, 0, -1) . "];";

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
