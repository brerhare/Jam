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

    public function actionAjaxSignup()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $retArr = array();
			$retArr['error'] = "";

            Yii::log("AJAX CALL (signup): username=" . $_POST['username'] . " password=" . $_POST['password'], CLogger::LEVEL_WARNING, 'system.test.kim');

			// Ensure username is not already taken
			$criteria = new CDbCriteria;
			$criteria->addCondition("username = '" . $_POST['username'] . "'");
			$member = Member::model()->find($criteria);
			if ($member)
			{
				$retArr['error'] = "Username already taken";
				echo CJSON::encode($retArr);
				return;
			}

			$retArr['mode'] = "signup";

			$retArr['business_name'] = "";
			$retArr['address1'] = "";
			$retArr['address2'] = "";
			$retArr['address3'] = "";
			$retArr['address4'] = "";
			$retArr['postcode'] = "";
			$retArr['contact'] = "";
			$retArr['web'] = "";
			$retArr['email'] = "";
			$retArr['phone'] = "";
			$retArr['opening_hours'] = "";
			$retArr['html_content'] = "";
			$retArr['logo_path'] = "";
			$retArr['slider_image_path'] = "";
			$retArr['public'] = "";

			echo CJSON::encode($retArr);
		}
	}

    public function actionAjaxLogin()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $retArr = array();
			$retArr['error'] = "";

            Yii::log("AJAX CALL (login): username=" . $_POST['username'] . " password=" . $_POST['password'], CLogger::LEVEL_WARNING, 'system.test.kim');

			// Ensure user exists
			$criteria = new CDbCriteria;
			$criteria->addCondition("username = '" . $_POST['username'] . "'");
			$member = Member::model()->find($criteria);
			if (!$member)
			{
				$retArr['error'] = "User does not exist";
				echo CJSON::encode($retArr);
				return;
			}

			$retArr['mode'] = "login";

			$retArr['business_name'] = $member->business_name;
			$retArr['address1'] = $member->address1;
			$retArr['address2'] = $member->address2;
			$retArr['address3'] = $member->address3;
			$retArr['address4'] = $member->address4;
			$retArr['postcode'] = $member->postcode;
			$retArr['contact'] = $member->contact;
			$retArr['web'] = $member->web;
			$retArr['email'] = $member->email;
			$retArr['phone'] = $member->phone;
			$retArr['opening_hours'] = $member->opening_hours;
			$retArr['html_content'] = $member->html_content;
			$retArr['logo_path'] = $member->logo_path;
			$retArr['slider_image_path'] = $member->slider_image_path;
			$retArr['public'] = $member->public;

			echo CJSON::encode($retArr);
		}
	}


}
