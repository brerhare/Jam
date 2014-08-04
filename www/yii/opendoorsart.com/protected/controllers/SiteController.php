<?php

class SiteController extends Controller
{
	private $_imageDir = '/../userdata/image/';

	/**
	 * Get the jelly script root (as defined in /protected/config/main.php)
	 */
	private function getJellyRoot()
	{
		return Yii::app()->params['jellyRoot'];
	}

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$layout = "index";
		if (isset($_GET['layout']))
			$layout = $_GET['layout'];
		$parseConfig = new ParseConfig();
		$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . $layout . ".jel");
		if (!($jellyArray))
			throw new Exception('Aborting');

		$jelly = new Jelly;
		$jelly->processData($jellyArray,$this->getJellyRoot());
		$jelly->outputData();

		//$this->render('index');
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionPlay($page)
	{
		$parseConfig = new ParseConfig();
		$jellyArray = $parseConfig->parse(Yii::app()->basePath . "/../" . $this->getJellyRoot() . $page . '.jel');
		if (!($jellyArray))
			throw new Exception('Aborting');

		$jelly = new Jelly;
		$jelly->processData($jellyArray,$this->getJellyRoot());
		$jelly->outputData();

	}



	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    public function actionAjaxSignup()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $retArr = array();
			$retArr['error'] = "";

            Yii::log("AJAX CALL (signup): username=" . $_POST['username'] . " password=" . $_POST['password'], CLogger::LEVEL_WARNING, 'system.test.kim');

			// Store details for the eventual submit
			Yii::app()->session['username'] = $_POST['username'];
			Yii::app()->session['password'] = $_POST['password'];

			// Ensure username is not already taken
			$criteria = new CDbCriteria;
			$criteria->addCondition("username = '" . $_POST['username'] . "'");
			$member = Member::model()->find($criteria);
			if ($member)
			{
				$retArr['error'] = "Username already taken";
				echo CJSON::encode($retArr);
				return;
			}

			$retArr['mode'] = "signup";

			$retArr['businessName'] = "";
			$retArr['address1'] =  "";
			$retArr['address2'] = "";
			$retArr['address3'] = "";
			$retArr['address4'] = "";
			$retArr['postCode'] = "";
			$retArr['contact'] = "";
			$retArr['web'] = "";
			$retArr['email'] = "";
			$retArr['phone'] = "";
			$retArr['openingHours'] = "";
			$retArr['htmlContent'] = "";
			$retArr['logoPath'] = "";
			$retArr['sliderImagePath'] = "";
			$retArr['public'] = "";

			$categories = Category::model()->findAll();
			$catCount = 0;
			foreach ($categories as $category)
			{
				$catStr = 'category_' . $catCount;
				$retArr[$catStr]['id'] = $category->id;
				$retArr[$catStr]['name'] = $category->name;
				$retArr[$catStr]['checked'] = 0;
				$catCount++;
			}
			$retArr['categoryCount'] = $catCount;

			$foodtypes = FoodType::model()->findAll();
			$ftCount = 0;
			foreach ($foodtypes as $foodtype)
			{
				$ftStr = 'foodtype_' . $ftCount;
				$retArr[$ftStr]['id'] = $foodtype->id;
				$retArr[$ftStr]['name'] = $foodtype->name;
				$retArr[$ftStr]['checked'] = 0;
				$ftCount++;
			}
			$retArr['foodtypeCount'] = $ftCount;

			echo CJSON::encode($retArr);
		}
	}

    public function actionAjaxLogin()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $retArr = array();
			$retArr['error'] = "";

            Yii::log("AJAX CALL (login): username=" . $_POST['username'] . " password=" . $_POST['password'], CLogger::LEVEL_WARNING, 'system.test.kim');

			// Store details for the eventual submit
			Yii::app()->session['username'] = $_POST['username'];
			Yii::app()->session['password'] = $_POST['password'];

			// Ensure user exists
			$criteria = new CDbCriteria;
			$criteria->addCondition("username = '" . $_POST['username'] . "'");
			$member = Member::model()->find($criteria);
			if (!$member)
			{
				$retArr['error'] = "User does not exist";
				echo CJSON::encode($retArr);
				return;
			}
			if ($member->password != $_POST['password'])
			{
				$retArr['error'] = "Invalid username or password";
				echo CJSON::encode($retArr);
				return;
			}

			$retArr['mode'] = "login";

			$retArr['businessName'] = $member->business_name;
			$retArr['address1'] = $member->address1;
			$retArr['address2'] = $member->address2;
			$retArr['address3'] = $member->address3;
			$retArr['address4'] = $member->address4;
			$retArr['postCode'] = $member->postcode;
			$retArr['contact'] = $member->contact;
			$retArr['web'] = $member->web;
			$retArr['email'] = $member->email;
			$retArr['phone'] = $member->phone;
			$retArr['openingHours'] = $member->opening_hours;
			$retArr['htmlContent'] = $member->html_content;
			$retArr['logoPath'] = $member->logo_path;
			$retArr['sliderImagePath'] = $member->slider_image_path;
			$retArr['public'] = $member->public;

			$categories = Category::model()->findAll();
			$catCount = 0;
			foreach ($categories as $category)
			{
				$catStr = 'category_' . $catCount;
				$retArr[$catStr]['id'] = $category->id;
				$retArr[$catStr]['name'] = $category->name;
				$criteria = new CDbCriteria;
            	$criteria->addCondition("category_id = " . $category->id);
            	$criteria->addCondition("member_id = " . $member->id);
            	$memberHasCategory = MemberHasCategory::model()->find($criteria);
            	if ($memberHasCategory)
					$retArr[$catStr]['checked'] = 1;
				else
					$retArr[$catStr]['checked'] = 0;
				$catCount++;
			}
			$retArr['categoryCount'] = $catCount;

			$foodtypes = FoodType::model()->findAll();
			$ftCount = 0;
			foreach ($foodtypes as $foodtype)
			{
				$ftStr = 'foodtype_' . $ftCount;
				$retArr[$ftStr]['id'] = $foodtype->id;
				$retArr[$ftStr]['name'] = $foodtype->name;
				$criteria = new CDbCriteria;
            	$criteria->addCondition("food_type_id = " . $foodtype->id);
            	$criteria->addCondition("member_id = " . $member->id);
            	$memberHasFoodtype = MemberHasFoodType::model()->find($criteria);
            	if ($memberHasFoodtype)
					$retArr[$ftStr]['checked'] = 1;
				else
					$retArr[$ftStr]['checked'] = 0;
				$ftCount++;
			}
			$retArr['foodtypeCount'] = $ftCount;

			echo CJSON::encode($retArr);
		}
	}

    public function actionAjaxEdit()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
