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

	public function actionAjaxLogin()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			Yii::log("LOGIN AJAX CALL: username:" . $_POST['username'] . " password:" . $_POST['password'] . " loggedIn:" . $_POST['loggedIn'], CLogger::LEVEL_WARNING, 'system.test.kim');

			$retArr = array();

			$retArr['error'] = "";
			$retArr['loggedOut'] = "";

			// Handle logout
			if (!isset($_POST['loggedIn']))
				$retArr['error'] = 'Internal error - logged in status not given';
			if ($_POST['loggedIn'] == '1')
			{
				$retArr['loggedOut'] = '1';
				unset(Yii::app()->session['mid']);
				echo CJSON::encode($retArr);
				return;
			}

			// Check if this is a query for login status
			if ($_POST['loggedIn'] == '?')
			{
				if (!(isset(Yii::app()->session['mid'])))
					return;
				$_POST['username'] = Yii::app()->session['username'];
				$_POST['password'] = Yii::app()->session['password'];
			}

			// Check passed parameters
			if (!isset($_POST['username']))
				$retArr['error'] = 'No username specified for login';
			else if (!isset($_POST['password']))
				$retArr['error'] = 'No member id specified for login';

			// Check member credentials
			if ($retArr['error'] == "");
			{
				$criteria = new CDbCriteria;
				$criteria->addCondition("username = '" . $_POST['username'] . "'");
				$member = Member::model()->find($criteria);
				if (!($member))
					$retArr['error'] = 'No username found for login';
				else if ($member->password != $_POST['password'])
					$retArr['error'] = 'Incorrect member id for login';
				if ($retArr['error'] == "")
				{
					// Pick up the member type
					$criteria = new CDbCriteria;
					$criteria->addCondition("id = " . $member->member_type_id);
					$memberType = MemberType::model()->find($criteria);
					if (!($memberType))
						$retArr['error'] = 'No membertype found for login';
					else
					{
						$retArr['id'] = $member->id;
						$retArr['password'] = $member->password;
						$retArr['displayName'] = $member->displayname;
						$retArr['userName'] = $member->username;
						Yii::app()->session['mid'] = $member->id;
						Yii::app()->session['username'] = $member->username;	// Store for persistent session
						Yii::app()->session['password'] = $member->password;	// Store for persistent session

						$retArr['memberType'] = $member->member_type_id;
						$retArr['slots'] = $memberType->slots;
						if ($memberType->week_month == 2)
							$retArr['period'] = 'month';
						else
							$retArr['period'] = 'week';
					}
				}
			}

			if ($retArr['error'] != "")
				unset(Yii::app()->session['mid']);

			echo CJSON::encode($retArr);

