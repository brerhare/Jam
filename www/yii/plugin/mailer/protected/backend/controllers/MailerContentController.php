<?php

class MailerContentController extends Controller
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
				'actions'=>array('create','update','admin','delete','publish','imageUpload','imageList'),
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
		$model=new MailerContent;
		$model->uid = Yii::app()->session['uid'];

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MailerContent']))
		{
			$model->attributes=$_POST['MailerContent'];
		if($model->save())
			{
				$this->deleteLists($model->id);
				$this->createLists($model->id);
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

		if(isset($_POST['MailerContent']))
		{
			$model->attributes=$_POST['MailerContent'];
			if($model->save())
			{
				$this->deleteLists($model->id);
				$this->createLists($model->id);
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
			$this->deleteLists($id);
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
		$dataProvider=new CActiveDataProvider('MailerContent');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$iDir = $this->getImageDir();
		if ((!is_dir($iDir)) &&  (!mkdir($iDir, 0777, true)))
			throw new CHttpException(400,'Failed to create user directory ' . $iDir);
		if ((!is_dir($iDir . '/image')) &&  (!mkdir($iDir . '/image', 0777, true)))
			throw new CHttpException(400,'Failed to create user directory ' . $iDir);

		$model=new MailerContent('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MailerContent']))
			$model->attributes=$_GET['MailerContent'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Import mailing list contents
	 */
	public function actionPublish($content_id)
	{
		$model=$this->loadModel($content_id);
		$model->sent = 1;
		$model->save();		// Update 'sent' flag
		Yii::app()->session['content_id'] = $content_id;
		if(Yii::app()->request->isPostRequest)
		{
			// Pick up each list this content must go out to
			$criteria = new CDbCriteria;
			$criteria->addCondition("mailer_content_id = " . $model->id);
			$mailerContentHasLists = MailerContentHasList::model()->findAll($criteria);
			foreach ($mailerContentHasLists as $mailerContentHasList)
			{
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $mailerContentHasList->mailer_list_id);
				$mailerList = MailerList::model()->find($criteria);
				if ($mailerList)
				{
					// Generate an email for every list member
					$criteria = new CDbCriteria;
					$criteria->addCondition("mailer_list_id = " . $mailerList->id);
					$mailerMembers = MailerMember::model()->findAll($criteria);
					foreach ($mailerMembers as $mailerMember)
					{
						if ($mailerMember->active == 0)
							continue;
						// phpmailer
						$from = "no-reply@dglink.co.uk";
						$fromName = "DG Link mailer";
						$subject = $model->title;
						$mail = new PHPMailer();
						$url = Yii::app()->getRequest()->getBaseUrl(true);
						$css = "<style>* { font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif !important; }</style>";
						$msg = "<div style='max-width:700px'>";
						$msg .= str_replace("/mailer/mailer/../", $url . "/", $model->content);
						$msg .= "</div>";
						$mail->AddAddress($mailerMember->email_address);
//						$mail->AddBCC($from);
						$mail->SetFrom($from, $fromName);
						$mail->AddReplyTo($from, $fromName);
//						$mail->AddAttachment($pdf_filename);
						$mail->Subject = $subject;
						$mail->MsgHTML($css . $msg);
						if (!$mail->Send())
						{
    						Yii::log("MAILER COULD NOT SEND MAIL " . $mail->ErrorInfo, CLogger::LEVEL_WARNING, 'system.test.kim');
    						echo "<div id=\"mailerrors\">Mailer Error: " . $mail->ErrorInfo . "</div>";
						}
						else
    						Yii::log("MAILER SENT MAIL SUCCESSFULLY" , CLogger::LEVEL_WARNING, 'system.test.kim');

					}
				}
			}
			$this->redirect(array('admin'));
		}
		$this->render('publish',array(
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
		$model=MailerContent::model()->findByPk($id);
		if (($model===null) || ($model->uid != Yii::app()->session['uid']))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	// Delete all attached lists
	public function deleteLists($id)
	{
		Yii::log("Deleting all attached lists for content " . $id, CLogger::LEVEL_INFO, 'system.test.kim');
		MailerContentHasList::model()->deleteAllByAttributes(array('mailer_content_id' => $id));
	}

	// Update all attached lists
	public function createLists($id)
	{
		if (isset($_POST['list']))
		{
			foreach ($_POST['list'] as $listItem):
				Yii::log("Creating list item " . $listItem, CLogger::LEVEL_INFO, 'system.test.kim');
				$itm = new MailerContentHasList;
				$itm->mailer_content_id = $id;
				$itm->mailer_list_id = $listItem;
				$itm->save();
			endforeach;
		}
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='mailer-content-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

// Image SID directory ---------------------------------------------------------

    public function getImageDir()
    {
        return Yii::app()->basePath . $this->_imageDir . Yii::app()->session['uid'] . '/';
    }

// Redactor image handling -----------------------------------------------------

    public function actionImageUpload()
    {
        $uploadedFile = CUploadedFile::getInstanceByName('file');
        if (!empty($uploadedFile)) {
            $rnd = rand();  // generate random number between 0-9999
            $fileName = "{$rnd}.{$uploadedFile->extensionName}";  // random number + file name
            if ($uploadedFile->saveAs(Yii::app()->basePath . '/../userdata/' .  Yii::app()->session['uid'] . '/image/' . $fileName)) {

                $array = array(
                     'filelink' => Yii::app()->baseUrl . '/mailer/../userdata/' .  Yii::app()->session['uid'] . '/image/' . $fileName);

                echo stripslashes(json_encode($array));
                Yii::app()->end();
            }
        }
        throw new CHttpException(400, 'The request cannot be fulfilled due to bad syntax');
    }

// "ListImages" (used to browse images in the server)

    public function actionImageList() {
        $images = array();
        $handler = opendir(Yii::app()->basePath . '/../userdata/' . Yii::app()->session['uid'] . '/image');
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..")
                $images[] = $file;
        }
        closedir($handler);

        $jsonArray = array();

        foreach ($images as $image)
            $jsonArray[] = array(
                'thumb' => Yii::app()->baseUrl . '/mailer/userdata/' . Yii::app()->session['uid'] . '/image/' . $image,
                'image' => Yii::app()->baseUrl . '/mailer/userdata/' . Yii::app()->session['uid'] . '/image/' . $image,
            );

        header('Content-type: application/json');
        echo CJSON::encode($jsonArray);
    }

}
