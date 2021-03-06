<?php

class SiteController extends Controller
{
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
		if (Yii::app()->user->isGuest)
			$this->redirect(array('site/login'));

        // Store the referer (hosting site) in a session cookie
        $referer = "unknown http_referer";
        if (isset($_SERVER['HTTP_REFERER']))
            $referer = $_SERVER['HTTP_REFERER'];
        Yii::app()->session['http_referer'] = str_replace("/backend.php", "", $referer);

		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
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
			{
				// Set the admin session var if is an admin on ANY project (shows the 'Project' menu option)
				unset(Yii::app()->session['isAnyAdmin']);
				$criteria = new CDbCriteria;
				$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
				$criteria->addCondition("privilege_level = 2");	//@@TODO Privilege hardcoded
				$memberHasProgram = MemberHasProgram::model()->findAll($criteria);
				if ($memberHasProgram)
					Yii::app()->session['isAnyAdmin'] = 1;

				// And carry on...
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	// Login from a backend
	public function actionLogin2()
	{
		$model=new LoginForm;
		if (trim($_GET['sid']) == "")
			$this->redirect(array('site/login'));
        $criteria = new CDbCriteria;
        $criteria->addCondition("sid = '" . $_GET['sid']. "'");
        $member = Member::model()->find($criteria);
        if ($member == null)
        {
            Yii::log("Cant autologin using SID");
            throw new CHttpException(500,'Missing member - Cannot continue without a valid sid');
        }
		$model->username = $member->user_name;
		$model->password = $member->password;
		if($model->validate() && $model->login())
		{
			// Set the admin session var if is an admin on ANY project (shows the 'Project' menu option)
			unset(Yii::app()->session['isAnyAdmin']);
			$criteria = new CDbCriteria;
			$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
			$criteria->addCondition("privilege_level = 2");	//@@TODO Privilege hardcoded
			$memberHasProgram = MemberHasProgram::model()->findAll($criteria);
			if ($memberHasProgram)
				Yii::app()->session['isAnyAdmin'] = 1;

			// And carry on...
			$this->redirect(Yii::app()->user->returnUrl);
		}
        throw new CHttpException(500,'Member validation problem - Cannot continue without a valid sid');
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