/*              echo CJSON::encode(array(
                    'error' => '',
                    'id' => '42',
                    'password' => '237',
                    'displayName' => 'Carla Milne',
                    'memberType' => '2',
                    'slots' => '4',
                    'period' => 'week',
                ));
*/

		}
		Yii::log("EXIT LOGIN AJAX CALL: " . $_POST['username'] , CLogger::LEVEL_WARNING, 'system.test.kim');
	}

	public function actionAjaxEvent()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			Yii::log("EVENT AJAX CALL: memberId=" . $_POST['memberId'], CLogger::LEVEL_WARNING, 'system.test.kim');
			$retArr = array();
			$retArr['error'] = "";
			$retArr['action'] = "";

			$editing = 0;
			$deleting = 0;
			$inserting = 0;

			if ($_POST['action'] == 'delete')
			{
				$deleting = 1;
				$retArr['action'] = "delete";
			}
			else if ($_POST['eventId'] == '0')
			{
				$inserting = 1;
				$retArr['action'] = "insert";
			}
			else if ($_POST['eventId'] > '0')
			{
				$editing = 1;
				$retArr['action'] = "edit";
			}

			$retArr['event_id'] = $_POST['eventId'];

			Yii::log("  memberPassword=" . $_POST['memberPassword'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  arena=" . $_POST['arena'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  date=" . $_POST['date'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  eventId=" . $_POST['eventId'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  start=" . $_POST['start'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  end=" . $_POST['end'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  description=" . $_POST['description'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  share=" . $_POST['share'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  confirmed=" . $_POST['confirmed'], CLogger::LEVEL_WARNING, 'system.test.kim');

			// Pick up the member
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $_POST['memberId']);
			$member = Member::model()->find($criteria);
			if (!($member))
			{
				$retArr['error'] = "Invalid member";
				echo CJSON::encode($retArr);
				return;
			}

			// Check no clash with existing booking
			$start = $_POST['start']; if (strlen($start) == 1) $start = "0" . $start;
			$end = $_POST['end']; if (strlen($end) == 1) $end = "0" . $end;
			$startChk = substr($_POST['date'], 0, 11) . $start . substr($_POST['date'], 13, 6);
			$endChk = substr($_POST['date'], 0, 11) . $end . substr($_POST['date'], 13, 6);
			// Only consider events for today
			$dayStart = substr($_POST['date'], 0, 11) . "00:00:00";
			$dayEnd = substr($_POST['date'], 0, 11) . "23:59:59";
			$criteria = new CDbCriteria;
			$criteria->addCondition("id != " . $_POST['eventId']);					// We might be updating our own existing event
			$criteria->addCondition("arena = " . $_POST['arena']);
			$criteria->addCondition("end > " . "'" . $startChk . "'");
			$criteria->addCondition("start < " . "'" . $endChk . "'");
			$criteria->addCondition("start >= " . "'" . $dayStart . "'");
			$criteria->addCondition("end <= " . "'" . $dayEnd . "'");
			$events = Event::model()->findAll($criteria);
			Yii::log("  daystart =[" . $dayStart . "]", CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("  dayend =[" . $dayEnd . "]", CLogger::LEVEL_WARNING, 'system.test.kim');
			if (($events) && ($_POST['share'] == 0))
			{
				$retArr['error'] = "This booking slot is unavailable. Please refresh your browser to see up-to-date content";
				echo CJSON::encode($retArr);
				return;
			}
			$shareable = 1;
			foreach ($events as $event)
			{
				if ($event->share == 0)
				{
					$shareable = 0;
					break;
				}
			}
			if (!($shareable))
			{
				$retArr['error'] = "This booking slot is unavailable to share. Please refresh your browser to see up-to-date content";
				echo CJSON::encode($retArr);
				return;
			}

			// Validate max slots (only if new)
			if (($inserting) && ($this->checkRemainingSlots($member->id, $startChk) == 0)) {
				$retArr['error'] = "No more bookable slots for this member type";
				echo CJSON::encode($retArr);
				return;
			}

			// Validate 2 week ahead rule

			// All ok, update the event
			if (($editing) || ($deleting))
			{
				// Fetch original event
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $_POST['eventId']);
				$event = Event::model()->find($criteria);
				if (!($event))
				{
					$retArr['error'] = "Cant retrieve event for editing";
					echo CJSON::encode($retArr);
					return;
				}
			}
			if ($deleting)
			{
				$event->delete();
				echo CJSON::encode($retArr);
				return;
			}
			if (($inserting) || ($editing))
			{
				if ($inserting)
	                $event = new Event;
				$event->arena = $_POST['arena'];
				$event->description = $_POST['description'];
				if (trim($event->description) == "")
					$event->description = "-";
				$start = $_POST['start']; if (strlen($start) == 1) $start = "0" . $start;
				$end = $_POST['end']; if (strlen($end) == 1) $end = "0" . $end;
				$event->start = substr($_POST['date'], 0, 11) . $start . substr($_POST['date'], 13, 6);
Yii::log("EVENT AJAX CALL: " . $event->start, CLogger::LEVEL_WARNING, 'system.test.kim');
Yii::log("EVENT AJAX CALL: " . $event->end, CLogger::LEVEL_WARNING, 'system.test.kim');
				$event->end = substr($_POST['date'], 0, 11) . $end . substr($_POST['date'], 13, 6);
				$event->share = $_POST['share'];
				$event->confirmed = $_POST['confirmed'];
				$event->password = $member->password;
				if (!($event->save()))
				{
                    $retArr['error'] = "Cant create event";
                    echo CJSON::encode($retArr);
                    return;
				}

				// Send the updated/new details
				$retArr['title'] = $member->displayname;
				$retArr['description'] = $event->description;
				$retArr['member_id'] = $member->id;
				$retArr['event_id'] = $event->id;
				$retArr['arena'] = $event->arena;
				$retArr['start'] = $event->start;
				$retArr['end'] = $event->end;
				$retArr['share'] = $event->share;
				$retArr['confirmed'] = $event->confirmed;
				$retArr['password'] = $event->password;
			}

			echo CJSON::encode($retArr);

		}
		Yii::log("EXIT EVENT AJAX CALL: ", CLogger::LEVEL_WARNING, 'system.test.kim');
	}

	/* 0 = none, n = howmany, -1 = error */
	public function checkRemainingSlots($memberId, $bookDate)
	{
		Yii::log("checkRemainingSlots member=[" . $memberId . "], date=[" . $bookDate . "]", CLogger::LEVEL_WARNING, 'system.test.kim');
		// Pick up the member
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $memberId);
		$member = Member::model()->find($criteria);
		if (!($member))
			return -1;

		// Pick up the member type
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $member->member_type_id);
		$memberType = MemberType::model()->find($criteria);
		if (!($memberType))
			return -1;

		// Get starting Sunday and ending Saturday for week-based members
		$start = "";
		$today = date("Y-m-d");


		$date = substr($bookDate, 0, 10);	// 2017-07-13
		$dateBook = strtotime($bookDate);
		$weekDay = date('w', $dateBook);

		if ($memberType->week_month == 1) {		// weekly
			$weekStart = date('Y-m-d', strtotime($date. '-'.$weekDay.' days'));
			$weekEnd = date('Y-m-d', strtotime($date. '+'.(6-$weekDay).' days'));
		} else { // if ($memberType->week_month == 2)	// monthly
			//$weekStart = date('Y-m-d', strtotime($today.'last sunday -21 days'));
			//$weekEnd = date('Y-m-d', strtotime($today.'last sunday +7 days'));
			$weekStart = date('Y-m-01', strtotime($today));
			$weekEnd = date('Y-m-t', strtotime($today));
		}
		$criteria = new CDbCriteria;
		$criteria->addCondition("password = " . $member->password);
		$criteria->addCondition("start >= " . "'" . $weekStart . "'");
		$criteria->addCondition("end <= " . "'" . $weekEnd . "'");
		$events = Event::model()->findAll($criteria);
		$usedSlots = 0;
		foreach ($events as $event)
			$usedSlots++;
		Yii::log("checkRemainingSlots dateBook=[" . $dateBook . "], weekDay=[" . $weekDay . "], weekStart=[" . $weekStart . "], weekEnd=[" . $weekEnd . "]", CLogger::LEVEL_WARNING, 'system.test.kim');
		Yii::log("checkRemainingSlots used [" . $usedSlots . "] of [" . $memberType->slots . "]", CLogger::LEVEL_WARNING, 'system.test.kim');
		if ($usedSlots >= $memberType->slots)
			return 0;
		return 1;
	}

}
