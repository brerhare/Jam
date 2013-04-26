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
				'actions'=>array('index','view','book','paid'),
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

            $ip = "UNKNOWN";
            if (getenv("HTTP_CLIENT_IP"))
                $ip = getenv("HTTP_CLIENT_IP");
            else if (getenv("HTTP_X_FORWARDED_FOR"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else if (getenv("REMOTE_ADDR"))
                $ip = getenv("REMOTE_ADDR");

			Order::model()->deleteAllByAttributes(array('ip' => $ip, 'uid' => Yii::app()->session['uid']));

			for ($x = 0; ; $x++)
			{
				if(!isset($_POST['line_' . $x . '_select']))	// ie no more lines
					break;
				$qty = $_POST['line_' . $x . '_select'];
				if ($qty == 0)	// ie no tickets for this line
					continue;

			Yii::log("EVENT INDEX LINE ITEM: " . $_POST['pline_' . $x . '_price'], CLogger::LEVEL_WARNING, 'system.test.kim');

				$order=new Order;
				$order->uid = Yii::app()->session['uid'];
				$order->sid = Yii::app()->session['sid'];
				$order->ip = $ip;
				$order->vendor_id = $model->ticket_vendor_id;
				$order->event_id = $model->id;
				$order->http_ticket_type_area = $_POST['pline_' . $x . '_area'];
				$order->http_ticket_type_id = $_POST['pline_' . $x . '_id'];
				$order->http_ticket_type_qty = $qty;
				$order->http_ticket_type_price = $_POST['pline_' . $x . '_price'];
				$order->http_ticket_type_total = $_POST['pline_' . $x . '_total'];
				$order->http_total = $_POST['ptotal'];
				$order->return_url = Yii::app()->baseUrl;
				if(!$order->save())
				{
					$this->redirect(array('admin'));
				}
			}

			// Go to paymentsense
			$this->redirect(Yii::app()->baseUrl . "/php/paymentSense/EntryPoint.php?sid=" . Yii::app()->session['sid'] . "&xid=" . rand(99999,999999));
		}

        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('book',array(
                        'model'=>$model,
                        'somedata'=>array(1,2,3),
        ));
	}

	public function actionPaid()
	{
        Yii::log("PAID PAGE LOADING" , CLogger::LEVEL_WARNING, 'system.test.kim');

          $ip = "UNKNOWN";
          if (getenv("HTTP_CLIENT_IP"))
              $ip = getenv("HTTP_CLIENT_IP");
          else if (getenv("HTTP_X_FORWARDED_FOR"))
              $ip = getenv("HTTP_X_FORWARDED_FOR");
          else if (getenv("REMOTE_ADDR"))
              $ip = getenv("REMOTE_ADDR");

        $ticket_type_area_arr = array();
		$ticket_type_id_arr = array();
		$ticket_type_qty_arr = array();
		$ticket_type_price_arr = array();
		$ticket_type_total_arr = array();
		$ticketNumbers = array();
		
		$criteria = new CDbCriteria;
        //$criteria->addCondition("uid = " . Yii::app()->session['uid']);
        $criteria->addCondition("ip = '" . $ip . "'");

        $orders = Order::model()->findAll($criteria);
        foreach ($orders as $order)
        {
        	array_push($ticket_type_area_arr,  $order->http_ticket_type_area);
			array_push($ticket_type_id_arr,    $order->http_ticket_type_id);
			array_push($ticket_type_qty_arr,   $order->http_ticket_type_qty);
			array_push($ticket_type_price_arr, $order->http_ticket_type_price);
			array_push($ticket_type_total_arr, $order->http_ticket_type_total);
        }

		$ticketNumbers = array();
		genTicket(
			$order->order_number,
			$order->vendor_id,
			$order->event_id,
			$ticket_type_area_arr,
			$ticket_type_id_arr,
			$ticket_type_qty_arr,
			$ticket_type_price_arr,
			$ticket_type_total_arr,
			$order->http_total,
			$ticketNumbers
		);

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
