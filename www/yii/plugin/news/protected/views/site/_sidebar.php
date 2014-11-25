<?php

	// Show the 3 most recent articles
	// -------------------------------
	echo "<style>
			 .uline {
				color:black;
				text-decoration: none;
			 }
			 .uline:hover {
				text-decoration: underline;
			 }
		</style>";

	echo "<div style='max-height:130px; padding-bottom:0px; overflow:hidden;  font-size:12px'>";

	echo "<div style='font-size:16px; padding-bottom:5px;'>";
		echo "<a class='uline' href='http://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art='>" . 'Recent' . "</a><br>";
	echo "</div style='border:1px solid black;' >";
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid=" . Yii::app()->session['uid']);
		$criteria->order = "date DESC, id DESC";
		$articles = Article::model()->findAll($criteria);
		$cnt = 0;
		if ($articles)
		{
			foreach ($articles as $article)
			{
				echo "<img src='/news/img/gray-circle.png' height='5px' width='5px' style='padding:0px 4px 2px 0px;'/>";

				echo "<a class='uline' href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['parenturl'] . "/?art=" . $article->id .       "&page=" . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", $article->title)          . '"' . ")'>";

				echo $article->title . "<br/>";
				echo "</a>";
				if ($cnt++ >= 2)
					break;
			}
		}
	echo "</div>";

	echo "<div style='height:18px'></div>";

	// Default styling for the signup form (can be changed by the iframe caller)
	$color = '#000000';
	$backColor = '#137feb';

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
	$optArr['inputwidth'] = '147px';
	$optArr['successtextcolor'] = 'white';
	$optArr['failuretextcolor'] = 'red';
	$ret = $addon->init($optArr, '/news/scripts/jelly/addon/mailer/signup');

	echo "<div style='font-size:13px; padding:5px; background-color:" . $backColor  . "'>";
		echo "Keep me informed<br/>";
		echo $ret[0];
	echo "</div>";

	echo "<script>" . $ret[1] . "</script>";

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
			echo "<a style='color:black; text-decoration:none' href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['parenturl'] . "/?cat=" . $category->id .       "&page=" . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", $category->name)          . '"' . ")'>" . $category->name . "</a><br>";

echo "<hr>";

		}
		echo "<a style='color:black; text-decoration:none' href='http://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art='>" . 'All' . "</a><br>";
		echo "<hr>";
	}

?>

