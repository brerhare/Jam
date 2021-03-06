<?php

class JellyGalleryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	private $_imageDir = '/../userdata/jelly/gallery/';

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
				'actions'=>array('create','update','delete','deleteWithImages','admin'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','deleteWithImages'),
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
		$model=new JellyGallery;
		$model->active = 1;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['JellyGallery']))
		{
			$model->attributes=$_POST['JellyGallery'];
			$model->image=CUploadedFile::getInstance($model, 'image');
			if($model->save())
			{
                if (strlen($model->image) > 0)
                {
                    $fname = Yii::app()->basePath . $this->_imageDir . $model->image;
                    $model->image->saveAs($fname);
					$this->makeThumb($fname);
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

		if(isset($_POST['JellyGallery']))
		{
			$model->attributes=$_POST['JellyGallery'];
            $file=CUploadedFile::getInstance($model, 'image');
            if(is_object($file) && get_class($file) === 'CUploadedFile')
            {
                if (file_exists(Yii::app()->basePath . $this->_imageDir . $model->image))
                {
                    unlink(Yii::app()->basePath . $this->_imageDir . $model->image);
					unlink(Yii::app()->basePath . $this->_imageDir . 'thumb_' . $model->image);
                }
                $model->image = $file;
            }

			if($model->save())
			{
                if(is_object($file))
                {
                    $fname = Yii::app()->basePath . $this->_imageDir . $model->image;
                    $model->image->saveAs($fname);
					$this->makeThumb($fname);
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

			// Cant delete galleries for which there are images (user deletes all those first)
			$criteria = new CDbCriteria;
			$criteria->addCondition("jelly_gallery_id = " . $id);
			$galleryImages = JellyGalleryImage::model()->findAll($criteria);
			foreach ($galleryImages as $galleryImage):
            	$oldfilename = $galleryImage->image;
            	if (($oldfilename != '') && (file_exists(Yii::app()->basePath . $this->_imageDir . $oldfilename)))
            	{
                	unlink(Yii::app()->basePath . $this->_imageDir . $oldfilename);
            		if (($oldfilename != '') && (file_exists(Yii::app()->basePath . $this->_imageDir . 'thumb_' . $oldfilename)))
                    	unlink(Yii::app()->basePath . $this->_imageDir . 'thumb_' . $oldfilename);
            	}
				$galleryImage->delete();
			endforeach;
        	$oldfilename = $this->loadModel($id)->image;
        	if (($oldfilename != '') && (file_exists(Yii::app()->basePath . $this->_imageDir . $oldfilename)))
        	{
            	unlink(Yii::app()->basePath . $this->_imageDir . $oldfilename);
				unlink(Yii::app()->basePath . $this->_imageDir . 'thumb_' . $oldfilename);
        	}

			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	public function actionDeleteWithImages($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request

			// Cant delete galleries for which there are images (user deletes all those first)
			$criteria = new CDbCriteria;
			$criteria->addCondition("jelly_gallery_id = " . $id);
			$galleryImages = JellyGalleryImage::model()->findAll($criteria);
			if ($galleryImages)
				throw new CHttpException(400,'XXXXXXXCant delete a gallery album that still has images.');

        	$oldfilename = $this->loadModel($id)->image;
        	if (($oldfilename != '') && (file_exists(Yii::app()->basePath . $this->_imageDir . $oldfilename)))
        	{
            	unlink(Yii::app()->basePath . $this->_imageDir . $oldfilename);
				unlink(Yii::app()->basePath . $this->_imageDir . 'thumb_' . $oldfilename);
        	}

			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(array('admin'));
		}
		else
		{
Yii::log("Going for confirm delete with images", CLogger::LEVEL_WARNING, 'system.test.kim');
 $this->redirect(array('create'));

		//$this->redirect(array('index'));

		$dataProvider=new CActiveDataProvider('JellyGallery');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));


/*
       		$this->render('deleteWithImages',array(
           		'id'=>$id,
       		));
*/
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('JellyGallery');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new JellyGallery('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['JellyGallery']))
			$model->attributes=$_GET['JellyGallery'];

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
		$model=JellyGallery::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='jelly-gallery-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /**
     * Add a watermark to the uploaded image
     * @param &$filename Address of filename to update with watermarked image
     */
    private function makeThumb(&$filename)
    {
        // Make a thumbnail image of the filename
        list($width, $height, $type) = getimagesize($filename);
        if ($width > $height) {
            $width = 150;
            $height = 0;
        } else {
            $height = 150;
            $width = 0;
        }
$width = 150; $height = 150;
        ImageUtils::resize($filename, dirname($filename) . '/thumb_' . basename($filename), $width, $height);
    }


}