return;
            $retArr = array();
			$retArr['error'] = "";

            Yii::log("AJAX CALL (edit)", CLogger::LEVEL_WARNING, 'system.test.kim');

			if (trim($_POST['editMode'] == ""))
			{
				$retArr['error'] = "Internal error - no 'mode' given to actionAjaxEdit()";
				echo CJSON::encode($retArr);
				return;
			}
			if ($_POST['editMode'] == 'login')
            {
                // Fetch original member details
				$criteria = new CDbCriteria;
				$criteria->addCondition("username = '" . $_POST['username'] . "'");
				$member = Member::model()->find($criteria);
				if (!$member)
				{
					$retArr['error'] = "User does not exist";
					echo CJSON::encode($retArr);
					return;
				}
            }
			else
				$member = new Member;

			$retArr['id'] = 0;
			$member->username = $_POST['username'];
			$member->password = $_POST['password'];
			$member->business_name = $_POST['businessName'];
			$member->address1 = $_POST['address1'];
			$member->address2 = $_POST['address2'];
			$member->address3 = $_POST['address3'];
			$member->address4 = $_POST['address4'];
			$member->postcode = $_POST['postCode'];
			$member->contact = $_POST['contact'];
			$member->web = $_POST['web'];
			$member->email = $_POST['email'];
			$member->phone = $_POST['phone'];
			$member->opening_hours = $_POST['openingHours'];
			$member->html_content = $_POST['htmlContent'];
			$member->logo_path = $_POST['logoPath'];
			$member->slider_image_path = $_POST['sliderImagePath'];
			$member->public = $_POST['public'];

			if (!$member->save())
			{
				$retArr['error'] = "Error saving member record";
				echo CJSON::encode($retArr);
				return;
			}

			// Reconstruct all member categories
			if ($_POST['editMode'] == 'login')
				MemberHasCategory::model()->deleteAllByAttributes(array('member_id' => $member->id));
			$list = $_POST['cats'];		// id_checked|1_0|23_0|7_1
