<?php

// PHPMailer
require_once('php/PHPMailer/class.phpmailer.php');

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

		$model=new Customer;
		$model->uid = Yii::app()->session['uid'];
        if(isset($_POST['Customer']))
        {
        	// Create customer record
        	$model->ref = date('U');
			$model->reservation_total = Yii::app()->session['bTotal'];
            $model->attributes=$_POST['Customer'];
            $model->coupon_code = Yii::app()->session['cCode'];
            $model->coupon_description = Yii::app()->session['cDescription'];
            $model->coupon_type = Yii::app()->session['cType'];
            $model->coupon_value = Yii::app()->session['cValue'];
            if($model->save())
            {
            	$msgRoom = "";
            	for ($roomIx = 0; $roomIx < 3; $roomIx++)
            	{
            		if (Yii::app()->session['room_' . ($roomIx+1) . '_selection'] == '0')
            			continue;
					$roomId = Yii::app()->session['room_' . ($roomIx+1) . '_selection'];

					// Pick up the room record for title and description in email
					$criteria = new CDbCriteria;
					$criteria->addCondition("id = " . $roomId);
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$modelRoom=Room::model()->find($criteria);
					$msgRoom .= "<b>" . $modelRoom->title . "</b><br>";

	            	// Create reservation_room record(s)
					$modelResRm=new ReservationRoom;
					$modelResRm->uid = Yii::app()->session['uid'];
					$modelResRm->ref = $model->ref;
					$dat = Yii::app()->session['arrivedate'];
					$dt = substr($dat,6,4) .'-'. substr($dat,3,2) .'-'. substr($dat,0,2);
					$modelResRm->start_date = $dt;
					$dat = Yii::app()->session['departdate'];
					$dt = substr($dat,6,4) .'-'. substr($dat,3,2) .'-'. substr($dat,0,2);
					$modelResRm->end_date = $dt;
					$modelResRm->num_nights = Yii::app()->session['nights'];
					$modelResRm->num_adult = Yii::app()->session['numAdults_' . ($roomIx+1)];
					$modelResRm->num_child = Yii::app()->session['numChildren_' . ($roomIx+1)];
					$modelResRm->room_total = Yii::app()->session['bRoomTotal_' . ($roomIx+1)];
					$modelResRm->occupancy_type_id = Yii::app()->session['occupancyType_' . ($roomIx+1)];
					$criteria = new CDbCriteria;
					$criteria->addCondition("id = " . $modelResRm->occupancy_type_id);
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$occupancyType=OccupancyType::model()->find($criteria);
					$modelResRm->occupancy_type_description = $occupancyType->description;
					$modelResRm->room_id = $roomId;
					$modelResRm->save();

					$msgRoom .= $modelResRm->num_adult . " adults and " . $modelResRm->num_child . " children<br>";
					$msgRoom .= $modelResRm->occupancy_type_description . "<br>";

					// Potentially create reservation extra record(s) for this room
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$criteria->addCondition("room_id = " . $roomId);
					$roomHasExtras=RoomHasExtra::model()->findAll($criteria);
					foreach ($roomHasExtras as $roomHasExtra)
					{
						$criteria = new CDbCriteria;
						$criteria->addCondition("uid = " . Yii::app()->session['uid']);
						$criteria->addCondition("id = " . $roomHasExtra->extra_id);
						$extra=Extra::model()->find($criteria);
						$thisExtra = 'bExtra_roomid_' . $roomId . '_extraid_' . $extra->id;
						if (Yii::app()->session[$thisExtra] != '0')
						{
							// Create the extra record
							$modelResEx=new ReservationExtra;
							$modelResEx->uid = Yii::app()->session['uid'];
							$modelResEx->ref = $model->ref;	
							$modelResEx->extra_id = $extra->id;
							$modelResEx->extra_description = $extra->description;
							$modelResEx->extra_total = Yii::app()->session[$thisExtra];
							$modelResEx->reservation_room_id = $modelResRm->id;
							$modelResEx->reservation_room_room_id = $modelResRm->room_id;
							$modelResEx->save();
							
							$msgRoom .= $modelResEx->extra_description . " included<br>";
						}
					}

					$msgRoom .= "<hr><br>";
					
					// Now block off the calendar for this room
					$dat = Yii::app()->session['arrivedate'];
					$dt = substr($dat,6,4) .'-'. substr($dat,3,2) .'-'. substr($dat,0,2);
					for ($cal = 0; $cal < Yii::app()->session['nights']; $cal++)
					{
						$modelCalendar=new Calendar;
						$modelCalendar->uid = Yii::app()->session['uid'];	
						$modelCalendar->ref = $model->ref;
						$modelCalendar->room_id = $modelResRm->room_id;
						$modelCalendar->date = $dt;
						$modelCalendar->save();
						$tmpdt = strtotime("+1 day", strtotime($dt));
						$dt = date("Y-m-d", $tmpdt);
					}
				} /* next room */

				// Send email
				$from = Yii::app()->session['uid_email'];
				$fromName = Yii::app()->session['uid_name'];
				$to = $model->email;
				$subject = "Your Reservation";
				$msg  = "<b> Thank you for your booking with us.</b><br><br>";
				$msg .= "Arriving " . Yii::app()->session['arrivedate'] . " and departing " . Yii::app()->session['departdate'] . "<br><br>";
				$msg .= $msgRoom;
				$msg .= "<br><b>Booking total : £ " . $model->reservation_total . "</b><br>";

//Yii::log($msg , CLogger::LEVEL_WARNING, 'system.test.kim');

				//$pdf_filename = '/tmp/' . $order->order_number . '.pdf';
				// phpmailer
				$mail = new PHPMailer();
				$mail->AddAddress($to);
				$mail->SetFrom($from, $fromName);
				$mail->AddReplyTo($from, $fromName);
				//$mail->AddAttachment($pdf_filename);
				$mail->Subject = $subject;
				$mail->CharSet = 'UTF-8';
				$mail->MsgHTML($msg);
				// Pick up params to find out if we must bcc ourselves
				$criteria = new CDbCriteria;
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$criteria->addCondition("id = " . 1);
				$param=Param::model()->find($criteria);
				if ($param)
				{
					Yii::log("MAIL picked up param " . $param->cc_email_address, CLogger::LEVEL_WARNING, 'system.test.kim'); 
					$pos = strpos($param->cc_email_address, "@");
					Yii::log("MAIL param email has a @" . $param->cc_email_address, CLogger::LEVEL_WARNING, 'system.test.kim'); 
					if ($pos !== false)
					{
						$mail->AddBCC($param->cc_email_address);   
						Yii::log("MAIL added bcc " . $param->cc_email_address, CLogger::LEVEL_WARNING, 'system.test.kim');  
					}
				}
				// Send
				if (!$mail->Send())
				{
					Yii::log("COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
					echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
				}
				else
					Yii::log("SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');

            	// Add an artificial pause in lieue of actually processing payment :/
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