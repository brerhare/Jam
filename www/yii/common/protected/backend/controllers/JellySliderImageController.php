<?php

class JellySliderImageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	private $_imageDir = '/../userdata/jelly/sliderimage/';



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
			array('allow',  // allow all users
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user
				'actions'=>array('create','update','admin','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user
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
		$model=new JellySliderImage;
		$model->slider = 1;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JellySliderImage']))
		{
			$rand = "random" . rand(1900, 9999) . "-";
			$model->attributes=$_POST['JellySliderImage'];
			$image = CUploadedFile::getInstance($model, 'image');
			$model->image = $rand . CUploadedFile::getInstance($model, 'image');
			if($model->save())
			{
                if (strlen($model->image) > 0)
                {
                    $fname = Yii::app()->basePath . $this->_imageDir . $model->image;
                    $image->saveAs($fname);
                    //$this->_watermark($fname);
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
		$model=$this->loadModel($id);

		$model->scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JellySliderImage']))
		{
			$rand = "random" . rand(1900, 9999) . "-";
			$model->attributes=$_POST['JellySliderImage'];
            $file = CUploadedFile::getInstance($model, 'image');
            if(is_object($file) && get_class($file) === 'CUploadedFile')
            {
                if (file_exists(Yii::app()->basePath . $this->_imageDir . $model->image))
                {
                    unlink(Yii::app()->basePath . $this->_imageDir . $model->image);
                }
                $model->image = $rand . $file;
            }

			if($model->save())
			{
                if(is_object($file))
                {
                    $fname = Yii::app()->basePath . $this->_imageDir . $model->image;
                    $file->saveAs($fname);
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

        	$oldfilename = $this->loadModel($id)->image;
        	if (($oldfilename != '') && (file_exists(Yii::app()->basePath . $this->_imageDir . $oldfilename)))
        	{
            	unlink(Yii::app()->basePath . $this->_imageDir . $oldfilename);
        	}



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
		$dataProvider=new CActiveDataProvider('JellySliderImage');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new JellySliderImage('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['JellySliderImage']))
			$model->attributes=$_GET['JellySliderImage'];

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
		$model=JellySliderImage::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='jelly-slider-image-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
