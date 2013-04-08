<?php

class RoomController extends Controller
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
			array('allow',  // allow all users
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user
				'actions'=>array('create','update', 'admin','delete'),
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
		$model=new Room;
		$model->uid = Yii::app()->session['uid'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Room']))
		{
			$model->attributes=$_POST['Room'];
			if($model->save())
			{
				$this->deleteRoomTabs($model->id);
				$this->updateRoomTabs($model->id);
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

		if(isset($_POST['Room']))
		{
			$model->attributes=$_POST['Room'];
			if($model->save())
			{
				// Update other tables
				$this->deleteRoomTabs($model->id);
				$this->updateRoomTabs($model->id);
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
			$this->deleteRoomTabs($id);
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
		$dataProvider=new CActiveDataProvider('Room');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Room('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Room']))
			$model->attributes=$_GET['Room'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	// Delete all room facilities and extras
	public function deleteRoomTabs($id)
	{
		// Options
		Yii::log("Deleting all facilities for room " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
		RoomHasFacility::model()->deleteAllByAttributes(array('room_id' => $id, 'uid' => Yii::app()->session['uid']));
		Yii::log("Deleting all extras for room " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
		RoomHasExtra::model()->deleteAllByAttributes(array('room_id' => $id, 'uid' => Yii::app()->session['uid']));
		// Pricing
		Yii::log("Deleting all occupancy types for room " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
		RoomHasOccupancyType::model()->deleteAllByAttributes(array('room_id' => $id, 'uid' => Yii::app()->session['uid']));
	}

	// Update room facilities and extras checkboxes
	public function updateRoomTabs($id)
	{
		// Option - facilities checkboxes
		if (isset($_POST['facility']))
		{
			foreach ($_POST['facility'] as $facilityItem):
				Yii::log("Creating facility item " . $facilityItem, CLogger::LEVEL_INFO, 'system.test.kim');
				$fac = new RoomHasFacility;
				$fac->room_id = $id;
				$fac->facility_id = $facilityItem;
				$fac->uid = Yii::app()->session['uid'];
				$fac->save();
			endforeach;
		}
		// Option - extras checkboxes
		if (isset($_POST['extra']))
		{
			foreach ($_POST['extra'] as $extraItem):
				Yii::log("Creating extra item " . $extraItem, CLogger::LEVEL_INFO, 'system.test.kim');
				$ext = new RoomHasExtra;
				$ext->room_id = $id;
				$ext->extra_id = $extraItem;
				$ext->uid = Yii::app()->session['uid'];
				$ext->save();
			endforeach;
		}
		// Pricing
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$occupancyTypes = OccupancyType::model()->findAll($criteria);
		foreach ($occupancyTypes as $occupancyType):
			$prc = new RoomHasOccupancyType;
			$prc->room_id = $id;
			$prc->occupancy_type_id = $occupancyType->id;
			$prc->uid = Yii::app()->session['uid'];
			$prc->single_rate = $_POST[$occupancyType->id . '_single'];
			$prc->double_rate = $_POST[$occupancyType->id . '_double'];
			$prc->any_rate    = $_POST[$occupancyType->id . '_any'];
			$prc->adult_rate  = $_POST[$occupancyType->id . '_adult'];
			$prc->child_rate  = $_POST[$occupancyType->id . '_child'];
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
		$model=Room::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='room-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
