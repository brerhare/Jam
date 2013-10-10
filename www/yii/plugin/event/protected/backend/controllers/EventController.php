<?php

class EventController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('create','update','import'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','import'),
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
		$model=new Event;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
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

		if(isset($_POST['Event']))
		{
			$model->attributes=$_POST['Event'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
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
*/
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
						if ($data[$c] == 'WWT')
						die(Yii::app()->session['uid']);
						// @@ TODO : ORGANISATION
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
			$event->member_id = 7;
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
}
