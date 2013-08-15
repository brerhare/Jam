<?php

class ImageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	private $_imageDir = '/../userdata/image/';

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
				'actions'=>array('create','update','admin','delete','session'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','admin','session'),
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
        $model=new Image;
		$model->uid = Yii::app()->session['uid'];

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Image']))
        {
            $model->attributes=$_POST['Image'];
            $model->filename=CUploadedFile::getInstance($model, 'filename');
            if($model->save())
            {
                if (strlen($model->filename) > 0)
                {
                    $fname = Yii::app()->basePath . $this->_imageDir . $model->filename;
                    $model->filename->saveAs($fname);
                    //$this->_watermark($fname);
                }
                $this->redirect(array('admin','id'=>$model->product_product_id));
            }
        }

        // Hard-wire this image to the product stored in our session
        $model->product_product_id = Yii::app()->session['product_id'];

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

        if(isset($_POST['Image']))
        {
            $model->attributes=$_POST['Image'];
            $file=CUploadedFile::getInstance($model, 'filename');
            if(is_object($file) && get_class($file) === 'CUploadedFile')
            {
                if (file_exists(Yii::app()->basePath . $this->_imageDir . $model->filename))
                {
                    unlink(Yii::app()->basePath . $this->_imageDir . $model->filename);
                    //unlink(Yii::app()->basePath . $this->_imageDir . 'gall_' . $model->filename);
                    //unlink(Yii::app()->basePath . $this->_imageDir . 'orig_' . $model->filename);
                }
                $model->filename = $file;
            }

            if($model->save())
            {
                if(is_object($file))
                {
                    $fname = Yii::app()->basePath . $this->_imageDir . $model->filename;
                    $model->filename->saveAs($fname);
                    //$this->_watermark($fname);

                }
                $this->redirect(array('admin','id'=>$model->id));
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
        if (($oldfilename != '') && (file_exists(Yii::app()->basePath . $this->_imageDir . $oldfilename)))
        {
            unlink(Yii::app()->basePath . $this->_imageDir . $oldfilename);
            //unlink(Yii::app()->basePath . $this->_imageDir . 'gall_' . $oldfilename);
            //unlink(Yii::app()->basePath . $this->_imageDir . 'orig_' . $oldfilename);
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
		$dataProvider=new CActiveDataProvider('Image');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Image('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Image']))
			$model->attributes=$_GET['Image'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    /**
     * Entry point. Same as actionAdmin except first stores the passed product_id in the session
     */
    // $product_id supplied by the CButtonColumn in product/admin
    public function actionSession($product_id)
    {
        Yii::app()->session['product_id'] = $product_id;
        $model=new Image('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Image']))
            $model->attributes=$_GET['Image'];

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
		$model=Image::model()->findByPk($id);
		if (($model===null) || ($model->uid != Yii::app()->session['uid']))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='image-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