Yii::log("AJAX CALL (cats)" . $list, CLogger::LEVEL_WARNING, 'system.test.kim');
			$pairs = explode("|", $list);
			for ($i = 0; $i < count($pairs); $i++)
			{
				$item = explode("_", $pairs[$i]);
				if ($item[1] == 'true')
				{
					$memberHasCategory = new MemberHasCategory;
					$memberHasCategory->member_id = $member->id;
					$memberHasCategory->category_id = $item[0];
					if (!$memberHasCategory->save())
            		{
                		$retArr['error'] = "Error saving member category record";
                		echo CJSON::encode($retArr);
                		return;
            		}
				}
			}

			// Reconstruct all member food types
			if ($_POST['editMode'] == 'login')
				MemberHasFoodType::model()->deleteAllByAttributes(array('member_id' => $member->id));
			$list = $_POST['fts'];		// id_checked|1_0|23_0|7_1
Yii::log("AJAX CALL (fts)" . $list, CLogger::LEVEL_WARNING, 'system.test.kim');
			$pairs = explode("|", $list);
			for ($i = 0; $i < count($pairs); $i++)
			{
				$item = explode("_", $pairs[$i]);
				if ($item[1] == 'true')
				{
					$memberHasFoodType = new MemberHasFoodType;
					$memberHasFoodType->member_id = $member->id;
					$memberHasFoodType->food_type_id = $item[0];
					if (!$memberHasFoodType->save())
            		{
                		$retArr['error'] = "Error saving member food type record";
                		echo CJSON::encode($retArr);
                		return;
            		}
				}
			}

			echo CJSON::encode($retArr);
		}
	}

    public function actionSubmit()
    {
            Yii::log("SUBMIT CALL (submit-start)", CLogger::LEVEL_WARNING, 'system.test.kim');
ob_start();
var_dump($_POST);
var_dump($_FILES);
$output = ob_get_contents();
ob_end_clean();
            Yii::log("SUBMIT CALL (submit-dump):" . $output, CLogger::LEVEL_WARNING, 'system.test.kim');

			if (trim($_POST['editMode'] == ""))
			{
				die("Internal error - no 'mode' given to actionAjaxEdit()");
			}

			// Handle any uploaded files
			$fileLogoPath = "";
			$fileSliderImagePath = "";
			$fileRand = rand();
			if (isset($_FILES['editLogoPath']))
			{
				$list = $_FILES['editLogoPath'];
				if ($_FILES["editLogoPath"]["error"] == UPLOAD_ERR_OK)
				{
					$tmp_name = $_FILES["editLogoPath"]["tmp_name"];
					$fileLogoPath = $fileRand . '_' . $_FILES["editLogoPath"]["name"];
					move_uploaded_file($tmp_name, Yii::app()->basePath . $this->_imageDir . "logo/" . $fileLogoPath);
				}
			}
			if (isset($_FILES['editSliderImagePath']))
			{
				$list = $_FILES['editSliderImagePath'];
				if ($_FILES["editSliderImagePath"]["error"] == UPLOAD_ERR_OK)
				{
					$tmp_name = $_FILES["editSliderImagePath"]["tmp_name"];
					$fileSliderImagePath = $fileRand . '_' . $_FILES["editSliderImagePath"]["name"];
					move_uploaded_file($tmp_name, Yii::app()->basePath . $this->_imageDir . "slider/" . $fileSliderImagePath);
				}
			}

			if ($_POST['editMode'] == 'login')
            {
                // Fetch original member details
				$criteria = new CDbCriteria;
				$criteria->addCondition("username = '" . Yii::app()->session['username'] . "'");
				$member = Member::model()->find($criteria);
				if (!$member)
				{
					die("User does not exist");
				}
            }
			else
				$member = new Member;

			$member->username = Yii::app()->session['username'];
			$member->password = Yii::app()->session['password'];
			$member->business_name = $_POST['editBusinessName'];
			$member->address1 = $_POST['editAddress1'];
			$member->address2 = $_POST['editAddress2'];
			$member->address3 = $_POST['editAddress3'];
			$member->address4 = $_POST['editAddress4'];
			$member->postcode = $_POST['editPostCode'];
			$member->contact = $_POST['editContact'];
			$member->web = $_POST['editWeb'];
			$member->email = $_POST['editEmail'];
			$member->phone = $_POST['editPhone'];
			$member->opening_hours = $_POST['editOpeningHours'];
			$member->html_content = $_POST['editHtmlContent'];
			$member->public = $_POST['editPublic'];
			if (trim($fileLogoPath) != "")
			{
				if (trim($member->logo_path) != "")
				{
					if (file_exists(Yii::app()->basePath . $this->_imageDir . "logo/" . $member->logo_path))
						unlink(Yii::app()->basePath . $this->_imageDir . "logo/" . $member->logo_path);
				}
				$member->logo_path = $fileLogoPath;
			}
			if (trim($fileSliderImagePath) != "")
			{
				if (trim($member->slider_image_path) != "")
				{
					if (file_exists(Yii::app()->basePath . $this->_imageDir . "slider/" . $member->slider_image_path))
						unlink(Yii::app()->basePath . $this->_imageDir . "slider/" . $member->slider_image_path);
				}
				$member->slider_image_path = $fileSliderImagePath;
			}

			if (!$member->save())
			{
				die("Error saving member record");
			}

			// Reconstruct all member categories
			if ($_POST['editMode'] == 'login')
				MemberHasCategory::model()->deleteAllByAttributes(array('member_id' => $member->id));
			if (isset($_POST['editCategory']))
			{
				$list = $_POST['editCategory'];
				foreach ($list as $n=>$v)
				{
					$memberHasCategory = new MemberHasCategory;
					$memberHasCategory->member_id = $member->id;
					$memberHasCategory->category_id = $v;
					if (!$memberHasCategory->save())
       				{
              			die("Error saving member category record");
					}
				}
			}

			// Reconstruct all member food types
			if ($_POST['editMode'] == 'login')
				MemberHasFoodType::model()->deleteAllByAttributes(array('member_id' => $member->id));
			if (isset($_POST['editFoodtype']))
			{
				$list = $_POST['editFoodtype'];
				foreach ($list as $n=>$v)
				{
					$memberHasFoodType = new MemberHasFoodType;
					$memberHasFoodType->member_id = $member->id;
					$memberHasFoodType->food_type_id = $v;
					if (!$memberHasFoodType->save())
           			{
               			die("Error saving member food type record");
           			}
				}
			}


			// @@NB: FIX THIS!!!!!! usual redirect goes to fadguide.wireflydesign.com/index.php/site/index which screws the jelly image location dir /img
			$this->redirect('http://www.opendoorsart.com');
	}


}
