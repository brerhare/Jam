<?php

class SiteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/main'
	 */
	 //public $layout='//layouts/main2';

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
		// Show the default gallery
		$this->actionIndexGallery(0);
	}

	public function actionIndexGallery($galleryId)
	{
		$galleries = Gallery::model()->findAll();
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index', array('galleryId' => $galleryId, 'galleries' => $galleries));
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
		$this->layout='//layouts/main2'; // kim
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$modelC=new Contact;
				$modelC->email = $model->email;
				$modelC->name = $model->name;
				$modelC->save();

				// Fill these, as they are not filled by the form
				$model->subject = $model->name . ' has signed up at glitzaratti.com';
				$model->body = 'Email address: ' . $model->email;

				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you.');
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

	/**
	 * Paypal return screen after the buyer hits 'Pay' from within paypal
	 */
	public function actionPaypalSuccess()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('paypalSuccess', array(
			'model'=>$model,
		));
	}

	/**
	 * T & C display
	 */
	public function actionTandc()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('tandc', array(
			'model'=>$model,
		));
	}

	/**
	 * Terms of use display
	 */
	public function actionTerms()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('terms', array(
			'model'=>$model,
		));
	}


	/**
	 * Privacy Policy display
	 */
	public function actionPrivacy()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('privacy', array(
			'model'=>$model,
		));
	}

	/**
	 * Delivery & Returns Policy display
	 */
	public function actionDeliverandreturn()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('deliverandreturn', array(
			'model'=>$model,
		));
	}

	/**
	 * 'Send Your Own' page display
	 */
	public function actionSendyourown()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('sendyourown', array(
			'model'=>$model,
		));
	}

	/**
	 * Links page display
	 */
	public function actionLinks()
	{
		$this->layout='//layouts/main2'; // kim
		$model=new Product('search');
		$this->render('links', array(
			'model'=>$model,
		));
	}
}