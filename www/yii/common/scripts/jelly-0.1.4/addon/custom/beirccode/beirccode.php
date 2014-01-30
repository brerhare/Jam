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
		if (trim(Yii::app()->session['beircid']) == "")
		{
			$content .= "<h2>Arena " . $_GET['arena'] . "</h2>";
			$content .= "<table><tr><td colspan=2>";
			$content .= "<a style='font-weight:bold;color:#017572'> Members please login to make bookings</a><br>";
			$content .= "</td></tr>";
			$content .= "<tr><td>User name</td><td>";
			$content .= "<input id='username' name='username' type='text' value='' size='20'/> <br/>";
			$content .= "</td></tr><tr><td>Member number</td><td>";
			$content .= "<input id='password' name='password' type='text' value='' size='5'/> <br />";		
			$content .= "</td></tr>";
			$content .= "<tr><td id='loggedinprompt'>&nbsp</td><td>";
			$content .= "<input type='button' id='loginbutton' style='float:left;padding:3px; width:60px' onClick='doLogin()' value='Login'>";
			$content .= "</td></tr></table>";
		}
		else
		{
			$content .= "<a style='font-weight:bold;color:#017572'> You are logged in</a><br>";
		}
		$clipBoard = "";
		$apiHtml = $content;

		$apiJs = <<<END_OF_API_JS_login

			var loggedIn = 0;

			function doLogin()
			{
				var username = document.getElementById('username').value;
				var password = document.getElementById('password').value;
				var arena = getArgValue('arena');
				<substitute-ajax-login-code>
			}

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
				alert('Welcome ' + val.displayName);
				document.getElementById("loggedinprompt").innerHTML="Logged in";
				document.getElementById("loginbutton").value="Logout";
			}

END_OF_API_JS_login;

		// Substitutes
		$xx = CHtml::ajax(array(
   			'url'=>Yii::app()->createUrl('site/ajaxLogin'),
   			'data'=>array(
      			'loggedIn'=>'js:loggedIn',
      			'username'=>'js:username',
       			'password'=>'js:password',
       			'arena'=>'js:arena',
     			),
   			'type'=>'POST',
   			'dataType'=>'json',
   			'success' => 'function(val){ajaxShowLogin(val);}',
		));
		$apiJs = str_replace("<substitute-ajax-login-code>", $xx, $apiJs);

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

				<link rel="stylesheet" href="<substitute-path>/fullcalendar/fullcalendar.css" type="text/css" media="screen"/>
				<!-- <link rel="stylesheet" href="<substitute-path>/fullcalendar/fullcalendar.print.css" type="text/css" media="print"/> -->
				<script src="<substitute-path>/fullcalendar/fullcalendar.js"></script>

				<!-- Style overrides -->
				<style>
					/* Suppress time display on week and day views */
					.fc-view-agendaWeek .fc-event-time{ display : none; }
					.fc-view-agendaDay .fc-event-time{ display : none; }

					/* Set the height of each hourly slot 'cell' (month/week/day) */
    				.fc-agenda-slots td div { height: 26px !important; }

				</style>


				<div style='/*border:1px solid #cfc497;*/ width:815px' id="mycalendar"></div> <br><br>
    		</div>

END_OF_API_HTML;

		$apiJs = <<<END_OF_API_JS_calendar

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
						var day = ($.fullCalendar.formatDate( event.start, 'dd' ));
						var month = ($.fullCalendar.formatDate( event.start, 'MM' ));
						var year = ($.fullCalendar.formatDate( event.start, 'yyyy' ));
						var title = event.title;
						alert('event ' + year+'-'+month+'-'+day + ' ' + title);
					});
				},

				dayClick: function(date, allDay, jsEvent, view)
				{
					alert('Clicked on the slot: ' + date + '. loggedIn='+loggedIn);
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

END_OF_API_JS_calendar;

		// Substitute paths for includes
		$apiHtml = str_replace("<substitute-path>", $this->jellyRootUrl, $apiHtml);

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
