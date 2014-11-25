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
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

		// Store the referer (hosting site) in a session cookie
		if ((!isset(Yii::app()->session['http_referer'])) || (Yii::app()->session['http_referer'] == "unknown http_referer"))
		{
			$referer = "unknown http_referer";
			if (isset($_SERVER['HTTP_REFERER']))
				$referer = $_SERVER['HTTP_REFERER'];
			$protoArr = explode(":", $referer);	// eg 'http' or 'https'
			$referer = str_replace("https://", "", $referer);
			$referer = str_replace("http://", "", $referer);
        	$refArr = explode("/", $referer);
			Yii::app()->session['http_referer'] = $protoArr[0] . "://" . $refArr[0];
		}

		// Set the news type (blog format)
		if (!(isset(Yii::app()->session['news_type'])))
			Yii::app()->session['news_type'] = 'traditional';
		if (isset($_GET['newstype']))
			Yii::app()->session['news_type'] = $_GET['newstype'];
		if (isset($_GET['parenturl']))
			Yii::app()->session['parenturl'] = $_GET['parenturl'];

		// If page is there but null, we have to look up the home page
		if ((isset($_GET['page'])) && ($_GET['page'] != ""))
				Yii::app()->session['page'] = $_GET['page'];
			else
			{
				$criteria = new CDbCriteria;
				$criteria->addCondition("home = " . 1);
				$contentBlock = ContentBlock::model()->find($criteria);
				if ($contentBlock)
					Yii::app()->session['page'] = $contentBlock->url;
			}
		}

		if (isset($_GET['art']))
			$this->actionPlay();

		$category = 0;
		if (isset($_GET['cat']))
			$category = $_GET['cat'];
		$this->renderPartial('index',array(
			'showCat'=>$category,
			'showArt'=>'',
		));
	}

	/**
	 * This is the 'index' action that is invoked
	 * when an article is explicitly requested by users.
	 */
	public function actionPlay()
	{
		if ((isset($_GET['art'])) && ($_GET['art'] != ""))
			$this->actionResolveParentSiteGalleryAddon(0);

		// If we get back here then there was no {{gallery-lightbox}} curly
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
            	$content = $this->populateArticleHeading($article) . $article->content;
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
			if (!(strstr($article->content, "{{gallery-lightbox")))
				return;

           	$content = $this->populateArticleHeading($article) . $article->content;
		}

		// Extract the {{...}} part of the content, storing the preceding and following strings for later reassembly
		$pre = strstr($content, "{{", true);
		$tag = strstr($content, "{{");
		$tag = strstr($tag, "}}", true) . "}}";
		$post = strstr($content, "}}");
		$post = str_replace("}}", "", $post);
		Yii::app()->session['stash_pre'] = $pre;
		Yii::app()->session['stash_post'] = $post;

		$url = Yii::app()->session['parenturl'] . "/index.php/site/pluginGalleryAddonCallback?&content=" . urlencode($tag);
		$this->redirect($url);
	}

	public function actionResolveParentSiteGalleryAddonReturn()
	{
		$fileName = $_GET['filename'];
		$content = file_get_contents($fileName);

		$util = new Util;

//		$decryptedContent = $util->decrypt($content);
$decryptedContent = $content;

		$content =  Yii::app()->session['stash_pre'] . $decryptedContent .  Yii::app()->session['stash_post'];
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

	private function populateArticleHeading($article)
	{
		$content = "";

		// Get the category name
		$catDesc = "Unknown";
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid=" . Yii::app()->session['uid']);
		$criteria->addCondition("id=" . $article->blog_category_id);
		$category = Category::model()->find($criteria);
		if ($category)
			$catDesc = $category->name;

		$content .= "<div>";
			$content .= "<table><tr>";
				$content .= "<td width='75%'>";
					$content .= "<div style='font-size:1.2em; font-weight:bold; color:#424242'>" . $article->title . "</div>";
					$content .= "<div style='font-size:0.9em; padding-top:5px; height:12px; color:#989898'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</div>";
				$content .= "</td><td width='25%'>";
				$content .= "<img style='width:150px; height:auto' src='" . Yii::app()->baseUrl  . "/userdata/" . Yii::app()->session['uid'] . "/thumb_" . $article->thumbnail_path .  "' alt='No Image' >";
				$content .= "</td>";
			$content .= "</tr></table>";
		$content .= "</div>";

		return $content;
	}

}
