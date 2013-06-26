<?php

// PHPMailer
require_once('php/PHPMailer/class.phpmailer.php');

class CustomerController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2cancelreason';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','show'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','show','delete','deposit'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','show','deposit'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionShow($ref, $sid)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// Cancellation button doubles as a form submit here
			Yii::log("CANCELLATION ID: " . $_POST['id'], CLogger::LEVEL_WARNING, 'system.test.kim');
			Yii::log("CANCELLATION REASON: " . $_POST['cancelreason'], CLogger::LEVEL_WARNING, 'system.test.kim');
			$model = $this->loadModel($_POST['id']);
			$ref = $model->ref;

			// Delete this ref from the booking calendar
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("ref = " . $ref);
			Calendar::model()->deleteAll($criteria);

			// Get this ref from the booking room(s) for dates - any one will do
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("ref = " . $ref);
			$reservationRoom = ReservationRoom::model()->Find($criteria);

			// Update the customer record with cancellation details
			$model->cancel_flag = true;
			$model->cancel_reason = $_POST['cancelreason'];
			$model->save();

			// Pick up params
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			Yii::log("MAIL Going to try pick up param " , CLogger::LEVEL_WARNING, 'system.test.kim'); 
			$param=Param::model()->find($criteria);

			// Send email
			$from = Yii::app()->session['uid_email'];
			$fromName = Yii::app()->session['uid_name'];
			$to = $model->email;
			$subject = "Reservation Cancelled";
			$msg  = "<b> This is confirmation that your reservation for the following dates has been cancelled.</b><br><br/>";
			$msg .= $reservationRoom->start_date . " to " . $reservationRoom->start_date . "<br><br>";
			$msg .= "Reason: " . $_POST['cancelreason'] . "<br><br>";
			Yii::log("SENDING CANCELLATION MAIL: " . $msg, CLogger::LEVEL_WARNING, 'system.test.kim');


			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			//$mail->AddAttachment($pdf_filename);
			$mail->Subject = $subject;
			$mail->CharSet = 'UTF-8';
			$mail->MsgHTML($msg);

			if ($param)
			{
				$mail->SetFrom($param->sender_email_address, $param->sender_name);
				$mail->AddReplyTo($param->sender_email_address, $param->sender_name);
				$pos = strpos($param->cc_email_address, "@");
				if ($pos !== false)
				{
					$mail->AddBCC($param->cc_email_address);   
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


			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			// @@EG: Redirect from one controller/action to a different controller
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('site/calendar'));
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->addCondition("ref = " . $ref);
		$model=Customer::model()->find($criteria);
		$this->render('show',array(
			'model'=>$model,
			'sid'=>$sid,
			'ref'=>$ref,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Customer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deposit taken on a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionDeposit($id)
	{
		$model=$this->loadModel($id);
		$deposit_amount = 0;
Yii::log("DEPOSIT ID: " . $id, CLogger::LEVEL_WARNING, 'system.test.kim');
Yii::log("DEPOSIT AMT INITIALLY: " . $deposit_amount,  CLogger::LEVEL_WARNING, 'system.test.kim');

		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$param=Param::model()->find($criteria);
		if (($param) && ($param->deposit_percent > 0))
			$deposit_amount = ($model->reservation_total  * $param->deposit_percent / 100);
Yii::log("DEPOSIT AMT FINALLY: " . $deposit_amount,  CLogger::LEVEL_WARNING, 'system.test.kim');

		$model->deposit_taken = $deposit_amount;
Yii::log("DEPOSIT AMT IN MODEL: " . $model->deposit_taken,  CLogger::LEVEL_WARNING, 'system.test.kim');
		$model->save(false);	// @@EG: 'false' means save even if a validation rule fails
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('site/calendar'));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Customer');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Customer('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Customer']))
			$model->attributes=$_GET['Customer'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Customer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
