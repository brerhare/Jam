<?php

// TCPDF ticket file
require_once("php/ticket.php"); 

class TicketController extends Controller
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
				'actions'=>array('index','view','book'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
	 * Show the choice of events
	 */	 
	public function actionIndex()
	{
		Yii::log("ARE WE DEFAULT? INDEX LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
		$this->render('index');
	}

	public function actionBook($id)
	{
        Yii::log("EVENT BOOKING PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');
        $model=$this->loadModel($id);

//echo "GET";
//print_r($_GET);
//echo "POST";
//print_r($_POST);

		if ((isset($_POST['ptotal'])) && ($_POST['ptotal'] != 0))
		{
			Yii::log("EVENT INDEX FORM FILLED: " . $_POST['ptotal'], CLogger::LEVEL_WARNING, 'system.test.kim');
			// Print the tickets
			$ticket_type_area_arr = array();
			$ticket_type_id_arr = array();
			$ticket_type_qty_arr = array();
			$ticket_type_price_arr = array();
			$ticket_type_total_arr = array();
			$ticketNumbers = array();
			for ($x = 0; ; $x++)
			{
				if(!isset($_POST['line_' . $x . '_select']))	// ie no more lines
					break;
				$qty = $_POST['line_' . $x . '_select'];
				if ($qty == 0)	// ie no tickets for this line
					continue;
				array_push($ticket_type_area_arr,  $_POST['pline_' . $x . '_area']);
				array_push($ticket_type_id_arr,    $_POST['pline_' . $x . '_id']);
				array_push($ticket_type_qty_arr,   $qty);
				array_push($ticket_type_price_arr, $_POST['pline_' . $x . '_price']);
				array_push($ticket_type_total_arr, $_POST['pline_' . $x . '_total']);
			}

			genTicket(
				"ordernum1",
				$model->ticket_vendor_id,
				$model->id,
				$ticket_type_area_arr,
				$ticket_type_id_arr,
				$ticket_type_qty_arr,
				$ticket_type_price_arr,
				$ticket_type_total_arr,
				$_POST['ptotal'],
				$ticketNumbers
			);
			// Update the db
			//$this->redirect(array('index',));
		}
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('book',array(
                        'model'=>$model,
                        'somedata'=>array(1,2,3),
        ));
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
}
