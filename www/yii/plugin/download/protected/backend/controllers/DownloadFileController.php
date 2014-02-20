<?php

class DownloadFileController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	private $_imageDir = '/../userdata/';   // Note this is only partial. Gets prepended base path and uid

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
				'actions'=>array('create','update', 'delete'),
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
		$model=new DownloadFile;
        $model->uid = Yii::app()->session['uid'];
        $model->download_collection_id = 1;	// @@@@@@@@@@@@@@@@@@@@@@ !!!!!!!!!

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DownloadFile']))
		{
			$model->attributes=$_POST['DownloadFile'];
            $model->filename=CUploadedFile::getInstance($model, 'filename');
			if($model->save())
			{
				$fname =  $this->getImageDir() . $model->filename;
				$model->filename->saveAs($fname);
			}
			$this->redirect(array('admin'));

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

		$oldfilename = $model->filename;

		$model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DownloadFile']))
		{
			$model->attributes=$_POST['DownloadFile'];
            $file=CUploadedFile::getInstance($model, 'filename');
            if(is_object($file) && get_class($file) === 'CUploadedFile')
            {
                if (file_exists($this->getImageDir() . $oldfilename))
                {
                    unlink($this->getImageDir() . $oldfilename);
                }
                $model->filename = $file;
			}

            if($model->save())
            {
                if(is_object($file))
                {
                    $fname = $this->getImageDir() . $model->filename;
                    $model->filename->saveAs($fname);

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
        $oldfilename = $this->loadModel($id)->filename;
        if (($oldfilename != '') && (file_exists($this->getImageDir() . $oldfilename)))
        {
			if (file_exists($this->getImageDir() . $oldfilename))
            	unlink($this->getImageDir() . $oldfilename);
        }

		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('DownloadFile');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DownloadFile('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DownloadFile']))
			$model->attributes=$_GET['DownloadFile'];

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
		$model=DownloadFile::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='download-file-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

// Image SID directory ---------------------------------------------------------

    public function getImageDir()
    {
        // Create it if doesnt exist
        if (!isset(Yii::app()->session['uid']))
            throw new CHttpException(400,'Cant create image directory - no SID ');

        $iDir = Yii::app()->basePath . $this->_imageDir . Yii::app()->session['uid'] . '/';

        if ((!is_dir($iDir)) &&  (!mkdir($iDir, 0777, true)))
            throw new CHttpException(400,'Failed to create user directory ' . $iDir);

        return $iDir;
    }


}
