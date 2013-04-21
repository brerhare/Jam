<?php

class AreaController extends Controller
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
				'actions'=>array('create','update','session','admin'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','session'),
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
		$model=new Area;
		$model->uid = Yii::app()->session['uid'];
		$model->ticket_event_id = Yii::app()->session['event_id'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Area']))
		{
			$model->attributes=$_POST['Area'];
			if($model->save())
			{
				$this->updateTicketTypesAvailable($model->id);
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

		if(isset($_POST['Area']))
		{
			$model->attributes=$_POST['Area'];
			if($model->save())
			{
				$this->deleteTicketTypesAvailable($model->id);
				$this->updateTicketTypesAvailable($model->id);
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
			$this->loadModel($id)->delete();
			$this->deleteTicketTypesAvailable($model->id);

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
		$dataProvider=new CActiveDataProvider('Area');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Area('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Area']))
			$model->attributes=$_GET['Area'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    /**
     * Entry point. Same as actionAdmin except first stores the passed event_id in the session
     */
    // $event_id supplied by the CButtonColumn in event/admin
    public function actionSession($event_id)
    {
        Yii::app()->session['event_id'] = $event_id;
        $model=new Area('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Area']))
            $model->attributes=$_GET['Area'];

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
		$model=Area::model()->findByPk($id);
		if (($model===null) || ($model->uid != Yii::app()->session['uid']))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    // Delete all ticket-types (checkboxes)
    public function deleteTicketTypesAvailable($id)
    {
        // Options
        Yii::log("Deleting all ticket types for area id " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
        AreaHasTicketType::model()->deleteAllByAttributes(array('ticket_area_id' => $id, 'uid' => Yii::app()->session['uid']));
    }

    // Update room facilities and extras checkboxes
    public function updateTicketTypesAvailable($id)
    {
        // Option - ticket types checkboxes
        if (isset($_POST['ticktock']))
        {
            foreach ($_POST['ticktock'] as $ttItem):
                Yii::log("Creating ticktock item " . $ttItem, CLogger::LEVEL_INFO, 'system.test.kim');
                $tt = new AreaHasTicketType;
                $tt->ticket_area_id = $id;
                $tt->ticket_ticket_type_id = $ttItem;
                $tt->uid = Yii::app()->session['uid'];
                $tt->save();
            endforeach;
        }
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
