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
					$content .= "<table width=100%><tr><td style='width:40%'>";
					$content .= "googlemap";
					$content .= "</td><td style='width:60%'>";
					// Booking
					$content .= "Booking ";
					if ($ws->booking_essential)
						$content .= "<b>essential</b>";
					else
						$content .= "not essential";
					$content .= "<br/>";
					// Organisation
					$criteria = new CDbCriteria;
					$criteria->condition = 'id = ' . $event->member_id;
					$member = Member::model()->find($criteria);
					if ($member)
						if (trim($member->organisation) != '')
							$content .= "Organization: " . $member->organisation . "<br>";
					// Contact details
					$content .= "Contact details: " . $event->contact . "<br>";
					// Website
					$content .= "Website: " . $event->web . "<br>";
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
//$content = 'xx';
			echo CJSON::encode(array(
				'paneId' => $paneId ,
				'eventId' => $eventId,
				'content' => $content,
                	));
		}
    }
}
