<?php

class SiteController extends Controller
{
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
		// Set the news type (blog format)
		Yii::app()->session['news_type'] = 'traditional';
		if (isset($_GET['newstype']))
			Yii::app()->session['news_type'] = $_GET['newstype'];
		if (isset($_GET['parenturl']))
			Yii::app()->session['parenturl'] = $_GET['parenturl'];

		$category = 0;
		$this->renderPartial('index',array(
			'showCat'=>$category,
			'showArt'=>'',
		));
	}

	/**
	 * This is the 'index' action that is invoked
	 * when a category is explicitly requested by users.
	 */
	public function actionPlay()
	{
		if ((isset($_GET['art'])) && ($_GET['art'] != ""))
			$this->actionResolveParentSiteGalleryAddon(0);

		// If we get back here then there was no {{gallery}} curly
		$cat = '';
		$art = '';
		$content = '';
		if (isset($_GET['cat']))
			$cat = $_GET['cat'];
		if (isset($_GET['art']))
			$art = $_GET['art'];

        // Show the selected article's detail
		if ($art != "")
		{
        	$criteria = new CDbCriteria;
        	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
        	$criteria->addCondition("id=" . $art);
        	$article = Article::model()->find($criteria);
        	if ($article)
            	$content = $article->content;
		}

		$this->renderPartial('index',array(
			'showCat'=>$cat,
			'showArt'=>$art,
			'showContent' => $content,
		));
	}


	// ------------------------------------------------- Addon resolver code starts --------------------------------------->
	public function actionResolveParentSiteGalleryAddon($repeat, $repeatContent="")
	{
		$content = $repeatContent;

		if ($repeat == 0)
		{
			// Stash the $_GETs
			$cat = '';
			$art = '';
			if (isset($_GET['cat']))
				$cat = $_GET['cat'];
			if (isset($_GET['art']))
				$art = $_GET['art'];
			Yii::app()->session['stash_cat'] = $cat;
			Yii::app()->session['stash_art'] = $art;

    		// Show the selected article's detail
    		$criteria = new CDbCriteria;
    		$criteria->addCondition("uid=" . Yii::app()->session['uid']);
    		$criteria->addCondition("id=" . Yii::app()->session['stash_art']);
    		$article = Article::model()->find($criteria);
			if (!($article))
				return;
			if (!(strstr($article->content, "{{gallery")))
				return;

			$content = $article->content;
		}
		$url = Yii::app()->session['parenturl'] . "/index.php/site/pluginGalleryAddonCallback?&content=" . urlencode($content);
		$this->redirect($url);
	}

	public function actionResolveParentSiteGalleryAddonReturn()
	{
		$content = $_GET['content'];

		$util = new Util;
		$content = $util->decrypt($content);

		//if (strstr($content, "{{"))
			//$this->redirect(array('resolveParentSiteGalleryAddon', 'repeat' => '1', 'repeatContent' => $content));

		$this->renderPartial('index',array(
			'showCat' => Yii::app()->session['stash_cat'],
			'showArt' => Yii::app()->session['stash_art'],
			'showContent' => $content,
		));
	}
	// <------------------------------------------- Addon resolver code ends ----------------------------------------------


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
	public function actionContact($id)
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
}
