<?php

class ProductController extends Controller
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
		$model=new Product;
		$model->uid = Yii::app()->session['uid'];
		$model->product_department_id = Yii::app()->session['department_id'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			if($model->save())
			{
                $this->deleteProductTabs($model->id);
                $this->updateProductTabs($model->id);
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Product']))
		{
			$model->attributes=$_POST['Product'];
			if($model->save())
			{
                $this->deleteProductTabs($model->id);
                $this->updateProductTabs($model->id);
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
			 $this->deleteProductTabs($id);
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
		$dataProvider=new CActiveDataProvider('Product');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Product']))
			$model->attributes=$_GET['Product'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    /**
     * Entry point. Same as actionAdmin except first stores the passed department_id in the session
     */
    // $department_id supplied by the CButtonColumn in department/admin
    public function actionSession($department_id)
    {
        Yii::app()->session['department_id'] = $department_id;
        $model=new Product('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Product']))
            $model->attributes=$_GET['Product'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    // Delete all product features (checkboxes) and options
    public function deleteProductTabs($id)
    {
        // Features
        Yii::log("Deleting all features for product " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
        ProductHasFeature::model()->deleteAllByAttributes(array('product_product_id' => $id));
		// Options
        Yii::log("Deleting all options for product " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
        ProductHasOption::model()->deleteAllByAttributes(array('product_product_id' => $id, ));
        //////////Yii::log("Deleting all images for product " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
        //////////Image::model()->deleteAllByAttributes(array('product_product_id' => $id, ));
    }

    // Update product features (checkboxes) and options
    public function updateProductTabs($id)
    {
        // Features
        if (isset($_POST['feature']))
        {
            foreach ($_POST['feature'] as $featureItem):
                Yii::log("Creating feature item " . $featureItem, CLogger::LEVEL_INFO, 'system.test.kim');
                $feat = new ProductHasFeature;
                $feat->product_product_id = $id;
                $feat->product_feature_id = $featureItem;
                $feat->save();
            endforeach;
        }

        // Pricing
        $criteria = new CDbCriteria;
        $criteria->addCondition("uid = " . Yii::app()->session['uid']);
        $criteria->addCondition("product_department_id = " . Yii::app()->session['department_id']);
        $options = Option::model()->findAll($criteria);
        foreach ($options as $option):
           	$prc = new ProductHasOption;
           	$prc->product_product_id = $id;
           	$prc->product_option_id = $option->id;
           	$prc->price = $_POST[$option->id . '_price'];
			if ($prc->price != 0)
           		$prc->save();
        endforeach;

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Product::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
