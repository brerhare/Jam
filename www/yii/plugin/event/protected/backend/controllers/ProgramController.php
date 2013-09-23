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
				'actions'=>array('create','update','admin','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
				$memberHasProgram->event_member_id = Yii::app()->session['uid'];
				$memberHasProgram->event_program_id = $model->id;
				$memberHasProgram->privilege_level = $memberHasProgram->PRIVILEGE_ADMIN;
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
		$model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Program']))
		{
			$model->attributes=$_POST['Program'];
//var_dump($_POST);die();
$crap = "";
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
						$crap .= 'old ' . $key . ':' . $val . "<br>";
						$criteria = new CDbCriteria;
						$criteria->addCondition("event_member_id = " . substr($key, 3));
						$criteria->addCondition("event_program_id = " . $model->id);
						$memberHasProgram = MemberHasProgram::model()->find($criteria);
						if (!($memberHasProgram))
							$memberHasProgram = new MemberHasProgram;
						$memberHasProgram->event_member_id = substr($key, 3);
						$memberHasProgram->event_program_id = $model->id;
						$memberHasProgram->privilege_level = $_POST['id_' . substr($key, 3)];
						$memberHasProgram->save();
					}
				}
			}
			//die($crap);

            $fileT=CUploadedFile::getInstance($model, 'thumb_path');
            if(is_object($fileT) && get_class($fileT) === 'CUploadedFile')
            {
                if (file_exists(Yii::app()->basePath . $this->_thumbDir . $model->thumb_path))
                	if (substr(Yii::app()->basePath . $this->_thumbDir . $model->thumb_path, -1) != '/')
                    	unlink(Yii::app()->basePath . $this->_thumbDir . $model->thumb_path);
                $model->thumb_path = $fileT;
            }

            $fileI=CUploadedFile::getInstance($model, 'icon_path');
            if(is_object($fileI) && get_class($fileI) === 'CUploadedFile')
            {
                if (file_exists(Yii::app()->basePath . $this->_iconDir . $model->icon_path))
                    if (substr(Yii::app()->basePath . $this->_iconDir . $model->thumb_path, -1) != '/')
                    	unlink(Yii::app()->basePath . $this->_iconDir . $model->icon_path);
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
