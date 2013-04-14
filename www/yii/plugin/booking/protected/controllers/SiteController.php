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
		Yii::log("INDEX LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
        $model=Room::model()->findByPk(2);
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $this->render('index',array(
                        'model'=>$model,
                        'roomdata'=>array(1,2,3),
                ));
		//$this->render('index');
	}


// @@EG Ajax (see site/_form_choose_rooms.php for client side
	public function actionAjaxTest()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			Yii::log("TEST AJAX CALL: " . $_POST['date'] , CLogger::LEVEL_WARNING, 'system.test.kim');
			if(isset($_POST['numRooms']))
			{
					$numRooms = $_POST['numRooms'];
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . 3);
					$rooms = Room::model()->findAll($criteria);
					$roomNo = 0;
					$roomData = array();
					for ($i = 0; $i < $numRooms; $i++)
					{
						foreach ($rooms as $room)
						{
							$roomNo++;
							$roomNoStr = 'room_' . $roomNo;
							$roomData[$roomNoStr] = array();
							$roomData[$roomNoStr]['id'] = $room->id;
							$roomData[$roomNoStr]['title'] = $room->title;
						}
					}					
					
					
					
					
/*					$model = Room::model()->findAll();
					$roomData = array(
						'room_1'=>array(
							'id'=>'5',
							'title'=>'Room One',
							'price'=>array(
								'p1'=>'11',
								'p2'=>'22',
								'p3'=>'33'
							 ),
						),
						'room2'=>array(
							'title'=>'R2',
							'price'=>array(
								'p101'=>'1100',
								'p102'=>'2200',
								'p103'=>'3003'
							 )
						)
					);
*/
					echo CJSON::encode($roomData);
			}
//			echo CJSON::encode(array(
//                    'name2browser' => 'trueekse',
//                    'status' => 'HPI check failed, please enter a registration number '
//                ));
	    }
		Yii::log("EXIT TEST AJAX CALL: " . $_POST['date'] , CLogger::LEVEL_WARNING, 'system.test.kim');
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
}