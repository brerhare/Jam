<?php
	// Show the 3 most recent articles
	echo "<div style='font-size:12px'>";
//echo "<a style='color:black; text-decoration:none' gin.wireflydesign.com/news/index.php/site/play/?cat=" . $category->id . "&art='>" . $category->name . "</a><br>";
	echo "<div style='font-size:16px; padding-bottom:5px;'>Recent</div>";
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "date DESC";
	$articles = Article::model()->findAll($criteria);
	$cnt = 0;
	if ($articles)
	{
		foreach ($articles as $article)
		{
			//echo "<a style='text-decoration:none;color:black' target='_top' href='http:/1staid4u.co.uk/?layout=index&page=news-traditional&cat=0&art=" . $article->id . "'>";
			echo "<a style='text-decoration:none;color:black' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art=" . $article->id . "'>";
			echo $article->title . "<br/>";
			echo "</a>";
			if ($cnt++ > 3)
				break;
		}
	}
	echo "</div>";
	echo "<br/>";

	// Default styling for the signup form (can be changed by the iframe caller)
	$color = '#000000';
	$backColor = '#d3d3d3';

	if ((isset($_GET['color'])) && (trim($_GET['color'] != '')))
		$color = $_GET['color'];
	if ((isset($_GET['backcolor'])) && (trim($_GET['backcolor'] != '')))
		$backColor = $_GET['backcolor'];

	// Show the signup form (@@EG calling an addon directly, not via the jelly)
	require(Yii::app()->basePath . "/../scripts/jelly/addon/mailer/signup/signup.php");
	$addon = new signup;
	$optArr = array();
	$optArr['textcolor'] = $color;
	$optArr['backcolor'] = $backColor;
	$optArr['buttoncolor'] = 'white';
	$optArr['buttontextcolor'] = '#a70055';
	$optArr['buttontext'] = 'Sign up';
	$optArr['inputspacing'] = '5px';
	$optArr['inputwidth'] = '155px';
	$optArr['successtextcolor'] = 'white';
	$optArr['failuretextcolor'] = 'red';
	$ret = $addon->init($optArr, '/news/scripts/jelly/addon/mailer/signup');




	//@@TODO: This is temporarily to disable the background color until we can set it properly in {{name=value}}
	// Also need to remove the XXX in plugin/news/scripts/jelly/addon/signup/signup.php
	echo "<div style='font-size:13px; padding:1px; XXXbackground-color:lightgrey'>";




	echo "Keep me informed<br/>";
	echo $ret[0];
	echo "</div>";
	echo "<script>" . $ret[1] . "</script>";
//echo"<script>SID='" . $_GET['sid'] . "';</script>";

	// Show the category list
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "name ASC";
	$categories = Category::model()->findAll($criteria);
	if ($categories)
	{
		echo "<br/>";
		foreach ($categories as $category)
		{
//			if ($category->id == $showCat)
//				continue;
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=" . $category->id . "&art='>" . $category->name . "</a><br>";
echo "<hr>";
		}
		if (($showCat != "0") || ((isset($_GET['art'])) && ($_GET['art'] != '')))
		{
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art='>" . 'All' . "</a><br>";
			echo "<hr>";
		}
	}

?>

