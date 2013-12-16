<?php

class MailerListController extends Controller
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
				'actions'=>array('create','update','admin','delete','import'),
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
		$model=new MailerList;
		$model->uid = Yii::app()->session['uid'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MailerList']))
		{
			$model->attributes=$_POST['MailerList'];
			if($model->save())
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MailerList']))
		{
			$model->attributes=$_POST['MailerList'];
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
		$dataProvider=new CActiveDataProvider('MailerList');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new MailerList('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MailerList']))
			$model->attributes=$_GET['MailerList'];

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
		$model=MailerList::model()->findByPk($id);
		if (($model===null) || ($model->uid != Yii::app()->session['uid']))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Import mailing list contents
	 */
	public function actionImport($list_id)
	{
		$model=$this->loadModel($list_id);
		Yii::app()->session['list_id'] = $list_id;

		if(isset($_FILES['files']))
		{
			//upload new files
			foreach($_FILES['files']['name'] as $key=>$filename)
			{
				$tmpName = '/tmp/import_' . rand();
				move_uploaded_file($_FILES['files']['tmp_name'][$key],$tmpName);
				// Do the import
				if (($handle = fopen($tmpName, "r")) === FALSE)
					die("Cant open $tmpName");
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
				{
					if (!strstr($data[0], "@"))
						continue;
					$member = new MailerMember;
					$member->uid = Yii::app()->session['uid'];
					$member->email_address = $data[0];
					$member->first_name = $data[1];
					$member->last_name = $data[2];
					$member->telephone = $data[3];
					$member->address = $data[4];
					$member->active = 1;
					$member->mailer_list_id = Yii::app()->session['list_id'];
					$member->save();
				}

				$this->redirect(array('admin'));
			}
		}

		$this->render('import',array(
			'id'=>$list_id,
			'name'=>$model->name,
		));
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mailer-list-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
