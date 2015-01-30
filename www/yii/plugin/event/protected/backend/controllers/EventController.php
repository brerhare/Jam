<?php

class EventController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

	public $createPrograms = 0;
	public $updateAsAdmin = 0;

	public $layout='//layouts/columnEvent';
	//public $layout='//layouts/column2';

    private $_thumbDir = '/../userdata/event/thumb/';   // Note this is only partial. Gets prepended base path

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
		$this->setDefaultProgram();	// @@TODO this being here is a kludge. Should be in a constructor

		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','delete','clone','imageUpload','imageList'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','clone','imageUpload','imageList'),
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

/*****
				echo 'Doing... ';
				$criteria = new CDbCriteria;
				$members=Member::model()->findAll($criteria);
				$irec = 0; $orec = 0; $drec = 0;
				foreach ($members as $member)
				{
					if ($member->lock_program_id != 6)
						continue;
					$irec++;

					$criteria = new CDbCriteria;
					$criteria->addCondition("event_member_id = $member->id");
					$criteria->addConditio("event_program_id = 6");
					$x=MemberHasProgram::model()->find($criteria);
					if (!($x))
					{
						$memberHasProgram = new MemberHasProgram;
						$memberHasProgram->event_member_id = $member->id;
						$memberHasProgram->event_program_id = 6;
						$memberHasProgram->privilege_level = 1;
						$memberHasProgram->save();
						$orec++;
					}
				}
				echo 'done : ' . $irec . ' : ' . $orec . ' : ' . $drec;
				die;
*****/

        Yii::log("CREATE EVENT ----- start " , CLogger::LEVEL_WARNING, 'system.test.kim');
        $iDir = $this->getThumbDir();
        $model=new Event;
		$model->active = 1;
