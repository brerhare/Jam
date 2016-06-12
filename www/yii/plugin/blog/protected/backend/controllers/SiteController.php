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

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR SUSANMCKAY ---------- REMOVE
    public function actionSusanmckayDirect()
    {
        Yii::app()->session['uid'] = 104;
        $identity = new UserIdentity('SusanMcKay', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR ORIGINALYOU ---------- REMOVE
    public function actionOriginalYouDirect()
    {
        Yii::app()->session['uid'] = 92;
        $identity = new UserIdentity('LifeEditor', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR SENWICK ---------- REMOVE
    public function actionSenwickDirect()
    {
        Yii::app()->session['uid'] = 102;
        $identity = new UserIdentity('Kerry', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR BEIRC ---------- REMOVE
    public function actionBeircDirect()
    {
        Yii::app()->session['uid'] = 20;
        $identity = new UserIdentity('beirc', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR Holiday Let Services ---------- REMOVE
    public function actionHolidayDirect()
    {
        Yii::app()->session['uid'] = 101;
        $identity = new UserIdentity('lindaMB', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
	}
    
    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR Jardine Roofing ---------- REMOVE
    public function actionJardineDirect()
    {
        Yii::app()->session['uid'] = 100;
        $identity = new UserIdentity('Jardines', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
	}
    
    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR Joseawright ---------- REMOVE
    public function actionJoSeawrightDirect()
    {
        Yii::app()->session['uid'] = 98;
        $identity = new UserIdentity('seawright99', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
	}
    
    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR Kirkcudbright Academy ---------- REMOVE
    public function actionKbtacademyDirect()
    {
        Yii::app()->session['uid'] = 99;
        $identity = new UserIdentity('kbt-academy', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR Wirefly ---------- REMOVE
    public function actionWireflyDirect()
    {
        Yii::app()->session['uid'] = 18;
        $identity = new UserIdentity('info@wireflydesign.com', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR Being Business ---------- REMOVE
    public function actionBeingDirect()
    {
        Yii::app()->session['uid'] = 97;
        $identity = new UserIdentity('admin@beingbusiness.co.uk', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR Barstobrick RC ---------- REMOVE
    public function actionBarstoDirect()
    {
        Yii::app()->session['uid'] = 95;
        $identity = new UserIdentity('barstobrickrc@hotmail.co.uk', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR Susie Jamieson ---------- REMOVE
    public function actionSusiejamiesonDirect()
    {
        Yii::app()->session['uid'] = 93;
        $identity = new UserIdentity('creative-therapies', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR Cocoa Kulula ---------- REMOVE
    public function actionCocoaDirect()
    {
        Yii::app()->session['uid'] = 91;
        $identity = new UserIdentity('SDawson', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR ZoeLife ---------- REMOVE
    public function actionZoelifeDirect()
    {
        Yii::app()->session['uid'] = 90;
        $identity = new UserIdentity('ZoeRoberts', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR CraigieKnowes ---------- REMOVE
    public function actionCraigieknowesDirect()
    {
        Yii::app()->session['uid'] = 89;
        $identity = new UserIdentity('Craigieknowes', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR 6TY ---------- REMOVE
    public function actionShadesDirect()
    {
        Yii::app()->session['uid'] = 88;
        $identity = new UserIdentity('info@6tyshadesofbeauty.co.uk', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR OUTLOOK SOLUTIONS ---------- REMOVE
	public function actionOutlookDirect()
	{
		Yii::app()->session['uid'] = 87;
		$identity = new UserIdentity('martin@outlooksolutions.com.au', 'site2plugin');
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
        $identity = new UserIdentity('chairman@dgbloodbikes.org.uk', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

    /**
     * Displays the DIRECT login page
     */
// @@TODO: HARDCODED FOR WEETARGET ---------- REMOVE
    public function actionWeetargetDirect()
    {
        Yii::app()->session['uid'] = 76;
        $identity = new UserIdentity('tristen@weetarget.co.uk', 'site2plugin');
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
        $identity = new UserIdentity('admin@dglink.co.uk', 'site2plugin');
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
        $identity = new UserIdentity('info@dgnews-sport.co.uk', 'site2plugin');
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
        $identity = new UserIdentity('register@rotarypeaceproject.com', 'site2plugin');
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
        $identity = new UserIdentity('chairperson@mossheadpreschool.co.uk', 'site2plugin');
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
        $identity = new UserIdentity('office@opendoorsart.com', 'site2plugin');
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
    public function actionAbsoluteClassicsdirect()
    {
        Yii::app()->session['uid'] = 7;
        $identity = new UserIdentity('mcquiston.concerts@gmail.com', 'site2plugin');
        $identity->authenticate();
        $duration = 3600*24*14; // 14 days
        Yii::app()->user->login($identity, $duration);
        $this->redirect(array('site/index'));
    }

// @@TODO: HARDCODED FOR FAD ---------- REMOVE
	public function actionFadDirect()
	{
		Yii::app()->session['uid'] = 57;
		$identity = new UserIdentity('jo@fadguide.com', 'site2plugin');
		$duration = 3600*24*14; // 14 days
		Yii::app()->user->login($identity, $duration);
		$this->redirect(array('site/index'));
	}

	/**
	 * Displays the DIRECT login page
	 */
// @@TODO: HARDCODED FOR ELEGANT ORIGINALS ---------- REMOVE
	public function actionElegantDirect()
	{
		Yii::app()->session['uid'] = 59;
		$identity = new UserIdentity('caroline@elegantoriginals.co.uk', 'site2plugin');
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
		$identity = new UserIdentity('wendy@jacquiesbeauty.co.uk', 'site2plugin');
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
        $identity = new UserIdentity('nancy@styleyourvenue.co.uk', 'site2plugin');
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
        $identity = new UserIdentity('rachel@the-art-room.co.uk', 'site2plugin');
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
