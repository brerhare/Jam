<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','import','admin','delete','imageUpload','imageList'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','import','imageUpload','imageList'),
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
        Yii::log("CREATE EVENT ----- start " , CLogger::LEVEL_WARNING, 'system.test.kim');
        $iDir = $this->getThumbDir();
        $model=new Event;
        $model2=new Ws;
        $model->member_id = Yii::app()->session['eid'];
        $model->approved = 1;	// @@TODO: Hard coded!!!
        $model->ticket_event_id = 1;	// Default to 'yes'
        $model2->booking_essential = 1;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Event']))
        {
        	Yii::log("CREATE EVENT ----- POST happened " , CLogger::LEVEL_WARNING, 'system.test.kim');
            $model->attributes=$_POST['Event'];
            $model->thumb_path=CUploadedFile::getInstance($model, 'thumb_path');
			$model2->attributes=$_POST['Ws'];
			//$model2->event_id = $model->id;

            if($model->save())
            {
        		Yii::log("CREATE EVENT ----- successful model->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
            	$this->deleteProductCheckboxes($model->id);
                $this->updateProductCheckboxes($model->id);

            	$modelId = $model->id;	// Store for later
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
        		Yii::log("CREATE EVENT ----- end of create " , CLogger::LEVEL_WARNING, 'system.test.kim');
                $this->redirect(array('admin'));
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'model2'=>$model2,
            'ticketUid'=>$this->getTicketUidFromEventSid(),	// Either a valid uid or -1
        ));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        Yii::log("UPDATE EVENT ----- star  t. id = " . $id , CLogger::LEVEL_WARNING, 'system.test.kim');
        $iDir = $this->getThumbDir();
        $model=$this->loadModel($id);

		$criteria = new CDbCriteria;
		$criteria->condition="event_id = $model->id";
        $model2=Ws::model()->find($criteria);
        if (!($model2))
        {
        	Yii::log("UPDATE EVENT ----- loading up. id = " . $id . " no model2. Dying " , CLogger::LEVEL_WARNING, 'system.test.kim');
        	die('Couldnt find event matching Ws record for update');
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

            	// Save Ws fields too
            	$model2->attributes=$_POST['Ws'];
            	$model2->event_id = $model->id;
            	Yii::log("UPDATE EVENT ----- about to do model2->save() " , CLogger::LEVEL_WARNING, 'system.test.kim');
            	$model2->save();
            	Yii::log("UPDATE EVENT ----- did model2->save(). Finished " , CLogger::LEVEL_WARNING, 'system.test.kim');
                $this->redirect(array('admin'));
            }
        }

        $this->render('update',array(
            'model'=>$model,
            'model2'=>$model2,
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
            $this->deleteProductCheckboxes($model->id);
           	Yii::log("DELETE EVENT ----- about to delete model() " . CLogger::LEVEL_WARNING, 'system.test.kim');
			$model->delete();

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
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Import an event
	 */
	public function actionImport()
	{
/*
+-----------------+--------------+------+-----+---------+----------------+
| Field           | Type         | Null | Key | Default | Extra          |
+-----------------+--------------+------+-----+---------+----------------+
| id              | int(11)      | NO   | PRI | NULL    | auto_increment |
| user_name       | varchar(255) | NO   | UNI | NULL    |                |
| password        | varchar(255) | NO   |     | NULL    |                |
| first_name      | varchar(255) | NO   |     | NULL    |                |
| last_name       | varchar(255) | NO   |     | NULL    |                |
| telephone       | varchar(45)  | YES  |     | NULL    |                |
| email_address   | varchar(255) | NO   |     | NULL    |                |
| organisation    | varchar(255) | YES  |     | NULL    |                |
| join_date       | date         | NO   |     | NULL    |                |
| last_login_date | date         | NO   |     | NULL    |                |
| captcha         | varchar(45)  | YES  |     | NULL    |                |
+-----------------+--------------+------+-----+---------+----------------+

		$file = "/tmp/ws-member.csv";
		$row = 0;
		if (($handle = fopen($file, "r")) === FALSE)
			die("Cant open $file");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
		{
	// Ignore header line
        	if ($row == 0)
        	{
            	$row++;
           		continue;
        	}
        	if ($row == 1)
        	{
        		$row++;
        		continue;
        	}
			$member = new Member;
			$member->user_name = $data[1];
			$member->password = $data[1];
			$member->first_name = $data[2];
			$member->last_name = $data[2];
			$member->telephone = $data[4];
			$member->email_address = $data[3];
			$member->organisation = $data[1];
			$member->join_date = '2013-10-10';
			$member->last_login_date = '2013-10-10';
			$member->captcha = '';

			if (!($member->save()))
				die("Member save failed on line " . $row);
		}
		return;
*/

		$file = "/tmp/ws.csv";
		$row = 0;
		if (($handle = fopen($file, "r")) === FALSE)
			die("Cant open $file");
    	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
    	{
        	// Ignore header line
        	if ($row == 0)
        	{
            	$row++;
           		continue;
        	}
        	if ($row == 1)
        	{
        		$row++;
        		continue;
        	}

        	// Init db fields
        	$event = new Event;
        	$ws = new Ws;

        	$num = count($data);
        	//echo "<p> $num fields in line $row: <br /></p>\n";
        	for ($c = 0; $c < $num; $c++)
        	{
            	//echo $data[$c] . "<br />\n";
        		switch ($c)
        		{
        			case 0: // title
        				$event->title = $data[$c];
        				echo $event->title . "<br>";
        				break;
        			case 1: // interest
						break;
					case 2: // type
						break;
					case 3: // start date
						$dt = $data[$c] . ' ' . $data[$c+1];
						$date = str_replace('/', '-', $dt);
						echo date('Y-m-d H:i:s', strtotime($date)) . "<br>";
						$event->start = date('Y-m-d H:i:s', strtotime($date));
						break;
					case 5: // start date
						$dt = $data[$c] . ' ' . $data[$c+1];
						$date = str_replace('/', '-', $dt);
						//echo date('Y-m-d H:i:s', strtotime($date)) . "<br>";
						$event->end = date('Y-m-d H:i:s', strtotime($date));
						break;
					case 7: // address
						$event->address = $data[$c];
						break;
					case 8: // post code
						$event->post_code = $data[$c];
						break;
					case 9: // web
						$event->web = $data[$c];
						break;
					case 10: // price band
						break;
					case 11: // contact
						$event->contact = $data[$c];
						break;
					case 12: // description
						$event->description = $data[$c];
						break;
// -----------------------------------------------------------------------------
					case 13:
						if ((strtoupper($data[$c]) == 'YES') || (strtoupper($data[$c]) == 'Y'))
							$ws->booking_essential = 1;
						break;
					case 14:
						$ws->os_grid_ref = $data[$c];
						break;
					case 15:
						$ws->grade = $data[$c];
						break;
					case 16:
						if ((strtoupper($ws->wheelchair_accessible) == 'YES') || (strtoupper($ws->wheelchair_accessible) == 'Y'))
						$ws->wheelchair_accessible = 1;
						break;
					case 17:
						if ($ws->min_age)
							$ws->min_age = $data[$c];
						break;
					case 18:
						if ($ws->max_age)
						$ws->max_age = $data[$c];
						break;
					case 19:
						$ws->child_ages_restrictions = $data[$c];
						break;
					case 20:
						$criteria = new CDbCriteria;
						$criteria->addCondition("organisation = '" . $data[$c] . "'");
						$member = Member::model()->find($criteria);
						if (!($member))
							die("Organisation field could not identify a member");
						$event->member_id = $member->id;
						break;
					case 21:
						$ws->full_price_notes = $data[$c];
						break;
					case 22:
						$ws->additional_venue_info = $data[$c];
						break;
					case 23:
						$ws->short_description = $data[$c];
						break;
				}
			}
// @@TODO: REMOVE HARD CODING!
			//$event->member_id = 7;
			$event->program_id = 6;
			if (!($event->save()))
				die("Event save failed on line " . $row);
			$ws->event_id = $event->id;
			echo $ws->event_id . "<br>";
			if (!($ws->save()))
				die("Event additional info save failed on line " . $row);
			$row++;
		}
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
