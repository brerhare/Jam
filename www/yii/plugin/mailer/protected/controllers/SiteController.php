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

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','unsubscribe'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if (isset($_GET['unsubscribe']))
		{
			return $this->actionUnsubscribe();
		}
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

	public function actionUnsubscribe()
	{
		if ((trim($_GET['unsubscribe'])) == "")
		{
		die("<style>* {color:#bf5855;}</style><br><br><br><br><br><br><br><center><h3>No email given to unsubscribe!</h3><h4>Please contact the list owner via the website you subscribed at and ask to be manually removed<br><br>Apologies for the inconvenience, we do respect your privacy</h4><h5>Should you have ANY difficulty with that you may raise this with us directly at info@wireflydesign.com</h5></center>");
		}
        $criteria = new CDbCriteria;
        $criteria->addCondition("email_address = '" . $_GET['unsubscribe'] . "'");
        $members = MailerMember::model()->findAll($criteria);
		foreach ($members as $member)
		{
            $member->delete();
        }
	die("<style>* {color:#bf5855;}</style><br><br><br><br><br><br><br><center><h3>Thank you</h3><h4>You are no longer on our mailing list and will receive no further emails from us</h4></center>");
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

    public function actionAjaxAddSignupMember()
    {
		Yii::log("AJAX CALL TO ADD MEMBER: uid:" . Yii::app()->session['uid'] . " name:" . $_GET['name'] . " email:" . $_GET['email'], CLogger::LEVEL_WARNING, 'system.test.kim');

        // Pick up the public signup list for this uid. Create it if it doesnt exist
        $criteria = new CDbCriteria;
        $criteria->addCondition("uid = " . Yii::app()->session['uid']);
        $criteria->addCondition("name = 'Public Signup'");
        $mailerList=MailerList::model()->find($criteria);
        if ($mailerList===null)
        {
            $mailerList=new MailerList;
            $mailerList->uid = Yii::app()->session['uid'];
            $mailerList->name = "Public Signup";
            $mailerList->save();
        }

        // Add the signup request if not already there
        $criteria = new CDbCriteria;
        $criteria->addCondition("uid = " . Yii::app()->session['uid']);
        $criteria->addCondition("email_address = '" . $_GET['email'] . "'");
        $member = MailerMember::model()->find($criteria);
        if ($member===null)
        {
            $member=new MailerMember;
            $member->uid = Yii::app()->session['uid'];
            $member->first_name = $_GET['name'];
            $member->email_address = $_GET['email'];
            $member->mailer_list_id = $mailerList->id;
            $member->active = 1;
            $member->save();
        }
	}

    public function actionAjaxContactUs()
    {
		Yii::log("AJAX CALL TO CONTACT US: uid:" . Yii::app()->session['uid'] . " name:" . $_GET['name'] . " email:" . $_GET['email'], CLogger::LEVEL_WARNING, 'system.test.kim');

        // Send email to the address defined on system settings

        // Pick up our only record
		$to = $_GET['settingsemail'];
        if (strlen(trim($to)) > 0)
        {
            $from =  $_GET['email'];
            $fromName = $_GET['name'];
            $subject = "[CONTACTFORM] " . $_GET['subject'];
            $message = "<b><u>" . $_GET['body'] . "</u></b><br/>";
            // phpmailer
            $mail = new PHPMailer();
            $mail->AddAddress($to);
            $mail->SetFrom($from, $fromName);
            $mail->AddReplyTo($from, $fromName);
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            if (!$mail->Send())
                Yii::log("CONTACT US COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
            else
                Yii::log("CONTACT US SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
        }
	}

}

