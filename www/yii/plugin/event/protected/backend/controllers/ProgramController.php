<?php

class ProgramController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	private $_thumbDir = '/../userdata/program/thumb/';
	private $_iconDir  = '/../userdata/program/icon/';

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
				'actions'=>array('create','update','privilege','admin','delete','export'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','export'),
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
		$model=new Program;
		$programFields = new ProgramFields;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Program']))
		{
			$model->attributes=$_POST['Program'];
			$model->thumb_path=CUploadedFile::getInstance($model, 'thumb_path');
			$model->icon_path=CUploadedFile::getInstance($model, 'icon_path');

			// Save linked tables (custom fields)
			$programFields->save();
			$model->event_program_fields_id = $programFields->id;

			if($model->save())
			{
				if (strlen($model->thumb_path) > 0)
				{
					$fname = Yii::app()->basePath . $this->_thumbDir . $model->thumb_path;
					$model->thumb_path->saveAs($fname);
					//$this->_watermark($fname);
				}
				if (strlen($model->icon_path) > 0)
				{
					$fname = Yii::app()->basePath . $this->_iconDir . $model->icon_path;
					$model->icon_path->saveAs($fname);
					//$this->_watermark($fname);
				}

				// Create the privilege link - creator is Admin
				$memberHasProgram = new MemberHasProgram;
				$memberHasProgram->event_member_id = Yii::app()->session['eid'];
				$memberHasProgram->event_program_id = $model->id;
// @@TODO: These privilege levels should be constants from the MemberHasProgram model
				$memberHasProgram->privilege_level = 2;
				$memberHasProgram->save();

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
		$model=$this->loadModel($id);
		$oldthumbname = $model->thumb_path;
		$oldiconname = $model->icon_path;

		$model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Program']))
		{
			$model->attributes=$_POST['Program'];

            $fileT=CUploadedFile::getInstance($model, 'thumb_path');
            if(is_object($fileT) && get_class($fileT) === 'CUploadedFile')
            {
	    		if (($oldthumbname != '') && (file_exists(Yii::app()->basePath . $this->_thumbDir . $oldthumbname)))
					unlink(Yii::app()->basePath . $this->_thumbDir . $oldthumbname);
                $model->thumb_path = $fileT;
            }

            $fileI=CUploadedFile::getInstance($model, 'icon_path');
            if(is_object($fileI) && get_class($fileI) === 'CUploadedFile')
            {
            	if (($oldiconname != '') && (file_exists(Yii::app()->basePath . $this->_iconDir . $oldiconname)))
					unlink(Yii::app()->basePath . $this->_iconDir . $oldiconname);
                $model->icon_path = $fileI;
            }
           
			if($model->save())
			{

                if(is_object($fileT))
                {
                    $fname = Yii::app()->basePath . $this->_thumbDir . $model->thumb_path;
                    $model->thumb_path->saveAs($fname);
                    //$this->_watermark($fname);
                }

                if(is_object($fileI))
                {
                    $fname = Yii::app()->basePath . $this->_iconDir . $model->icon_path;
                    $model->icon_path->saveAs($fname);
                    //$this->_watermark($fname);
                }
                
				$this->redirect(array('admin'));
			}

		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Assign member privileges
	 */
	public function actionPrivilege($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['privilege-entered']))
		{
			// Update privileges
			foreach ($_POST as $key => $val)
			{
				if (is_array($val))
					continue;
				if (substr($key, 0, 3) == 'od_')
				{
					//die ('id_' . substr($key, 3) );
					if (($_POST['id_' . substr($key, 3)]) != $val)
					{
						$criteria = new CDbCriteria;
						$criteria->addCondition("event_member_id = " . substr($key, 3));
						$criteria->addCondition("event_program_id = " . $id);
						$memberHasProgram = MemberHasProgram::model()->find($criteria);
						if (!($memberHasProgram))
							$memberHasProgram = new MemberHasProgram;
						$memberHasProgram->event_member_id = substr($key, 3);
						$memberHasProgram->event_program_id = $id;
						$memberHasProgram->privilege_level = $_POST['id_' . substr($key, 3)];
						$memberHasProgram->save();
					}
				}
			}
         
			$this->redirect(array('admin'));

		}

		$this->render('privilege',array(
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request

	        $oldthumbname = $this->loadModel($id)->thumb_path;
    	    if (($oldthumbname != '') && (file_exists(Yii::app()->basePath . $this->_thumbDir . $oldthumbname)))
           		unlink(Yii::app()->basePath . $this->_thumbDir . $oldthumbname);

	        $oldiconname = $this->loadModel($id)->icon_path;
    	    if (($oldiconname != '') && (file_exists(Yii::app()->basePath . $this->_iconDir . $oldiconname)))
           		unlink(Yii::app()->basePath . $this->_iconDir . $oldiconname);

			// Delete all privilege links for this program
			MemberHasProgram::model()->deleteAll('event_program_id=' . $id);

			$linked_id = $this->loadModel($id)->event_program_fields_id; 
			$this->loadModel($id)->delete();

			// Delete linked tables (custom fields)
			$programFields = ProgramFields::model()->findByPk($linked_id);
			$programFields->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Export all events for this program
	 */
	public function actionExport()
	{
		$cr = "<br>";
		$member=Member::model()->findByPk(Yii::app()->session['eid']);
        if ($member == null)
        	throw new CHttpException(500,'Cant export because there is no member record');
        if (trim($member->email_address) == '')
        	throw new CHttpException(500,'Please fill in your email address details before exporting');

		$fileName = '/tmp/Events_' . Yii::app()->session['eid'] . '.csv';
		$fp = fopen($fileName, 'w');
		if (!($fp))
			throw new CHttpException(500,'Cant create CSV export file' . $fileName);

		$standardHeading = array('id', 'title', 'start', 'end', 'address', 'post_code', 'web address', 'contact', 'decription', 'approved');
		$wsHeading = array('os_grid_ref', 'grade', 'booking_essential', 'min_age', 'max_age', 'child_ages_restrictions', 'additional_venue_info', 'full_price_notes', 'short_description', 'wheelchair_accessible');
		$heading = array_merge($standardHeading, $wsHeading);
		fputcsv($fp, $heading);
		
		$criteria = new CDbCriteria;
		$criteria->order = 'id ASC';
		$criteria->addCondition("program_id = 6");
		$events = Event::model()->findAll($criteria);

		foreach ($events as $event)
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("event_id = " . $event->id);
			$ws = Ws::model()->find($criteria);
			if ($ws)
			{
				//if (trim($ws->short_description) == '')
				//	$ws->short_description = substr($event->description, 0, 97) . "...";
			}
			else
			{
				Yii::log("Cant export because there is no wild-seasons matching event record. Event id = " . $event->id, CLogger::LEVEL_WARNING, 'system.test.kim');
				//throw new CHttpException(500,'Cant export because there is no wild-seasons matching event record. Event id = ' . $event->id);
			}

			// Standard fields
			$description = strip_tags($event->description);
			$standardLine = array($event->id, $event->title, $event->start, $event->end, $event->address, $event->post_code, $event->web, $event->contact, $description, $event->approved == 0 ? 'N' : 'Y');

			// Wild Seasons fields
			$wsLine = array();
			if ($ws)
			{
				$wheelchairAccess = 'N';
				$criteria = new CDbCriteria;
				$criteria->addCondition("event_facility_id = " . 12);	// @@NB: Hardcoded to pick up 'Wheelchair'
				$criteria->addCondition("event_event_id = " . $event->id);
				$eventFacility = EventHasFacility::model()->findAll($criteria);
				if ($eventFacility)
					$wheelchairAccess = 'Y';
				$wsLine = array($ws->os_grid_ref, $ws->grade, $ws->booking_essential == 0 ? 'N' : 'Y', $ws->min_age, $ws->max_age, $ws->child_ages_restrictions, $ws->additional_venue_info, $ws->full_price_notes, $ws->short_description, $wheelchairAccess);
			}

			$line = array_merge($standardLine, $wsLine);
			fputcsv($fp, $line);
		}

		// Send email to member
		$to = $member->email_address;
		$att_filename = $fileName;
		if (strlen($to) > 0)
		{
			$from = "admin@dglink.co.uk";
			$fromName = "Admin";
			$subject = "Database export";
			$message = "<b><u>All Events in CSV format, requested by " . $member->first_name . "</u></b>" . $cr;
			// phpmailer
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			$mail->AddAttachment($att_filename);
			$mail->Subject = $subject;
			$mail->MsgHTML($message);
			if (!$mail->Send())
				Yii::log("WEEKLY REPORT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
			else
				Yii::log("WEEKLY SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}
		$this->redirect(array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Program');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Program('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Program']))
			$model->attributes=$_GET['Program'];

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
		$model=Program::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='program-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

// @@ NOT USED
    public function actionAjaxUpdatePrivilege()
    {       
        if (Yii::app()->request->isAjaxRequest)
        {           
			if(isset($_POST['id']))
			{
				die('id='. $_POST['id']);
			}
		}
	}

}
