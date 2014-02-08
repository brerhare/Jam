<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for BEIRC custom code
 *
 * Notes
 * -----
 * None
 */

class beirccode
{
	// Globals
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

		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "run":
					if ($val == 'login')
						return $this->login();
					if ($val == 'calendar')
						return $this->calendar();
					break;
				default:
					break;
			}
		}
		return array("","");
	}

	/*********************************************************************************************************/

	private function login()
	{
		$content = "";
		$content .= "<h2>Arena " . $_GET['arena'] . "</h2>";
		$content .= "<table><tr><td colspan=2>";
		$content .= "<a style='font-weight:bold;color:#017572'> Members please login to make bookings</a><br>";
		$content .= "</td></tr>";
		$content .= "<tr><td>User name</td><td>";
		$content .= "<input id='username' name='username' type='text' value='' size='20'/> <br/>";
		$content .= "</td></tr><tr><td>Member number</td><td>";
		$content .= "<input id='password' name='password' type='password' value='' size='5'/> <br />";		
		$content .= "</td></tr>";
		$content .= "<tr><td id='loggedinprompt'>&nbsp</td><td>";
		$content .= "<input type='button' id='loginbutton' style='float:left;padding:3px; width:60px' onClick='doLogin()' value='Login'>";
		$content .= "</td></tr></table>";

		$clipBoard = "";
		$apiHtml = $content;

		$apiJs = <<<END_OF_API_JS_login

			var loggedIn = 0;

			var memberId = 0;
			var memberType = 0;
			var memberPassword = "";
			var memberDisplayName = "";
			var arena = 0;

			$( document ).ready(function() {
				checkLogin();
			});

			function checkLogin()	// Are we already logged in?
			{
				var username = '?';
				var password = '?';
				var loggedIn = '?';	// NB this is NOT the global
				<substitute-ajax-login-code>
			}

			function doLogin()
			{
				var username = document.getElementById('username').value;
				var password = document.getElementById('password').value;
				//arena = getArgValue('arena');
				<substitute-ajax-login-code>
			}

			// @@EG: JS retrieve page arg
			// Return the GET[''] value of something
			function getArgValue(name)
			{
				if (name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
					return decodeURIComponent(name[1]);
				return("");
			}

			function ajaxShowLogin(val)
			{
				if (val.error != "")
				{
					// Error
					loggedIn = 0;
					alert(val.error);
					return;
				}
				if (val.loggedOut != "")
				{
					// Just logged out
					loggedIn = 0;
					document.getElementById("loggedinprompt").innerHTML="";
					document.getElementById("loginbutton").value="Login";
					return;
				}
				// Success
				loggedIn = 1;
				//alert('Welcome ' + val.displayName);
				document.getElementById("loggedinprompt").innerHTML="Logged in";
				document.getElementById("loginbutton").value="Logout";
				// Show the login stuff in case this was a query
				document.getElementById("username").value=val.userName;
				document.getElementById("password").value=val.password;

				memberId = val.id;
				memberPassword = val.password;
				memberDisplayName = val.displayName;
				memberType = val.memberType;
			}

END_OF_API_JS_login;

		// Substitutes
		$ajaxLoginString = CHtml::ajax(array(
   			'url'=>Yii::app()->createUrl('site/ajaxLogin'),
   			'data'=>array(
      			'loggedIn'=>'js:loggedIn',
      			'username'=>'js:username',
       			'password'=>'js:password',
     			),
   			'type'=>'POST',
   			'dataType'=>'json',
   			'success' => 'function(val){ajaxShowLogin(val);}',
		));
		$apiJs = str_replace("<substitute-ajax-login-code>", $ajaxLoginString, $apiJs);

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

	private function calendar()
	{
 		$apiHtml = <<<END_OF_API_HTML

			<div id="jelly-beirc-fullcalendar-container">

				<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js" ></script>
<!--				<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/cupertino/jquery-ui.css" type="text/css" rel="stylesheet" /> -->
<!--				<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/pepper-grinder/jquery-ui.css" type="text/css" rel="stylesheet" /> -->
<!--				<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/redmond/jquery-ui.css" type="text/css" rel="stylesheet" /> -->
<!--				<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/sunny/jquery-ui.css" type="text/css" rel="stylesheet" /> -->
				<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/start/jquery-ui.css" type="text/css" rel="stylesheet" />

				<!-- Fullcalendar -->
				<link rel="stylesheet" href="<substitute-path>/fullcalendar/fullcalendar.css" type="text/css" media="screen"/>
				<!-- <link rel="stylesheet" href="<substitute-path>/fullcalendar/fullcalendar.print.css" type="text/css" media="print"/> -->
				<script src="<substitute-path>/fullcalendar/fullcalendar.js"></script>

				<!-- Timepicker -->
				<script type="text/javascript" src="<substitute-path>/timepicker/jquery.timepicker.js"></script>
<!--			<link rel="stylesheet" type="text/css" href="<substitute-path>/timepicker/jquery.timepicker.css" /> -->
				<script type="text/javascript" src="<substitute-path>/timepicker/lib/base.js"></script>

				<!-- Style overrides -->
				<style>
					/* Suppress time display on week and day views */
					.fc-view-agendaWeek .fc-event-time{ display : none; }
					.fc-view-agendaDay .fc-event-time{ display : none; }

					/* Set the height of each hourly slot 'cell' (month/week/day) */
    				.fc-agenda-slots td div { height: 26px !important; }

				</style>


				<!-- Calendar container -->
				<div style='/*border:1px solid #cfc497;*/ width:815px' id="mycalendar"></div> <br><br>

				<!-- Edit dialog container -->
				<div id="dialog" style="display:none;z-index:12000;/*border:1px solid #e2f0f8*/" title="Event">
					<input type="hidden" name="editDate" id="editDate">
					<input type="hidden" name="editEventId" id="editEventId">
					<table>
<!--
						<tr>
							<td>&nbsp</td>
							<td><span id="editDate" style="color:#47a4cb" name="editDate" value=""></span></td>
						</tr>
-->
						<tr>
							<td> <label for="start">Start time</label> </td>
							<td> <select id="editStart" name="editStart">
									<option value="7">7am</option>
									<option value="8">8am</option>
									<option value="9">9am</option>
									<option value="10">10am</option>
									<option value="11">11am</option>
									<option value="12">12pm</option>
									<option value="13">1pm</option>
									<option value="14">2pm</option>
									<option value="15">3pm</option>
									<option value="16">4pm</option>
									<option value="17">5pm</option>
									<option value="18">6pm</option>
									<option value="19">7pm</option>
									<option value="20">8pm</option>
								</select>
						</tr>
						<tr>
							<td> <label for="end">End time</label> </td>
							<td> <select id="editEnd" name="editEnd">
									<option value="8">8am</option>
									<option value="9">9am</option>
									<option value="10">10am</option>
									<option value="11">11am</option>
									<option value="12">12pm</option>
									<option value="13">1pm</option>
									<option value="14">2pm</option>
									<option value="15">3pm</option>
									<option value="16">4pm</option>
									<option value="17">5pm</option>
									<option value="18">6pm</option>
									<option value="19">7pm</option>
									<option value="20">8pm</option>
									<option value="21">9pm</option>
								</select>
						</tr>
						<tr>
							<td> <label for="description">Description</label> </td>
							<td> <input type="text" style="width:180px" name="editDescription" id="editDescription" class="text ui-widget-content ui-corner-all"> </td>
						</tr>
							<td> <label for="share">Share?</label> </td>
							<td> <select id="editShare" name="editShare">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
						</tr>
						<tr>
							<td> <label for="confirmed">Confirmed?</label> </td>
							<td> <select id="editConfirmed" name="editConfirmed">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
						</tr>
					</table>
<hr/>
					<table>
						<tr>
							<td width="20%">
							<td width="20%"> <input type='button' id='editSave' style='float:left;padding:3px; width:60px' onClick='saveDialog("save")' value='Save'> </td>
							<td width="20%"> <input type='button' id='editDelete' style='float:left;padding:3px; width:60px' onClick='saveDialog("delete")' value='Delete'> </td>
							<td width="20%"> <input type='button' id='editCancel' style='float:left;padding:3px; width:60px' onClick='cancelDialog()' value='Cancel'> </td>
							<td width="20%">
						</tr>
					</table>
				</div>

				<!-- Message dialog container -->
				<div id="dialogMsg" style="display:none;z-index:12000;/*border:1px solid #e2f0f8*/" title="Message">
					<br>
					<span id="msgText"></span>
				</div>

    		</div>

END_OF_API_HTML;

		$apiJs = <<<END_OF_API_JS_calendar

		function saveDialog(saveType)	// 'save' or 'delete'
		{
			var start = document.getElementById('editStart').value;
			var end = document.getElementById('editEnd').value;
			var date = document.getElementById('editDate').value;
			var eventId = document.getElementById('editEventId').value;
			var description = document.getElementById('editDescription').value;
			var share = document.getElementById('editShare').value;
			var confirmed = document.getElementById('editConfirmed').value;
			var action = saveType;
			$( "#dialog" ).dialog('close');
			<substitute-ajax-event-code>
		}

		function ajaxShowEvent(val)
		{
			if (val.error != "")
			{
				// Error
				alert(val.error);
				return;
			}
			if ((val.action == 'edit') || (val.action == 'delete'))
			{
				$('#mycalendar').fullCalendar('removeEvents', val.event_id);
			}
			if ((val.action == "insert") || (val.action == 'edit'))
			{
				$('#mycalendar').fullCalendar('renderEvent', {
					id: val.event_id,
					title: val.title,
					description: val.description,
					member_id: val.member_id,
					event_id: val.event_id,
					arena: val.arena,
					start: val.start,
					end: val.end,
					share: val.share,
					confirmed: val.confirmed,
					password: val.password,
					allDay: false,
					}, true );
			}
		}

		function cancelDialog()
		{
			$( "#dialog" ).dialog('close');
		}

			$('#mycalendar').fullCalendar(
			{
				// Defaults
				editable: true,        
				disableDragging: true,	// No dragging events around
				disableResizing: true,	// Cant increase/decrease an event's duration 
				defaultView: 'agendaWeek',
				allDaySlot: false,
				minTime: 7,
				maxTime: 21,
				slotMinutes: 60,
				snapMinutes: 60,

				// Buttons etc
				header:
				{
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},

				// Date format
				columnFormat:
				{
					month: 'ddd',    // Mon
					week: 'ddd d/M', // Mon 9/7
					day: 'dddd d/M', // Monday 9/7
				},

				// Hover
				eventMouseover: function(calEvent, jsEvent) {
					var shared = 'Will share';
					if (calEvent.share == 0)
						shared = 'Wont share';
					var confirmed = 'Confirmed';
					if (calEvent.confirmed == 0)
						confirmed = 'Not confirmed';
    				var tooltip = '<div class="tooltipevent" style="border:1px solid black;padding:5px;background:#ccc;position:absolute;z-index:10001;">' + $.fullCalendar.formatDate(calEvent.start,'htt') + '-' + $.fullCalendar.formatDate(calEvent.end,'htt') + '<br>' + calEvent.title + '<br>' + calEvent.description + '<br>' + shared + '<br>' + confirmed + '<br></div>';
    				$("body").append(tooltip);
    				$(this).mouseover(function(e) {
        				$(this).css('z-index', 10000);
        				$('.tooltipevent').fadeIn('500');
        				$('.tooltipevent').fadeTo('10', 1.9);
    				}).mousemove(function(e) {
        				$('.tooltipevent').css('top', e.pageY + 25);
        				$('.tooltipevent').css('left', e.pageX + 20);
    				});
				},
				eventMouseout: function(calEvent, jsEvent) {
    				$(this).css('z-index', 8);
    				$('.tooltipevent').remove();
				},

				eventRender: function(event, element, view)
				{
					// This next line adds a second line (description) to each event display
					element.find('.fc-event-title').append("<br/>" + event.description);
					// Make each event a clicky
					element.bind('click', function()
					{
						if (loggedIn)
						{
							// Edit own events only
							if (event.member_id != memberId)
							{
								return;
							}
							var titleDate = $.fullCalendar.formatDate(event.start, "dddd d MMMM yyyy");
							var editDate = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
							var start = $.fullCalendar.formatDate(event.start, "H");
							var end = $.fullCalendar.formatDate(event.end, "H");
							$("#editDate").val(editDate);
							$("#editEventId").val(event.event_id);
							$("#editStart").val(start);
							$("#editEnd").val(end);
							$("#editDescription").val(event.description);
							$("#editShare").val(event.share);
							$("#editConfirmed").val(event.confirmed);
    						$("#dialog").dialog();
							$("#dialog").dialog('option', 'title', titleDate);
						}
						else
						{
							document.getElementById("msgText").innerHTML = "Please login to manage your bookings";
    						$("#dialogMsg").dialog();
						}
					});
				},

				dayClick: function(date, allDay, jsEvent, view)
				{
					document.getElementById("msgText").innerHTML = "";
					if (loggedIn)
					{
						var titleDate = $.fullCalendar.formatDate(date, "dddd d MMMM yyyy");
						var editDate = $.fullCalendar.formatDate(date, "yyyy-MM-dd HH:mm:ss");
						var checkDate = $.fullCalendar.formatDate(date, "yyyy-MM-dd");
						// Check the day isnt more than 2 weeks ahead
						if (memberType != 5)
						{
							var fortnightAway = new Date(+new Date + 12096e5);
							if (checkDate >= date2YMD(fortnightAway))
							{
								document.getElementById("msgText").innerHTML = "Cant book more than 14 days ahead";
   								$("#dialogMsg").dialog();
								return;
							}
						}
						// Check if theres already an event, and if so will they share the slot
						var dayEvents = $('#mycalendar').fullCalendar('clientEvents', function(event) {
							var dt = checkDate + ' 00:00:00';
							var start = $.fullCalendar.formatDate(date, "HH");
							var end = (parseInt(start) + 1);
							if (end < 10) end = "0" + end;
							var sdt = checkDate + ' ' + start + ':00:00';
							var edt = checkDate + ' ' + end + ':00:00';
							var estart = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
							var eend = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
//alert('estart:'+estart+' eend:'+eend+' sdt:'+sdt+' edt:'+edt);
							return sdt >= estart && edt <= eend;
						});
//alert('dayEvents='+dayEvents.length);
						if (dayEvents.length > 1)
						{
							document.getElementById("msgText").innerHTML = "This time slot has already been shared";
    						$("#dialogMsg").dialog();
							return;
						}
						if (dayEvents.length == 1)
						{
							if (parseInt(dayEvents[0].share) == 0)
							{
								document.getElementById("msgText").innerHTML = "Sorry, " + dayEvents[0].title + " is not sharing this slot";
    							$("#dialogMsg").dialog();
								return;
							}
						}
						// No-one has booked this slot
						var start = $.fullCalendar.formatDate(date, "H");
						var end = (parseInt(start) + 1);
						$("#editDate").val(editDate);
						$("#editEventId").val(0);
						$("#editStart").val(start);
						$("#editEnd").val(end);
						$("#editDescription").val("");
						$("#editShare").val("Yes");
						$("#editConfirmed").val("yes");
    					$("#dialog").dialog();
						$("#dialog").dialog('option', 'title', titleDate);
					}
					else
					{
						document.getElementById("msgText").innerHTML = "Please login to manage your bookings";
   						$("#dialogMsg").dialog();
					}
					//alert('Current view: ' + view.name);
					// change the day's background color just for fun
					//$(this).css('background-color', 'red');
				},

/***
				selectable: true,
				selectHelper: true,
//					when user select timeslot this option code will execute.
//					It has three arguments. Start,end and allDay.
//					Start means starting time of event.
//					End means ending time of event.
//					allDay means if events is for entire day or not.
				select: function(start, end, allDay)
				{
//						after selection user will be promted for enter title for event.
					alert(start + '<br>' + end);
					//var title = prompt('Event Title:');
		},
***/

				events:
				[
					<substitute-events>
				]
			}); 


	/* Convert a Date object to '2014-12-31' format */
    function date2YMD(date)
	{
        var year, month, day;
        year = String(date.getFullYear());
        month = String(date.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(date.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
        return year + "-" + month + "-" + day;
    }

END_OF_API_JS_calendar;

		// Substitute paths for includes
		$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);

		// Substitutes for ajax events
		$ajaxEventString = CHtml::ajax(array(
   			'url'=>Yii::app()->createUrl('site/ajaxEvent'),
   			'data'=>array(
      			'memberId'=>'js:memberId',
      			'memberPassword'=>'js:memberPassword',
       			'arena'=>'js:arena',
       			'date'=>'js:date',
       			'eventId'=>'js:eventId',
       			'start'=>'js:start',
       			'end'=>'js:end',
       			'description'=>'js:description',
       			'share'=>'js:share',
       			'confirmed'=>'js:confirmed',
       			'action'=>'js:action',
     			),
   			'type'=>'POST',
   			'dataType'=>'json',
   			'success' => 'function(val){ajaxShowEvent(val);}',
		));
		$apiJs = str_replace("<substitute-ajax-event-code>", $ajaxEventString, $apiJs);

		// Populate events
/*****
{
	title  : 'event1',
	description: 'hack',
	start  : '2014-01-23 07:00:00',
	end    : '2014-01-23 08:00:00',
	allDay : false,	// Will make the time show
},
*****/
		$criteria = new CDbCriteria;
		$criteria->addCondition("arena = " . $_GET['arena']);
		$events = Event::model()->findAll($criteria);
		$eventList = "";
		if ($events)
		{
			foreach ($events as $event)
			{
				$criteria = new CDbCriteria;
				$criteria->addCondition("password = " . $event->password);
				$member = Member::model()->find($criteria);
				if (!($member))
				{
					//die("Member " . $event->password . " not found for an event");
					continue;
				}
				$desc = str_replace('"', '', $event->description);
				$desc = str_replace("'", '', $desc);
				$eventList .= "{\n";
				$eventList .= "    title: '" . $member->displayname . "',\n";
				$eventList .= "    description: '" . $desc . "',\n";
				$eventList .= "    member_id: '" . $member->id . "',\n";
				$eventList .= "    event_id: '" . $event->id . "',\n";
				$eventList .= "    arena: '" . $event->arena . "',\n";
				$eventList .= "    start: '" . $event->start . "',\n";
				$eventList .= "    end: '" . $event->end . "',\n";
				$eventList .= "    share: '" . $event->share . "',\n";
				$eventList .= "    confirmed: '" . $event->confirmed . "',\n";
				$eventList .= "    password: '" . $event->password . "',\n";
				$eventList .= "    id: '" . $event->id . "',\n";
				$eventList .= "    allDay: false,\n";
				$eventList .= "},\n";
			}
		}
		$apiJs = str_replace("<substitute-events>", $eventList, $apiJs);

		$clipBoard = "";

		$retArr = array();
		$retArr[0] = $apiHtml;
		$retArr[1] = $apiJs;
		$retArr[2] = $clipBoard;
		return $retArr;
	}

/**********************************************************************************************/
/* Common functions */
/* ---------------- */


}

?>
