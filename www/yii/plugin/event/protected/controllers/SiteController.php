<?php

class SiteController extends Controller
{

	/**
	 * Get the jelly script root (as defined in /protected/config/main.php)
	 */
	private function getJellyRoot()
	{
		return Yii::app()->params['jellyRoot'];
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// Store any passed customisation args
		Yii::app()->session['headercolor'] == "";
		Yii::app()->session['map'] == "";
		if (isset($_GET['headercolor']))
			Yii::app()->session['headercolor'] = $_GET['headercolor'];
		if (isset($_GET['map']))
			Yii::app()->session['map'] = $_GET['map'];

		$layout = "index";
		if (isset($_GET['layout']))
			$layout = $_GET['layout'];
		$parseConfig = new ParseConfig();
		$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . $layout . ".jel");
		if (!($jellyArray))
			throw new Exception('Aborting');

		$jelly = new Jelly;
		$jelly->processData($jellyArray,$this->getJellyRoot());
		$jelly->outputData();

		//$this->render('index');
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionPlay($page)
	{
		$parseConfig = new ParseConfig();
		$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . $page . '.jel');
		if (!($jellyArray))
			throw new Exception('Aborting');

		$jelly = new Jelly;
		$jelly->processData($jellyArray,$this->getJellyRoot());
		$jelly->outputData();

	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionAjaxGetEvent()
    {
		if (Yii::app()->request->isAjaxRequest)
		{
			require(Yii::app()->basePath . "/../scripts/jelly/addon/map/google_os/google_os.php");

			$paneId = $_POST['paneId'];
			$eventId = $_POST['eventId'];
			Yii::log("AJAX CALL: paneId:" . $paneId . " and eventId:" . $eventId, CLogger::LEVEL_WARNING, 'system.test.kim');

			$content = "";

			$criteria = new CDbCriteria;
			$criteria->condition = 'id = ' . $eventId;
			$event = Event::model()->find($criteria);
			if ($event)
			{
				Yii::log("AJAX CALL: program_id:" . $event->program_id, CLogger::LEVEL_WARNING, 'system.test.kim');
				if ($event->program_id == 6)	// WS Wild Seasons
				{
					$criteria = new CDbCriteria;
					$criteria->condition = 'event_id = ' . $eventId;
					$ws = Ws::model()->find($criteria);
					if ($ws)
					{
						$content .= "<div id='pShortDesc-" . $eventId. "'>" . $ws->short_description . "</div>";		// Printing start and end
						$content .= "<table width=100% style='padding:10px 10px 10px 0px'><tr><td style='padding:0px; width:40%'>";
	
						// @@EG: Calling a jelly addon directly, from outside the jelly

						if (trim($ws->os_grid_ref) != "")
						{
// GOOGLE MAPS OSGRIDREF TO LATLNG - uses LEAFLET (called by markerByOs2)
							$addon = new google_os;
							$optArr = array();
							$optArr['single'] = '1';
							$optArr['id'] = 'detailMap_' . $eventId;
							$optArr['width'] = '200px';
							$optArr['height'] = '200px';
							//$optArr['maptype'] = 'terrain';
							$optArr['inputmode'] = 'os';
							$optArr['center'] = $ws->os_grid_ref;
							$optArr['zoom'] = '9';
							$ret = $addon->init($optArr, '/event/scripts/jelly/addon/map/google_os');
							$content .= $ret[0];
							$content .= '<script>' . $ret[1] . '</script>';
							$content .= "<script> markerByOs2('" . $ws->os_grid_ref . "', '" . $event->post_code . "'); </script>";
						}
						else
						{
// GOOGLE MAPS POSTCODE TO LATLNG - uses LEAFLET
$address = $event->post_code;
$coords = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true');
$coords = json_decode($coords);
$lat = $coords->results[0]->geometry->location->lat;
$lng = $coords->results[0]->geometry->location->lng;

							$addon = new google_os;
							$optArr = array();
							$optArr['single'] = '1';
							$optArr['id'] = 'detailMap_' . $eventId;
							$optArr['width'] = '200px';
							$optArr['height'] = '200px';
							$optArr['lat'] = $lat;
							$optArr['lng'] = $lng;
							//$optArr['maptype'] = 'terrain';
							$optArr['inputmode'] = 'latlong';
							$optArr['center'] = $lat . "," . $lng;
							$optArr['zoom'] = '9';
							$ret = $addon->init($optArr, '/event/scripts/jelly/addon/map/google_os');
							$content .= $ret[0];
							$content .= '<script>' . $ret[1] . '</script>';
							$content .= "<script> markerByLatLong('" . $lat . "', '" . $lng . "', '" . $event->post_code . "'); </script>";
						}

						$content .= "</td> <div id='" . $optArr['id'] . "'></div> <td style='width:60%; padding-left:10px; vertical-align:top'>";

						$content .= "<div id='pDetails-" . $eventId. "'>";		// Printing start

						// Booking
						$content .= "<a class='event-detail-label'>Booking</a> ";
						if ($ws->booking_essential)
							$content .= "<b>essential</b>";
						else
							$content .= "not essential";
						// Pick up the member record
						$criteria = new CDbCriteria;
						$criteria->condition = 'id = ' . $event->member_id;
						$member = Member::model()->find($criteria);

						$content .= "<br/>";
						// Organisation
						if ($member)
							if (trim($member->organisation) != '')
								$content .= "<a class='event-detail-label'>Organisation</a> " . $member->organisation . "<br>";
						// Contact details
						$content .= "<a class='event-detail-label'>Contact details</a> " . $event->contact . "<br>";
						// Website
						if (trim($event->web) != '')
						{
							$http = "http://";
							if (strstr("http://", $event->web))
								$http = "";
							$content .= "<a class='event-detail-label'>Website</a> " . "<a href='" . $http . $event->web . "'' target='_blank'>" . $event->web . "</a>" . "<br>";
						}
						// Suitable ages
						if (($ws->min_age == 0) && ($ws->max_age == 0))
							$content .= "<a class='event-detail-label'>Suitable for</a> all ages" . "<br>";
						else if (($ws->min_age != 0) && ($ws->max_age != 0))
							$content .= "<a class='event-detail-label'>Suitable for</a> ages " . $ws->min_age . " to " . $ws->max_age . "<br>";
						else if (($ws->min_age != 0) && ($ws->max_age == 0))
							$content .= "<a class='event-detail-label'>Suitable for</a> ages " . $ws->min_age . " and older" . "<br>";
						else if (($ws->min_age == 0) && ($ws->max_age != 0))
							$content .= "<a class='event-detail-label'>Suitable for</a> ages up to " . $ws->max_age . "<br>";
						// Grade
						$content .= "<a class='event-detail-label'>Grade</a> " . $ws->grade . "<br>";
						// OS Grid Ref
						$content .= "<a class='event-detail-label'>OS grid ref</a> " . $ws->os_grid_ref . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(" . $event->post_code . ")" . "<br>";
	
						$content .= "</div>";	// pDetails.	Printing end
	
						$content .= "</td></tr></table>";
	
						$content .= "<div id='pDesc-" . $eventId. "'>";		// Printing start
	
						// Description
						$content .= $event->description . "<br>";
						// Additional Venue Info
						if (trim($ws->additional_venue_info) != '')
							$content .= "Additional venue info: " . $ws->additional_venue_info . "<br>";
						if (trim($ws->full_price_notes) != '')
							$content .= "Full price notes: " . $ws->full_price_notes . "<br>";

						$content .= "</div>";	// pDetails.	Printing end
	
						// Facebook
						$content .= "<div style='float:right;padding-left:10px;padding-top:1px' class='fb-share-button' data-href='http://www.wildseasons.co.uk' data-type='button'></div>";
	
						// print
						$content .= "<div style='float:right;padding-left:10px'><a href=javascript:printDiv('" . $eventId . "')><img style='margin-top:0px; margin-left:0px' title='Print' src='img/print.jpg'></a></div>";
	
						// Ticketing info (if applicable)
						if (($event->ticket_event_id != 0) && ($member))
						{
							$ticketUrl = "https://plugin.wireflydesign.com/ticket/index.php/ticket/book/" . $event->ticket_event_id . "?sid=" . $member->sid . "&ref=event";
							$content .= "<div style='float:right'><a target='_blank' href='" . $ticketUrl . "'><img style='margin-top:0px; margin-left:0px' title='Book' src='img/book-s.jpg'></a></div><br/>";
							$content .= "<div style='clear:both'></div>";
						}
						$content .= "<br><br>";
					}
					else 
						$content .= "No Ws record";
				}

				else	// Standard fields only
				{
					$content .= "<table width=100% style='padding:10px 10px 10px 0px'><tr><td style='padding:0px; width:40%'>";

// GOOGLE MAPS POSTCODE TO LATLNG
$address = $event->post_code;
$coords = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true');
$coords = json_decode($coords);
$lat = $coords->results[0]->geometry->location->lat;
$lng = $coords->results[0]->geometry->location->lng;

// LEAFLET
//$content .= '<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />';
//$content .= '<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>';
					// @@EG: Calling a jelly addon directly, from outside the jelly
					$addon = new google_os;
					$optArr = array();
					$optArr['single'] = '1';
					$optArr['id'] = 'detailMap_' . $eventId;
					$optArr['width'] = '200px';
					$optArr['height'] = '200px';
					$optArr['lat'] = $lat;
					$optArr['lng'] = $lng;
					//$optArr['maptype'] = 'terrain';
					$optArr['inputmode'] = 'latlong';
					$optArr['center'] = $lat . "," . $lng;
					$optArr['zoom'] = '9';
//die('x='.$event->program_id);
					$ret = $addon->init($optArr, '/event/scripts/jelly/addon/map/google_os');
					$content .= $ret[0];
					$content .= '<script>' . $ret[1] . '</script>';
					$content .= "<script> markerByLatLong('" . $lat . "', '" . $lng . "', '" . $event->post_code . "'); </script>";
					$content .= "</td> <div id='" . 'detailMap_' . $eventId . "'></div> <td style='width:60%; padding-left:10px; vertical-align:top'>";

					$content .= "<div id='pDetails-" . $eventId. "'>";		// Printing start

					// Pick up the member record
					$criteria = new CDbCriteria;
					$criteria->condition = 'id = ' . $event->member_id;
					$member = Member::model()->find($criteria);

					$content .= "<br/>";
					// Organisation
					if ($member)
						if (trim($member->organisation) != '')
							$content .= "<a class='event-detail-label'>Organisation</a> " . $member->organisation . "<br>";
					// Contact details
					$content .= "<a class='event-detail-label'>Contact details</a> " . $event->contact . "<br>";
					// Website
					if (trim($event->web) != '')
					{
						$http = "http://";
						if (strstr("http://", $event->web))
							$http = "";
						$content .= "<a class='event-detail-label'>Website</a> " . "<a href='" . $http . $event->web . "'' target='_blank'>" . $event->web . "</a>" . "<br>";
					}
	
					$content .= "</td></tr></table>";
	
					$content .= "<div id='pDesc-" . $eventId. "'>";		// Printing start
	
					// Description
					$content .= $event->description . "<br>";

					$content .= "</div>";	// pDetails.	Printing end
	
					// Facebook
					$content .= "<div style='float:right;padding-left:10px;padding-top:1px' class='fb-share-button' data-href='http://www.kirkcudbright.dumgal.sch.uk/?layout=index&page=kirkcudbright-academy-events' data-type='button'></div>";
	
					// print
					$content .= "<div style='float:right;padding-left:10px'><a href=javascript:printDiv('" . $eventId . "')><img style='margin-top:0px; margin-left:0px' title='Print' src='img/print.jpg'></a></div>";
	
					// Ticketing info (if applicable)
					if (($event->ticket_event_id != 0) && ($member))
					{
						$ticketUrl = "https://plugin.wireflydesign.com/ticket/index.php/ticket/book/" . $event->ticket_event_id . "?sid=" . $member->sid . "&ref=event";
						$content .= "<div style='float:right'><a target='_blank' href='" . $ticketUrl . "'><img style='margin-top:0px; margin-left:0px' title='Book' src='img/book-s.jpg'></a></div><br/>";
						$content .= "<div style='clear:both'></div>";
					}
					$content .= "<br><br>";
				}
			}
			else	// Error
				$content .= "No event record - cant determine program";

			echo CJSON::encode(array(
				'paneId' => $paneId ,
				'eventId' => $eventId,
				'content' => $content,
                	));
		}
    }
}
