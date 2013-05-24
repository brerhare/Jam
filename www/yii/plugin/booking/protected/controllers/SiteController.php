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
	 * Show the 1st screen - choose a room(s)
	 */
	public function actionIndex()
	{
		Yii::log("INDEX LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
//var_dump($_POST);
		if(isset($_POST['nextButton']))
		{
				Yii::log("INDEX COMPLETE. GOING TO INDEX2. POSTED ROOMS=" . $_POST['numRooms'] , CLogger::LEVEL_WARNING, 'system.test.kim');
				foreach ($_POST as $field => $value)
				{
					Yii::app()->session[$field] = $value;
					//Yii::log("GIVING INDEX2 VALUES FOR " . Yii::app()->session[$field] . " = " . $value , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
				$this->redirect(array('index2'));
		}
        $model=Room::model()->findByPk(2);
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $this->render('index',array(
                        'model'=>$model,
                        'roomdata'=>array(1,2,3),
                ));
	}

	/*
	 * Show the 2nd screen - occupancy type and options selections
	 */
	public function actionIndex2() {
		Yii::log("INDEX 2 LOADING", CLogger::LEVEL_WARNING, 'system.test.kim');
//var_dump($_POST);
		if (isset($_POST['backButton']))
		{
			$this->redirect(array('index'));
		}

		if(isset($_POST['nextButton']))
		{
				Yii::log("INDEX 2  COMPLETE. GOING TO INDEX3. POSTED TOTALS=" . '@@TODO: fix' , CLogger::LEVEL_WARNING, 'system.test.kim');
				foreach ($_POST as $field => $value)
				{
					Yii::app()->session[$field] = $value;
					Yii::log("GIVING INDEX3 VALUES FOR " . Yii::app()->session[$field] . " = " . $value , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
				$this->redirect(array('index3'));
		}

		$model=Room::model()->findByPk(2);
		$this->render('index2',array(
			'model'=>$model,
			'roomdata'=>array(1,2,3),
		));
	}

	/*
	 * Show the 3rd screen - payment
	 */
	public function actionIndex3() {
		Yii::log("PAGE 3 LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		if (isset($_POST['backButton']))
			$this->redirect(array('index2'));
//var_dump($_POST);
		$model=new Customer;
		$model->uid = Yii::app()->session['uid'];
		$model->ref = date('U');
		$model->reservation_total = Yii::app()->session['bTotal'];
        if(isset($_POST['Customer']))
        {
            $model->attributes=$_POST['Customer'];
            if($model->save())
            {
            	// Artificial pause in lieue of processing payment
            	sleep(5);
                $this->redirect(array('index4'));
            }
        }
		$this->render('index3',array(
			'model'=>$model,
			'roomdata'=>array(1,2,3),
		));
	}

	/*
	 * Show the 4th screen - finished
	 */
	public function actionIndex4() {
		Yii::log("PAGE 4 LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		if (isset($_POST['finishedButton']))
			$this->redirect(array('index'));

		$this->render('index4',array(
			'roomdata'=>array(1,2,3),
		));
	}


// @@EG Ajax (see site/_form_choose_rooms.php for client side
	public function actionAjaxGetRoomPriceAvail()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			Yii::log("TEST AJAX CALL: date:" . $_POST['date'] . " arrival:" . $_POST['arrival'] . " departure:" . $_POST['departure'] . " room:" . $_POST['roomList'], CLogger::LEVEL_WARNING, 'system.test.kim');
			if(isset($_POST['date']))
			{
				$retArr = array();
				$roomCount = 0;
				foreach ($_POST['roomList'] as $roomId)
				{
					// GENERATE THE 14-DAY AVAILABILITY DISPLAY

					// Init the array
					$availDays = array();
					for ($i = 0; $i < 14; $i++)
					{
						$availDays[date('Y-m-d', ($_POST['date'] + ($i * (60 * 60 * 24)) ))] = 1;
						// Eg '2013-04-16'=>'1'
					}
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$criteria->addCondition("room_id = " . $roomId);
					$criteria->addCondition("date >= '" . date('Y-m-d', $_POST['date']) . "'");
					$criteria->addCondition("date <= '" . date('Y-m-d', ($_POST['date'] + (13 * (60 * 60 * 24)) )) . "'");
					$days = Calendar::model()->findAll($criteria);
					foreach ($days as $day)
					{
						$availDays[$day->date] = 0;
						// Eg '2013-04-16'=>'0'
						$i++;
					}
					$arr = array();
					$i = 0;
					foreach ($availDays as $k => $v)
						$arr[$i++] = $v;

					// CHECK IF THIS ROOM IS AVAILABLE FOR THE ARRIVE-DEPART DATES (for the buttons)

					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$criteria->addCondition("room_id = " . $roomId);
					$criteria->addCondition("date >= '" . date('Y-m-d', $_POST['arrival']) . "'");
					$criteria->addCondition("date <= '" . date('Y-m-d', ($_POST['departure'] - (1 * (60 * 60 * 24)) )) . "'");
					$days = Calendar::model()->findAll($criteria);
					$roomIsAvailToBook = 1;
					foreach ($days as $day)
					{
						$roomIsAvailToBook = 0;
						break;
					}

					// Add this room, its datearray and booking-availability to the return array
					$roomStr = 'room_' . $roomCount;
					$retArr[$roomStr]['roomId'] = $roomId;
					$retArr[$roomStr]['bookAvail'] = $roomIsAvailToBook;
					$retArr[$roomStr]['dates'] = $arr;

					$roomCount++;
				}
				$retArr['numRooms'] = $roomCount;
				
				echo CJSON::encode($retArr);

/*				echo CJSON::encode(array(
					'numRooms' => '2',
					'room_1' => array(
						'roomId' => 23,
						'bookAvail' => 1,
						'dates' => array(1,1,1,1,1,1,1,1,1,1,1,1,1,1),
					}
					'room_2' => array(
						'roomId' => 17,
						'dates' => array(0,0,0,0,0,0,0,1,1,0,0,0,0,0)
					)
				)); */
			}

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