$model->program_id = Yii::app()->session['pid'];	// @TODO This is filled simply because cant be null. Not used, in favour of EventHasProgram
													// @TODO Both the program_id field and 'pid' session var can go
		if ($this->creatingWildSeasons())
		{
        	$model2=new Ws;
        	$model2->booking_essential = 1;
		}
        $model->member_id = Yii::app()->session['eid'];
        $model->ticket_event_id = 1;	// Default to 'yes'

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Event']))
        {
        	Yii::log("CREATE EVENT ----- POST happened " , CLogger::LEVEL_WARNING, 'system.test.kim');
            $model->attributes=$_POST['Event'];
            $model->thumb_path=CUploadedFile::getInstance($model, 'thumb_path');
			if ($this->creatingWildSeasons())
				$model2->attributes=$_POST['Ws'];
			//$model2->event_id = $model->id;

            if($model->save())
            {
        		Yii::log("CREATE EVENT ----- successful model->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
            	$this->deleteProductCheckboxes($model->id);
                $this->updateProductCheckboxes($model->id);

            	$modelId = $model->id;	// Store for later
				if ($this->creatingWildSeasons())
				{
            		// Save Ws fields too
            		$model2->attributes=$_POST['Ws'];
            		$model2->event_id = $model->id;
            		if (!($model2->save()))
            		{
        				Yii::log("CREATE EVENT ----- UNsuccessful model2->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
	            		$this->deleteProductCheckboxes($model->id);
            			$model->delete();
            			$this->render('create',array(
            				'model'=>$model,
            				'model2'=>$model2,
            				'ticketUid'=>$this->getTicketUidFromEventSid(),	// Either a valid uid or -1
        				));
        				return;
            		}
				}
        		Yii::log("CREATE EVENT ----- successful model2->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
                if (strlen($model->thumb_path) > 0)
                {
                    $fname = $iDir . $model->thumb_path;
                    $model->thumb_path->saveAs($fname);
                }

	            // Do we need to create a ticket event too?
	            // @@EG Check softlink name for model, this is our standard naming
	            if ($model->ticket_event_id == 1)
	            {
	            	$ticketUid = $this->getTicketUidFromEventSid();
	            	if ($ticketUid != -1)
	            	{
		            	// Pick up the ticket vendor, its id is needed for the ticket event
    		        	$criteria = new CDbCriteria;
						$criteria->condition="uid = " . $ticketUid;
        				$ticketVendor = Vendor::model()->find($criteria);
	        			if ($ticketVendor != null)
	        			{
			            	// Create ticket event record
			            	$ticketEvent = new ticket_Event;
    			        	$ticketEvent->uid = $ticketUid;
        			    	$ticketEvent->title = $model->title;
            				$ticketEvent->date = substr($model->start, 0, 10);  // 0000-00-00 00:00;00
            				$ticketEvent->time = substr($model->start, 11, 8);
		            		$ticketEvent->address = $model->address;
	    		        	$ticketEvent->post_code = $model->post_code;
    	    		    	$ticketEvent->active = 0;
        	    			$ticketEvent->ticket_vendor_id = $ticketVendor->id;
	        	    		if (!($ticketEvent->save()))
    	        				throw new CHttpException(500,'Couldnt write ticket event record');
    		            	// Update the Event model with the ticket reference
	            			$model=$this->loadModel($modelId);
	            			$model->ticket_event_id = $ticketEvent->id;
			            	$model->save();
	        	    	}
	            	}
	            }
				// Now add key records for each program this event wants to appear on
				$ckArr = explode('|', Yii::app()->request->cookies['createEventPrograms']->value);
				foreach ($ckArr as $ckItem) {
					$eventHasProgram = new EventHasProgram;
					$eventHasProgram->program_id = $ckItem;
					$eventHasProgram->event_event_id = $model->id;
					$eventHasProgram->approved = 0;	// This might be changed by handleNewEventPermissions() a couple of lines down...
					$eventHasProgram->save();
					$this->handleNewEventPermissions($model->id, $ckItem);
				}
        		Yii::log("CREATE EVENT ----- end of event records for create " , CLogger::LEVEL_WARNING, 'system.test.kim');
                $this->redirect(array('admin'));
           	}
        }

		if (!$this->creatingWildSeasons())
			$model2 = '';

        $this->render('create',array(
            'model'=>$model,
            'model2'=>$model2,
            'ticketUid'=>$this->getTicketUidFromEventSid(),	// Either a valid uid or -1
        ));
	}

	/**
	 * Clones a particular model.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionClone($id)
	{
        Yii::log("Clone EVENT ----- start. id = " . $id , CLogger::LEVEL_WARNING, 'system.test.kim');
        $iDir = $this->getThumbDir();
        $model=$this->loadModel($id);
		if ($model)
		{
			$originalId = $model->id;

			// Insert event record
			unset($model->id);
			$model->title = $model->title . " (copy)";
			// Duplicate the thumbnail
			if (strlen(trim($model->thumb_path)) > 0)
			{
				$newThumbPath = $model->thumb_path . "_" . rand(1, 99999);
				copy($iDir . $model->thumb_path, $iDir . $newThumbPath);
				$model->thumb_path = $newThumbPath;
			}
			$model->ticket_event_id = 0;
			$model->isNewRecord = true;

			if ($model->insert())
			{

				// Copy program inclusions
				$criteria = new CDbCriteria;
				$criteria->condition="event_event_id = $originalId";
				$eventHasPrograms=EventHasProgram::model()->findAll($criteria);
				foreach ($eventHasPrograms as $eventHasProgram)
				{
					$data = new EventHasProgram;
					$data->event_event_id = $model->id;
					$data->program_id = $eventHasProgram->program_id;
					$data->approved = 0;
					$data->save();
				}
			}
			else
				throw new CHttpException(500,'Could not save new model() in clone');

			if ($this->isWildSeasons($id))
			{
				$criteria = new CDbCriteria;
				$criteria->condition="event_id = $originalId";
        		$model2=Ws::model()->find($criteria);
        		if ($model2)
				{
					// Insert ws record
					unset($model2->id);
            		$model2->event_id = $model->id;
					$model2->isNewRecord = true;
					if (!($model2->insert()))
					{
						$model->delete();
						die("Internal error copying event (ws record)");
					}
					// Copy interests
					$criteria = new CDbCriteria;
					$criteria->condition="event_event_id = $originalId";
        			$interests=EventHasInterest::model()->findAll($criteria);
        			foreach ($interests as $interest)
					{
						$data = new EventHasInterest;
						$data->event_event_id = $model->id;
						$data->event_interest_id = $interest->event_interest_id;
						$data->save();
					}
					// Copy formats
					$criteria = new CDbCriteria;
					$criteria->condition="event_event_id = $originalId";
        			$formats=EventHasFormat::model()->findAll($criteria);
        			foreach ($formats as $format)
					{
						$data = new EventHasFormat;
						$data->event_event_id = $model->id;
						$data->event_format_id = $format->event_format_id;
						$data->save();
					}
					// Copy facilities
					$criteria = new CDbCriteria;
					$criteria->condition="event_event_id = $originalId";
        			$facilities=EventHasFacility::model()->findAll($criteria);
        			foreach ($facilities as $facility)
					{
						$data = new EventHasFacility;
						$data->event_event_id = $model->id;
						$data->event_facility_id = $facility->event_facility_id;
						$data->save();
					}
				}
			}	// end of if insert && ws
		}
		$this->redirect(array('admin'));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$updateMode = "update";
        if(isset($_GET['updateMode']))
			$updateMode = $_GET['updateMode'];	// "view" - called by ProgramController approvals to view an event

        Yii::log("UPDATE EVENT ----- start. id = " . $id , CLogger::LEVEL_WARNING, 'system.test.kim');
        $iDir = $this->getThumbDir();
        $model=$this->loadModel($id);

		if ($this->isWildSeasons($id))
		{
			$criteria = new CDbCriteria;
			$criteria->condition="event_id = $model->id";
        	$model2=Ws::model()->find($criteria);
        	if (!($model2))
        	{
        		Yii::log("UPDATE EVENT ----- loading up. id = " . $id . " no model2. Creating one " , CLogger::LEVEL_WARNING, 'system.test.kim');
        		//die('Couldnt find event matching Ws record for update for event id ' . $id . ' Please report this error');
				$model2 = new Ws;
				$model2->event_id = $model->id;
				$model2->os_grid_ref = 'none';
				$model2->grade = 'Easy';
				$model2->save();
        	}
		}

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Event']))
        {
        	Yii::log("UPDATE EVENT ----- post happened " , CLogger::LEVEL_WARNING, 'system.test.kim');
            $model->attributes=$_POST['Event'];
            $file=CUploadedFile::getInstance($model, 'thumb_path');
            if (is_object($file) && get_class($file) === 'CUploadedFile')
            {
                // Delete old one
                if (strlen($model->thumb_path) > 0)
                {
                    if (file_exists($iDir . $model->thumb_path))
                        unlink($iDir . $model->thumb_path);
                }
                // Save new one
                $model->thumb_path = $file;
                $fname = $iDir . $model->thumb_path;
                $model->thumb_path->saveAs($fname);
            }
            Yii::log("UPDATE EVENT ----- about to do model->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
            if($model->save())
            {
            	Yii::log("UPDATE EVENT ----- successful model->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
            	$this->deleteProductCheckboxes($model->id);
                $this->updateProductCheckboxes($model->id);

            	$modelId = $model->id;	// Store for later

				if ($this->isWildSeasons($id))
				{
            		// Save Ws fields too
            		$model2->attributes=$_POST['Ws'];
            		$model2->event_id = $model->id;
            		Yii::log("UPDATE EVENT ----- about to do model2->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
            		$model2->save();
            		Yii::log("UPDATE EVENT ----- did model2->save(). Finished " , CLogger::LEVEL_WARNING, 'system.test.kim');
				}

	            // Do we need to create a ticket event too?
	            // @@EG Check softlink name for model, this is our standard naming
	            if ($model->ticket_event_id == 1)
	            {
	            	$ticketUid = $this->getTicketUidFromEventSid();
	            	if ($ticketUid != -1)
	            	{
		            	// Pick up the ticket vendor, its id is needed for the ticket event
    		        	$criteria = new CDbCriteria;
						$criteria->condition="uid = " . $ticketUid;
        				$ticketVendor = Vendor::model()->find($criteria);
	        			if ($ticketVendor != null)
	        			{
			            	// Create ticket event record
			            	$ticketEvent = new ticket_Event;
    			        	$ticketEvent->uid = $ticketUid;
        			    	$ticketEvent->title = $model->title;
            				$ticketEvent->date = substr($model->start, 0, 10);  // 0000-00-00 00:00;00
            				$ticketEvent->time = substr($model->start, 11, 8);
		            		$ticketEvent->address = $model->address;
	    		        	$ticketEvent->post_code = $model->post_code;
    	    		    	$ticketEvent->active = 0;
        	    			$ticketEvent->ticket_vendor_id = $ticketVendor->id;
	        	    		if ($ticketEvent->save())
							{
    	        				//	throw new CHttpException(500,'Couldnt write ticket event record');
    		            		// Update the Event model with the ticket reference
	            				$model=$this->loadModel($modelId);
	            				$model->ticket_event_id = $ticketEvent->id;
			            		$model->save();
							}
	        	    	}
	            	}
	            }

				// -FROM- the form
				// Using the 'approved' DUMMY field in the event from form updating
				// Update approved for this event in any if its programs we are admin on
				$criteria = new CDbCriteria;
				$criteria->addCondition("event_event_id = $id");
				$eventHasPrograms=EventHasProgram::model()->findAll($criteria);
				foreach ($eventHasPrograms as $eventHasProgram)
				{
					$criteria = new CDbCriteria;
					$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
					$criteria->addCondition("event_program_id = $eventHasProgram->program_id");
					$memberHasProgram=MemberHasProgram::model()->find($criteria);
					if (($memberHasProgram) && ($memberHasProgram->privilege_level == 2))
					{
						$eventHasProgram->approved = $model->approved;
						$eventHasProgram->save();
					}
				}

                $this->redirect(array('admin'));
            }
        }

		if (!$this->isWildSeasons($id))
			$model2 = '';

		// -FOR- the form
		// Using the 'approved' DUMMY field in the event for form updating
		// See if we are admin any of the programs this event is part of. Should all be the same so just use the 1st one for approvals 0/1
		$model->approved = 0;	// Start with 'not approved'
		$criteria = new CDbCriteria;
		$criteria->addCondition("event_event_id = $id");
		$eventHasPrograms=EventHasProgram::model()->findAll($criteria);
		foreach ($eventHasPrograms as $eventHasProgram)
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
			$criteria->addCondition("event_program_id = $eventHasProgram->program_id");
			$memberHasProgram=MemberHasProgram::model()->find($criteria);
			if (($memberHasProgram) && ($memberHasProgram->privilege_level == 2))
			{
				$model->approved = $eventHasProgram->approved;
				$this->updateAsAdmin = 1;
			}
		}

        $this->render('update',array(
            'model'=>$model,
            'model2'=>$model2,
            'updateMode'=>$updateMode,
            'ticketUid'=>$this->getTicketUidFromEventSid(),	// Either a valid uid or -1
        ));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        Yii::log("DELETE EVENT ----- start " . CLogger::LEVEL_WARNING, 'system.test.kim');
		$iDir = $this->getThumbDir();
		if(Yii::app()->request->isPostRequest)
		{
			Yii::log("DELETE EVENT ----- POST received " , CLogger::LEVEL_WARNING, 'system.test.kim');
			// we only allow deletion via POST request
			$model = $this->loadModel($id);

            if (strlen($model->thumb_path) > 0)
            {
                if (file_exists($iDir . $model->thumb_path))
                    unlink($iDir . $model->thumb_path);
            }
			Yii::log("DELETE EVENT ----- about to delete productcheckboxes " , CLogger::LEVEL_WARNING, 'system.test.kim');

			// Delete related event checkbox records
            $this->deleteProductCheckboxes($model->id);

			if ($this->isWildSeasons($id))
			{
				$criteria = new CDbCriteria;
				$criteria->condition="event_id = $id";
   				$model2=Ws::model()->find($criteria);
    	    	if (!($model2))
    	    		Yii::log("DELETE EVENT ----- NO MATCHINV WS RECORD TO DELETE (ignoring this error)" , CLogger::LEVEL_WARNING, 'system.test.kim');
    	    	else
    	    	{
	        		$model2->delete();
	        		Yii::log("DELETE EVENT ----- deleteing matching WS record " , CLogger::LEVEL_WARNING, 'system.test.kim');
    	    	}
			}

			// Delete program inclusion records
			$criteria = new CDbCriteria;
			$criteria->condition="event_event_id = $id";
			$eventHasPrograms=EventHasProgram::model()->findAll($criteria);
			foreach ($eventHasPrograms as $eventHasProgram)
				$eventHasProgram->delete();

           	Yii::log("DELETE EVENT ----- about to delete model() " . CLogger::LEVEL_WARNING, 'system.test.kim');
			$model->delete();

			Yii::log("DELETE EVENT ----- finished " , CLogger::LEVEL_WARNING, 'system.test.kim');

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
		$this->createPrograms = 1;
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	 /* Returns the data model based on the primary key given in the GET variable.
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

	// Check whether we are creating a wild seasons program (true or false)
	protected function creatingWildSeasons()
	{
		// eg 6 or 6|13 or 6|13|52 etc
		if (Yii::app()->request->cookies['createEventPrograms'])
			return in_array('6',  explode('|', Yii::app()->request->cookies['createEventPrograms']->value));
		else
			return false;
	}

	// Check whether we are updating/deleting/cloning a wild seasons program (true or false)
	protected function isWildSeasons($id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="event_event_id = $id";
		$eventHasPrograms=EventHasProgram::model()->findAll($criteria);
		foreach ($eventHasPrograms as $eventHasProgram)
		{
			if ($eventHasProgram->program_id == 6)	// WS
				return true;
		}
		return false;
	}

	// Set the default (lock) program for this member
	protected function setDefaultProgram()
	{
		$member=Member::model()->findByPk(Yii::app()->session['eid']);
		Yii::app()->session['pid'] = 0;
		if ($member != null)
			Yii::app()->session['pid'] = $member->lock_program_id;
		else
			throw new CHttpException(400, 'The request cannot be fulfilled. Please logout and login again');
	}

	// New event - autoapprove or notify needs approval
	protected function handleNewEventPermissions($eventId, $programId)
	{
		// Get the members permissions for this program
		$criteria = new CDbCriteria;
		$criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
		$criteria->addCondition("event_program_id = " . $programId);
		$memberHasProgram = MemberHasProgram::model()->find($criteria);
		if (!($memberHasProgram))
		{
			// Create a no-privilege level 'joining' entry for this program if this is their 1st event for it
			$memberHasProgram = new MemberHasProgram;
			$memberHasProgram->event_member_id = Yii::app()->session['eid'];
			$memberHasProgram->event_program_id = $programId;
			$memberHasProgram->privilege_level = 0;
			$memberHasProgram->save();
		}
		if ($memberHasProgram->privilege_level > 0)
		{
			// Is trusted or admin - set approved
			$criteria = new CDbCriteria;
			$criteria->condition="event_event_id = $eventId";
			$criteria->condition="program_id = $programId";
			$eventHasProgram = EventHasProgram::model()->find($criteria);
			if ($eventHasProgram)
			{
				$eventHasProgram->approved = 1;
				$eventHasProgram->save();
			}
			else
				throw new CHttpException(400, 'Missing event-program for this event & program so unable to approve it for public visibility');
			return;
		}

/*
		// Unprivileged or new member to this program - send permission-request email to admin(s)
		$criteria = new CDbCriteria;
		$criteria->addCondition("event_member_id != " . Yii::app()->session['eid']);
		$criteria->addCondition("event_program_id = " . $programId);
		$criteria->addCondition("privilege_level = 2");	//@@TODO Privilege hardcoded
		$memberHasPrograms = MemberHasProgram::model()->findAll($criteria);
		foreach ($memberHasProgram as $memberHasProgram)
		{
			// phpmailer
			$member=Member::model()->findByPk($memberHasProgram->event_member_id);
			if ($member == null)
			{
				Yii::log("REQUEST TO POST EVENT COULD NOT RETRIEVE ADMIN MEMBER RECORD" . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
				return;
			}
			$mail = new PHPMailer();
			$mail->AddAddress($to);
			$mail->SetFrom($from, $fromName);
			$mail->AddReplyTo($from, $fromName);
			$mail->Subject = $subject;
			$mail->MsgHTML($message);
			if (!$mail->Send())
				Yii::log("REQUEST TO POST EVENT COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
			else
				Yii::log("REQUEST TO POST EVENT SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');
		}
*/

	}

	// Check whether this member has a valid SID on their member profile
	public function getTicketUidFromEventSid()
	{
		$member=Member::model()->findByPk(Yii::app()->session['eid']);
		if ($member != null)
		{
			// Pick up the User to translate the SID to a id/uid
			$criteria = new CDbCriteria;
			$criteria->addCondition("sid = '" . $member->sid . "'");
			$user = User::model()->find($criteria);
			if ($user != null)
				return $user->id;
		}
		return -1;
	}

    public function getThumbDir()
    {
        return Yii::app()->basePath . $this->_thumbDir;
    }

    public function deleteProductCheckboxes($id)
    {
        // Interests
        Yii::log("Deleting all interests for event " . $id, CLogger::LEVEL_WARNING, 'system.test.kim');
        EventHasInterest::model()->deleteAllByAttributes(array('event_event_id' => $id));
		// Formats
        Yii::log("Deleting all formats for event " . $id, CLogger::LEVEL_WARNING, 'system.test.kim');
        EventHasFormat::model()->deleteAllByAttributes(array('event_event_id' => $id, ));
		// Facilities
        Yii::log("Deleting all facilities for event " . $id, CLogger::LEVEL_WARNING, 'system.test.kim');
        EventHasFacility::model()->deleteAllByAttributes(array('event_event_id' => $id, ));
    }

	public function updateProductCheckboxes($id)
	{
/********************************************
// Initialise Interest and Format tables (takeon)
$events = Event::model()->findAll();
foreach ($events as $event):
	$data = new EventHasInterest;
    $data->event_event_id = $event->id;
    $data->event_interest_id = 10;
    $data->save();
	$data = new EventHasFormat;
    $data->event_event_id = $event->id;
    $data->event_format_id = 2;
    $data->save();
endforeach;
return;
*********************************************/
    	// Interests
        if (isset($_POST['interest']))
        {
            foreach ($_POST['interest'] as $item):
                Yii::log("Creating interest item " . $item, CLogger::LEVEL_WARNING, 'system.test.kim');
                $data = new EventHasInterest;
                $data->event_event_id = $id;
                $data->event_interest_id = $item;
                $data->save();
            endforeach;
        }
    	// Formats
        if (isset($_POST['format']))
        {
            foreach ($_POST['format'] as $item):
                Yii::log("Creating format item " . $item, CLogger::LEVEL_WARNING, 'system.test.kim');
                $data = new EventHasFormat;
                $data->event_event_id = $id;
                $data->event_format_id = $item;
                $data->save();
            endforeach;
        }
    	// Facilities
        if (isset($_POST['facility']))
        {
            foreach ($_POST['facility'] as $item):
                Yii::log("Creating facility item " . $item, CLogger::LEVEL_WARNING, 'system.test.kim');
                $data = new EventHasFacility;
                $data->event_event_id = $id;
                $data->event_facility_id = $item;
                $data->save();
            endforeach;
        }
	}

// Redactor image handling -----------------------------------------------------

    public function actionImageUpload()
    {
        $uploadedFile = CUploadedFile::getInstanceByName('file');
        if (!empty($uploadedFile)) {
            $rnd = rand();  // generate random number between 0-9999
            $fileName = "{$rnd}.{$uploadedFile->extensionName}";  // random number + file name
            if ($uploadedFile->saveAs(Yii::app()->basePath . '/../userdata/event/image/' . $fileName)) {

                $array = array(
                     'filelink' => Yii::app()->baseUrl . '/event/../userdata/event/image/' . $fileName);

                echo stripslashes(json_encode($array));
                Yii::app()->end();
            }
        }
        throw new CHttpException(400, 'The request cannot be fulfilled due to bad syntax');
    }

// "ListImages" (used to browse images in the server)

    public function actionImageList() {
        $images = array();
        $handler = opendir(Yii::app()->basePath . '/../userdata/event/image');
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..")
                $images[] = $file;
        }
        closedir($handler);

        $jsonArray = array();

        foreach ($images as $image)
            $jsonArray[] = array(
                'thumb' => Yii::app()->baseUrl . '/event/userdata/event/image/' . $image,
                'image' => Yii::app()->baseUrl . '/event/userdata/event/image/' . $image,
            );

        header('Content-type: application/json');
        echo CJSON::encode($jsonArray);
    }

}
