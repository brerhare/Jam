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
				'actions'=>array('create','update','admin','delete','report','showReport'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','report','showReport'),
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
	 * Choose an event to show transactions for
	 */
	public function actionReport()
	{
		$model=new Event('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('report1',array(
			'model'=>$model,
		));
	}

	/**
	 * Show report for event $id (called by above function's view)
	 */
	public function actionShowReport($id)
	{
		$model=$this->loadModel($id);
		if(isset($_GET['Event']))
			$model->attributes=$_GET['Event'];

		$this->render('report2',array(
			'model'=>$this->loadModel($id),
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
