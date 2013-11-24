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
	 * Prints an event
	 */
	public function actionPrint()
	{
		$eventId = $_GET['event'];

		$content = "<html><style>";
		$css = file_get_contents(Yii::app()->basePath . "/../scripts/jelly/addon/custom/eventcode/eventcode.css");
		$content .= $css;
		$content .= "</style>";
		$content .= "<div id='accordion' style='margin:0px auto'>";
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $eventId);
		$event = Event::model()->find($criteria);
		if ($event)
		{
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

			if ($member)
			{
				if (trim($member->avatar_path) != '')
					$content .= "<img style='padding-right:5px;margin-top:0px; margin-right:0px' title='" . $member->organisation . "' src='" . Yii::app()->baseUrl . "/userdata/member/avatar/" . $member->avatar_path . "' width='20' height='20'>";
			}


			$content .= $event->title;

			$content .= "    </div>";

			$content .= "    <div id='header-date'>";
			$content .= $this->formatDateString($event->start, $event->end);
			$content .= "    </div>";

			$content .= "    <div id='header-venue'>";	
			$content .= $event->address;
			$content .= "    </div>";

			$content .= "    <div id='header-icons' style='float:left; padding-left:7px;'>";

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
						$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . $interest->name . "' src='" . Yii::app()->baseUrl . "/userdata/icon/" . $interest->icon_path . "' width='20' height='20'>";
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
						$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . $facility->name . "' src='" . Yii::app()->baseUrl . "/userdata/icon/" . $facility->icon_path . "' width='20' height='20'>";
				}
			}

			// @@TODO: These are hardcoded. Neednt be anymore now that theres 1 icon per band
			// Price Band icons
			if ($event->event_price_band_id == 1)	// Free
				$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Free' . "' src='" . Yii::app()->baseUrl . "/userdata/icon/" . 'Free x20.png' . "' width='20' height='20'>";
			else
			{
				// 1st Price
				if ($event->event_price_band_id == 2)	// 1st price
					$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Under £5' . "' src='" . Yii::app()->baseUrl . "/userdata/icon/" . 'Pound x20.png' . "' width='20' height='20'>";
				if ($event->event_price_band_id == 3)	// 2nd price
				{
					$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . '£5 - £10' . "' src='" . Yii::app()->baseUrl . "/userdata/icon/" . '2pound.png' . "' width='20' height='20'>";
				}
				if ($event->event_price_band_id == 4)	// 3rd price
				{
					$content .= "      <img style='margin-top:0px; margin-left:0px' title='" . 'Over £10' . "' src='" . Yii::app()->baseUrl . "/userdata/icon/" . '3pound.png' . "' width='20' height='20'>";
				}
			}

			$content .= "    </div>";
			$content .= "  </div>	<!-- /float left -->";


			$content .= "  <div style=float:right;width:120px;height:100%>";
			if (trim($event->thumb_path) != '')
			{
				if (file_exists('userdata/event/thumb/' . $event->thumb_path))
					$content .= "<img style='margin-top:-10px; margin-left:-20px' title='" . $event->thumb_path . "' src='" . Yii::app()->baseUrl . "/userdata/event/thumb/" . $event->thumb_path . "' width='140' height='115'>";
			}
			else if ($program)
				$content .= "<img style='margin-top:-10px; margin-left:-20px' title='" . $program->thumb_path . "' src='" . Yii::app()->baseUrl . "/userdata/program/thumb/" . $program->thumb_path . "' width='140' height='115'>";
			$content .= "  </div>";

			$content .= "</div> <!-- /header -->";

			// The content block
			$content .= "<div>";
			//$content .= $event->id;
			$content .= "</div>";
		}
		$content .= "</div>";
		$content .= "</html>";
die($content);
die('ok2');
		return;
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
				$criteria = new CDbCriteria;
				$criteria->condition = 'event_id = ' . $eventId;
				$ws = Ws::model()->find($criteria);
				if ($ws)
				{
					$content .= $ws->short_description . "..." . "<br>";
					$content .= "<table width=100% style='padding:10px 10px 10px 0px'><tr><td style='padding:0px; width:40%'>";

					// @@EG: Calling a jelly addon directly, from outside the jelly
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
					$content .= "<script> markerByOs('" . $ws->os_grid_ref . "'); </script>";

					$content .= "</td><td style='width:60%; padding-left:10px; vertical-align:top'>";
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
					$content .= "</td></tr></table>";

					// Description
					$content .= $event->description . "<br>";
					// Additional Venue Info
					if (trim($ws->additional_venue_info) != '')
						$content .= "Additional venue info: " . $ws->additional_venue_info . "<br>";
					if (trim($ws->full_price_notes) != '')
						$content .= "Full price notes: " . $ws->full_price_notes . "<br>";

					// Facebook
					$content .= "<div style='float:right;padding-left:10px;padding-top:1px' class='fb-share-button' data-href='http://www.wildseasons.co.uk' data-type='button'></div>";


					$content .= "<br><br>";
				}
				else 
					$content .= "No Ws record";
			}
			else 
				$content .= "No event record";

			echo CJSON::encode(array(
				'paneId' => $paneId ,
				'eventId' => $eventId,
				'content' => $content,
                	));
		}
    }

	/* Used by the print function */
	public function formatDateString($startDate, $endDate)
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
