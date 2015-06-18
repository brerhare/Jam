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
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR ROTARY PEACE PROJECT ---------- REMOVE
    public function actionRotaryPeaceProjectDirect()
    {
        Yii::app()->session['uid'] = 56;
        $identity = new UserIdentity('register@rotarypeaceproject.com', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR BRYCEWALKERVENDING ---------- REMOVE
    public function actionBryceWalkerVendingDirect()
    {
        Yii::app()->session['uid'] = 70;
        $identity = new UserIdentity('bryce@brycewalkervending.com', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR 1STAID4U ---------- REMOVE
    public function action1staid4uDirect()
    {
        Yii::app()->session['uid'] = 71;
        $identity = new UserIdentity('contact@1staid4u.co.uk', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DEMO ---------- REMOVE
    public function actionDemoDirect()
    {
        Yii::app()->session['uid'] = 4;
        $identity = new UserIdentity('demo', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DEMO1 ---------- REMOVE
    public function actionDemo1Direct()
    {
        Yii::app()->session['uid'] = 64;
        $identity = new UserIdentity('demo1', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DEMO2 ---------- REMOVE
    public function actionDemo2Direct()
    {
        Yii::app()->session['uid'] = 65;
        $identity = new UserIdentity('demo2', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DEMO3 ---------- REMOVE
    public function actionDemo3Direct()
    {
        Yii::app()->session['uid'] = 66;
        $identity = new UserIdentity('demo3', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DEMO4 ---------- REMOVE
    public function actionDemo4Direct()
    {
        Yii::app()->session['uid'] = 67;
        $identity = new UserIdentity('demo4', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DEMO5 ---------- REMOVE
    public function actionDemo5Direct()
    {
        Yii::app()->session['uid'] = 68;
        $identity = new UserIdentity('demo5', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR TEST ---------- REMOVE
    public function actionTestDirect()
    {
        Yii::app()->session['uid'] = 55;
        $identity = new UserIdentity('test', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

// @@TODO: HARDCODED FOR AbSOLUTE CLASSICS ---------- REMOVE
    public function actionAbsoluteClassicsDirect()
    {
        Yii::app()->session['uid'] = 7;
        $identity = new UserIdentity('mcquiston.concerts@gmail.com', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	public function actionManualTicket()
	{
		$this->render('ticket');
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
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
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	// Login from another plugin (events)
	public function actionLogin2()
	{
        if (trim($_GET['sid']) == "")
            $this->redirect(array('site/login'));

        $criteria = new CDbCriteria;
        $criteria->addCondition("sid = '" . $_GET['sid']. "'");
        $user = User::model()->find($criteria);
        if ($user == null)
        {
            Yii::log("Cant autologin using SID");
            throw new CHttpException(500,'Missing user - Cannot continue without a valid sid');
        }
        Yii::app()->session['uid'] = $user->id;
        $identity = new UserIdentity($user->email_address, $user->password);
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
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
