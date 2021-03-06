<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	private $_imageDir = '/../userdata/';	// Note this is only partial. Gets prepended base path and uid

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','delete','download','downloadFull','exportCSV','exportCSVFull','showReport', 'fullReport', 'remailConfirm', 'remailSend'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','download','downloadFull','exportCSV','exportCSVFull','showReport', 'fullReport', 'remailConfirm', 'remailSend'),
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
		$iDir = $this->getImageDir();
		$model=new Event;
		$model->uid = Yii::app()->session['uid'];
		$vId = $this->getVendorId();
		$model->ticket_vendor_id = $vId;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			$model->ticket_logo_path=CUploadedFile::getInstance($model, 'ticket_logo_path');
			// Initialise the ticket numbering
			$model->optional_next_ticket_number = $model->optional_start_ticket_number;
			if($model->save())
			{
				if (strlen($model->ticket_logo_path) > 0)
				{
					$fname = $iDir . $model->ticket_logo_path;
					$model->ticket_logo_path->saveAs($fname);
				}
				$this->redirect(array('admin'));
			}
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
		$iDir = $this->getImageDir();
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
//Yii::log("UPDATE START ", CLogger::LEVEL_WARNING, 'system.test.kim');
			$model->attributes=$_POST['Event'];
			$file=CUploadedFile::getInstance($model, 'ticket_logo_path');
			if (is_object($file) && get_class($file) === 'CUploadedFile')
			{
				// Delete old one
				if (strlen($model->ticket_logo_path) > 0)
				{
					if (file_exists($iDir . $model->ticket_logo_path))
						unlink($iDir . $model->ticket_logo_path);
				}
				// Save new one
				$model->ticket_logo_path = $file;
				$fname = $iDir . $model->ticket_logo_path;
				$model->ticket_logo_path->saveAs($fname);
			}
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$iDir = $this->getImageDir();
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$oldfilename = $this->loadModel($id)->ticket_logo_path;
			if (($oldfilename != '') && (file_exists($iDir . $oldfilename)))
				unlink($iDir . $oldfilename);
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Event');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$iDir = $this->getImageDir();
//Yii::log("ADMIN START. CREATING " . $iDir, CLogger::LEVEL_WARNING, 'system.test.kim');
		if ((!is_dir($iDir)) &&  (!mkdir($iDir, 0777, true)))
			throw new CHttpException(400,'Failed to create user directory ' . $iDir);
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Show the 'Download .CSV for this event' view
	 */
	public function actionDownload($id)
	{
		$model=$this->loadModel($id);
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('download',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Show the 'Download .CSV for this event' view
	 */
	//public function actionDownloadFull($fromD, $toD, $fromT, $toT)
	public function actionDownloadFull()
	{
//print_r($_GET);
//die;
		$this->render('downloadFull',array(
			'model'=>null,
			'fromD'=>$_GET['fromD'],
			'toD'=>$_GET['toD'],
			'fromT'=>$_GET['fromT'],
			'toT'=>$_GET['toT'],
		));
	}

	/**
	 * Export CSV report (called from the 'download' view)
	 */
	public function actionExportCSV($id)
	{
		$model=$this->loadModel($id);
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];
		$cr = "<br>";
		$heading = array('event', 'area', 'ticket_type', 'date', 'order_number', 'auth_code', 'name', 'email', 'telephone', 'address1', 'address2', 'address3', 'address4', 'city', 'county', 'post_code', 'price_each', 'sales_qty', 'sales_value');
		
		if (file_exists('/tmp/ticketVendorExport.csv'))
			unlink('/tmp/ticketVendorExport.csv');
		$fp2 = fopen('/tmp/ticketVendorExport.csv', 'w');
		fputcsv($fp2, $heading);
		$umsg = "";	
		$hasActiveEvent = false;
		$uVal = 0;

		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $id);
		$event = Event::model()->find($criteria);
		if ($event)
		{
			//if (!($event->active))
				//continue;

			// Pick up the vendor record
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $event->ticket_vendor_id);
			$vendor = Vendor::model()->find($criteria);
			if (!($vendor))
				throw new CHttpException(400,'Vendor record missing');
			if (trim($vendor->email) == "")
				throw new CHttpException(400,'Please set up an email address in your vendor record');

			$umsg .= "<i>" . $cr . $event->title . " : " . $event->date . "</i>" . $cr;
			$hasActiveEvent = true;
			$eQty = 0;
			$eVal = 0;

			$areas = $event->areas;
			foreach ($areas as $area)	// All ticket areas
			{
				$ticketTypes = $area->ticketTypes;
				foreach ($ticketTypes as $ticketType)	// All ticket types
				{
					$qty = 0;
					$val = 0;

					$criteria = new CDbCriteria;
					$criteria->addCondition("vendor_id = " . $vendor->id);
					$criteria->addCondition("event_id = " . $event->id);
					$criteria->addCondition("http_area_id = " . $area->id);
					$criteria->addCondition("http_ticket_type_id = " . $ticketType->id);
					$transactions = Transaction::model()->findAll($criteria);
					foreach ($transactions as $transaction)	// All event transactions for the event
					{

            			// Suppress values for non-paymentsense tickets
            			if (!($transaction->auth_code))
                			$transaction->http_ticket_total = 0;

						$criteria = new CDbCriteria;
						$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
						$auth = Auth::model()->find($criteria);
						$name = "";
						$a1 = "";
						$a2 = "";
						$a3 = "";
						$a4 = "";
						$city = "";
						$state = "";
						$pc = "";
						if ($auth != null)
						{
							$name = $auth->card_name;
							$a1 = $auth->address1;
							$a2 = $auth->address2;
							$a3 = $auth->address3;
							$a4 = $auth->address4;
							$city = $auth->city;
							$state = $auth->state;
							$pc = $auth->post_code;
						}

						$line = array($event->title, $area->description, $ticketType->description, $transaction->timestamp, $transaction->order_number, $transaction->auth_code, $name, $transaction->email, $transaction->telephone, $a1, $a2, $a3, $a4, $city, $state, $pc, sprintf("%01.2f", $transaction->http_ticket_price), $transaction->http_ticket_qty, sprintf("%01.2f", $transaction->http_ticket_total));
						fputcsv($fp2, $line);

						//if ($transaction->auth_code == NULL)
							//continue;	// We only want paymentsense sales on the report (not manual)

						$qty += $transaction->http_ticket_qty;
						$val += $transaction->http_ticket_total;
						$eQty += $transaction->http_ticket_qty;
						$eVal += $transaction->http_ticket_total;
						//if ($transaction->http_ticket_price != "0.00")
							//$uQty += $transaction->http_ticket_qty;
						$uVal += $transaction->http_ticket_total;
					}
				}
			}
		}
		fclose($fp2);
		if ($hasActiveEvent)
			Yii::app()->getRequest()->sendFile( "Export.csv" , file_get_contents( "/tmp/ticketVendorExport.csv" ) );
		$this->redirect(array('admin'));
	}

	/**
	 * Export CSV report (called from the menu)
	 */
	public function actionExportCSVFull()
	{
//print_r($_GET);
//die;
		$fChk = $_GET['fyear'] . $_GET['fmon'] . $_GET['fmday'];
		$tChk = $_GET['tyear'] . $_GET['tmon'] . $_GET['tmday'];

		// Pick up the vendor record
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$vendor = Vendor::model()->find($criteria);
		if (!($vendor))
			throw new CHttpException(400,'Vendor record missing for id ' . Yii::app()->session['uid']);
		if (trim($vendor->email) == "")
			throw new CHttpException(400,'Please set up an email address in your vendor record');

		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];
		$cr = "<br>";
		$heading = array('event', 'area', 'ticket_type', 'date', 'order_number', 'auth_code', 'name', 'email', 'telephone', 'address1', 'address2', 'address3', 'address4', 'city', 'county', 'post_code', 'price_each', 'sales_qty', 'sales_value');
		
		if (file_exists('/tmp/ticketVendorExportFull.csv'))
			unlink('/tmp/ticketVendorExportFull.csv');
		$fp2 = fopen('/tmp/ticketVendorExportFull.csv', 'w');
		fputcsv($fp2, $heading);
		$hasActiveEvent = false;

		$criteria = new CDbCriteria;
        $criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$transactions = Transaction::model()->findAll($criteria);
		foreach ($transactions as $transaction)	// All event transactions for the event
		{
			// Suppress values for non-paymentsense tickets
			if (!($transaction->auth_code))
				$transaction->http_ticket_total = 0;

			$date = $transaction->timestamp;
			$chk = sprintf("%04s%02s%02s", substr($date,0,4),substr($date,5,2),substr($date,8,2));
//echo "   >> " . $chk . " ? " . $fChk . " : " . $tChk . " <<   ";
			if (($chk < $fChk) || ($chk > $tChk))
				continue;

			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $transaction->event_id);
			$event = Event::model()->find($criteria);
			if ($event)
			{
				//if (!($event->active))
					//continue;

				$hasActiveEvent = true;

				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $transaction->http_area_id);
				$area = Area::model()->find($criteria);
				$areaDescription = "*Missing*";
				if ($area)
					$areaDescription = $area->description;

				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $transaction->http_ticket_type_id);
				$ticketType = TicketType::model()->find($criteria);
				$ticketTypeDescription = "*Missing*";
				if ($ticketType)
					$ticketTypeDescription = $ticketType->description;

				$criteria = new CDbCriteria;
				$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
				$auth = Auth::model()->find($criteria);
				$name = "";
				$a1 = "";
				$a2 = "";
				$a3 = "";
				$a4 = "";
				$city = "";
					$state = "";
				$pc = "";
				if ($auth != null)
				{
					$name = $auth->card_name;
					$a1 = $auth->address1;
					$a2 = $auth->address2;
					$a3 = $auth->address3;
					$a4 = $auth->address4;
					$city = $auth->city;
					$state = $auth->state;
					$pc = $auth->post_code;
				}
				$line = array($event->title, $areaDescription, $ticketTypeDescription, $transaction->timestamp, $transaction->order_number, $transaction->auth_code, $name, $transaction->email, $transaction->telephone, $a1, $a2, $a3, $a4, $city, $state, $pc, sprintf("%01.2f", $transaction->http_ticket_price), $transaction->http_ticket_qty, sprintf("%01.2f", $transaction->http_ticket_total));
				fputcsv($fp2, $line);
			}
		}
		fclose($fp2);
		if ($hasActiveEvent)
			Yii::app()->getRequest()->sendFile( "Export.csv" , file_get_contents( "/tmp/ticketVendorExportFull.csv" ) );
		$this->redirect(array('admin'));
	}

	/**
	 * Show report for event $id (called by above function's view)
	 */
	public function actionShowReport($id)
	{
		if(Yii::app()->request->isPostRequest) {
			if ((isset($_POST['eml'])) && (trim($_POST['eml']) != "") && (isset($_POST['uid'])) && (trim($_POST['uid']))) {
				$model=$this->loadModel($_POST['event']);
				$to = $_POST['eml'];
				$from = "no-reply@wireflydesign.com";
				$fromName = "Ticket reports";
				$subject = "Link to the report for " . $model->title;
				$linkPath = Yii::app()->basePath . "/../../tmp/ticketeventreport-" . Yii::app()->session['sid'] . "-" . $_POST['event'] . ".html";
				$linkUrl = "http://plugin.wireflydesign.com/tmp/ticketeventreport-" . Yii::app()->session['sid'] . "-" . $_POST['event'] . ".html";
				$htmlContent = "<html><head><meta name='viewport' content='initial-scale=1, maximum-scale=1'><meta http-equiv='refresh' content='0; url=http://plugin.wireflydesign.com/run/ticket/offlineDelegateReport?uid=" . $_POST['uid'] . "&event=" .  $_POST['event'] . "' /></head><body></body></html>";
				file_put_contents($linkPath, $htmlContent);
				$message = "<b>" . $model->title . "</b><br><br>Please click on the link below to access the report<br>This will be valid for 90 days<br><br><a href='" . $linkUrl . "'>Click here</a><br>";
				// phpmailer
				$mail = new PHPMailer();
				$mail->AddAddress($to);
				$mail->SetFrom($from, $fromName);
				$mail->AddReplyTo($from, $fromName);
				$mail->Subject = $subject;
				$mail->MsgHTML($message);
				if (!$mail->Send())
				{
					Yii::log("REPORT PAGE COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
					echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
				}
				else
					Yii::log("REPORT RESEND PAGE SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
				$this->redirect(array('admin'));
			} else if ((isset($_POST['android'])) && (trim($_POST['android']) != "") && (isset($_POST['uid'])) && (trim($_POST['uid']))) {
				$model=$this->loadModel($_POST['event']);
				$to = $_POST['android'];
				$from = "no-reply@wireflydesign.com";
				$fromName = "Ticket scanner app download";
				$subject = "Link to the android scanner app for " . $model->title;
				$linkUrl = "http://plugin.wireflydesign.com/ticket/app/scanner.html?appdata=" . Yii::app()->session['sid'] . "-" . $_POST['event'];
				$message = "<html><head><meta name='viewport' content='initial-scale=1, maximum-scale=1'></head><body><b>" . $model->title . "</b><br><br>Please click on the link below to go to the download area for the android app<br>This will be valid for 90 days<br><br><a href='" . $linkUrl . "'>Click here</a><br></body></html>";
				// phpmailer
				$mail = new PHPMailer();
				$mail->AddAddress($to);
				$mail->SetFrom($from, $fromName);
				$mail->AddReplyTo($from, $fromName);
				$mail->Subject = $subject;
				$mail->MsgHTML($message);
				if (!$mail->Send())
				{
					Yii::log("REPORT PAGE COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
					echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
				}
				else
					Yii::log("REPORT RESEND PAGE SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
				$this->redirect(array('admin'));
			}
		}
		$model=$this->loadModel($id);
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('report',array(
			'model'=>$model,
		));
	}

	/**
	 * Show report for all events
	 */
	public function actionFullReport()
	{
		$start = time() - (6 * 24 * 60 * 60);
		$end = time();

		if(Yii::app()->request->isPostRequest)
		{
			$start = strtotime($_POST['start']);
			$end = strtotime($_POST['end']);
		}
		$f = getdate($start);
		$t = getdate($end);
		if (strlen($f['mon']) == 1) $f['mon'] = "0" . $f['mon'];
		if (strlen($f['mday']) == 1) $f['mday'] = "0" . $f['mday'];
		if (strlen($t['mon']) == 1) $t['mon'] = "0" . $t['mon'];
		if (strlen($t['mday']) == 1) $t['mday'] = "0" . $t['mday'];

		$this->render('report',array(
			'model'=>null,
			'fromD'=>$f,
			'toD'=>$t,
			'fromT'=>$start,
			'toT'=>$end,
		));
	}

	/**
	 * Ask to Send email (remail)
	 */
	public function actionRemailConfirm($id, $name)
	{
		$this->renderPartial('remailconfirm',array(
			'id'=>$id,
			'name'=>$name,
		));
	}

	/**
	 * Send email (remail)
	 */
	public function actionRemailSend($id)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("id = " . $id);
		$transaction = Transaction::model()->find($criteria);

		$criteria = new CDbCriteria;
		$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
		$auth = Auth::model()->find($criteria);
		$name = "";
		if ($auth != null)
		{
			$name = $auth->card_name;
		}

		$to = $transaction->email;
		$pdf_filename = Yii::app()->basePath . '/../tkts/' . $transaction->order_number . '.pdf';
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "COPY of your tickets purchased at DG Link";
			$message = '<b>Thank you for using the DG Link to order your ticket(s).</b> <br> The attached PDF file contains your ticket(s) and card receipt. Please print all pages and bring them with you to your event or activity. The barcode on each ticket can only be used once.<br> If you ever need to reprint your tickets you may login to the site and do so from your account page. If you have forgotten your log in details you can request a password reminder.<br> We hope you enjoy your event.  --  The DG Link Team';
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			$mail->AddAttachment($pdf_filename);
//			if ($bcc != "")
//				$mail->AddBCC($bcc);
			$mail->Subject = $subject;
			$mail->MsgHTML($message);
			if (!$mail->Send())
			{
				Yii::log("RESEND PAGE COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
				echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
			}
			else
				Yii::log("RESEND PAGE SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}

		$this->renderPartial('remailconfirm',array(
			'id'=>'sent',
		));
	}

	public function getImageDir()
	{
		return Yii::app()->basePath . $this->_imageDir . Yii::app()->session['uid'] . '/';
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Event::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Return the one-and-only Vendor id answering to session variable 'uid'
	 */
	public function getVendorId()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$vendor = Vendor::model()->find($criteria);
		if (($vendor === null) || ($vendor->id === null))
			throw new CHttpException(400,'Invalid Vendor Id. Have you set up your vendor details yet?');
		return $vendor->id;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='event-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

// Redactor image handling -----------------------------------------------------

    public function actionImageUpload()
    {
        $uploadedFile = CUploadedFile::getInstanceByName('file');
        if (!empty($uploadedFile)) {
            $rnd = rand();  // generate random number between 0-9999
            $fileName = "{$rnd}.{$uploadedFile->extensionName}";  // random number + file name
            if ($uploadedFile->saveAs(Yii::app()->basePath . '/../userdata/image/' . $fileName)) {

                $array = array(
                     'filelink' => Yii::app()->baseUrl . '/userdata/image/' . $fileName);
               // echo CHtml::image(Yii::app()->baseUrl . '/userdata/image/' . $fileName);

                echo stripslashes(json_encode($array));
                Yii::app()->end();
            }
        }
        throw new CHttpException(400, 'The request cannot be fulfilled due to bad syntax');
    }

// "ListImages" (used to browse images in the server)

    public function actionImageList() {

        $images = array();
        $handler = opendir(Yii::app()->basePath . '/../userdata/image');
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..")
                $images[] = $file;
        }
        closedir($handler);

        $jsonArray = array();

        foreach ($images as $image)
            $jsonArray[] = array(
                'thumb' => Yii::app()->baseUrl . '/userdata/image/' . $image,
                'image' => Yii::app()->baseUrl . '/userdata/image/' . $image,
            );

        header('Content-type: application/json');
        echo CJSON::encode($jsonArray);
    }	
	
	
}
