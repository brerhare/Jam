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

// @@TODO: HARDCODED FOR OUTLOOK SOLUTIONS ---------- REMOVE
    public function actionOutlookDirect()
    {
        Yii::app()->session['uid'] = 87;
        $identity = new UserIdentity('martin@outlooksolutions.com.au', '83bs9hrdb');
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

// @@TODO: HARDCODED FOR ELEGANT ORIGINALS ---------- REMOVE
    public function actionElegantDirect()
    {
        Yii::app()->session['uid'] = 59;
        $identity = new UserIdentity('caroline@elegantoriginals.co.uk', 'gothchick');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR BLOODBIKES ---------- REMOVE
    public function actionBloodbikesDirect()
    {
        Yii::app()->session['uid'] = 77;
        $identity = new UserIdentity('chairman@dgbloodbikes.org.uk', '13100dbik35');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

// @@TODO: HARDCODED FOR AbSOLUTE CLASSICS ---------- REMOVE
    public function actionAbsoluteClassicsdirect()
    {       
        Yii::app()->session['uid'] = 7;
        $identity = new UserIdentity('mcquiston.concerts@gmail.com', 'greyfriars');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }   

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DGLINK ---------- REMOVE
    public function actionDglinkDirect()
    {
        Yii::app()->session['uid'] = 75;
        $identity = new UserIdentity('admin@dglink.co.uk', 'communityV2');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR ROTARY PEACE PROJECT ---------- REMOVE
    public function actionRotaryPeaceProjectDirect()
    {
        Yii::app()->session['uid'] = 56;
        $identity = new UserIdentity('register@rotarypeaceproject.com', 'district1020');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR DGNEWS-SPORT ---------- REMOVE
    public function actionDgnewsSportDirect()
    {
        Yii::app()->session['uid'] = 74;
        $identity = new UserIdentity('info@dgnews-sport.co.uk', 'nicole500');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR MOSSHEAD PRESCHOOL ---------- REMOVE
    public function actionMossheadPreschoolDirect()
    {
        Yii::app()->session['uid'] = 73;
        $identity = new UserIdentity('chairperson@mossheadpreschool.co.uk', 'earlyyears');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR OPEN DOORS ART ---------- REMOVE
    public function actionOpenDoorsArtDirect()
    {
        Yii::app()->session['uid'] = 72;
        $identity = new UserIdentity('office@opendoorsart.com', 'alternativeSF');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR FAD ---------- REMOVE
    public function actionFadDirect()
    {
        Yii::app()->session['uid'] = 57;
        $identity = new UserIdentity('jo@fadguide.com', 'foodiefest');
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR JACQUIES ---------- REMOVE
	public function actionJDirect()
	{
		Yii::app()->session['uid'] = 19;
		$identity = new UserIdentity('wendy@jacquiesbeauty.co.uk', 'guinot');
		$duration = 3600*24*14; // 14 days
		Yii::app()->user->login($identity, $duration);
		$this->redirect(array('site/index'));
	}

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR STYLE ---------- REMOVE
    public function actionSDirect()
    {
        Yii::app()->session['uid'] = 22;
        $identity = new UserIdentity('nancy@styleyourvenue.co.uk', 'cu62mg6');
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR THE-ART-ROOM ---------- REMOVE
    public function actionADirect()
    {
        Yii::app()->session['uid'] = 24;
        $identity = new UserIdentity('rachel@the-art-room.co.uk', 'oldschool');
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
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
}
