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
$optArr['id'] = 'detailMap-' . $eventId;
$optArr['width'] = '200px';
$optArr['height'] = '200px';
$optArr['maptype'] = 'terrain';
$optArr['inputmode'] = 'os';
$optArr['center'] = $ws->os_grid_ref;
$optArr['zoom'] = '9';
$ret = $addon->init($optArr, '/event/scripts/jelly/addon/map/google_os');
$content .= $ret[0];
$content .= '<script>' . $ret[1] . '</script>';
$content .= "<script> markerByOs('" . $ws->os_grid_ref . "'); </script>";

					$content .= "</td><td style='width:60%; padding:10px'>";
					// Booking
					$content .= "Booking ";
					if ($ws->booking_essential)
						$content .= "<b>essential</b>";
					else
						$content .= "not essential";
					// Pick up the member record
					$criteria = new CDbCriteria;
					$criteria->condition = 'id = ' . $event->member_id;
					$member = Member::model()->find($criteria);
					// Ticketing info (if applicable)
					if (($event->ticket_event_id != 0) && ($member))
					{
						$ticketUrl = "https://plugin.wireflydesign.com/ticket/index.php/ticket/book/" . $event->ticket_event_id . "?sid=" . $member->sid . "&ref=event";
						$content .= "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" . "<a target='_blank' href='" . $ticketUrl . "'><img style='margin-top:0px; margin-left:0px' title='Book' src='img/book-s.jpg'></a>";
					}
					$content .= "<br/>";
					// Organisation
					if ($member)
						if (trim($member->organisation) != '')
							$content .= "Organization: " . $member->organisation . "<br>";
					// Contact details
					$content .= "Contact details: " . $event->contact . "<br>";
					// Website
					if (trim($event->web) != '')
					{
						$http = "http://";
						if (strstr("http://", $event->web))
							$http = "";
						$content .= "Website: " . "<a href='" . $http . $event->web . "'' target='_blank'>" . $event->web . "</a>" . "<br>";
					}
					// Suitable ages
					if (($ws->min_age == 0) && ($ws->max_age == 0))
						$content .= "Suitable for all ages" . "<br>";
					else if (($ws->min_age != 0) && ($ws->max_age != 0))
						$content .= "Suitable for ages " . $ws->min_age . " to " . $ws->max_age . "<br>";
					else if (($ws->min_age != 0) && ($ws->max_age == 0))
						$content .= "Suitable for ages " . $ws->min_age . " and older" . "<br>";
					else if (($ws->min_age == 0) && ($ws->max_age != 0))
						$content .= "Suitable for ages up to " . $ws->max_age . "<br>";
					// Grade
					$content .= "Grade: " . $ws->grade . "<br>";
					// OS Grid Ref
					$content .= "OS grid ref : " . $ws->os_grid_ref . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp(" . $event->post_code . ")" . "<br>";
					$content .= "</td></tr></table>";

					// Description
					$content .= $event->description . "<br>";
					// Additional Venue Info
					if (trim($ws->additional_venue_info) != '')
						$content .= "Additional venue info: " . $ws->additional_venue_info . "<br>";
					if (trim($ws->full_price_notes) != '')
						$content .= "Full price notes: " . $ws->full_price_notes . "<br>";
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
}
