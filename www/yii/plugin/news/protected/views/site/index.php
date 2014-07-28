<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>

<style type="text/css" media="screen">

</style>
<div style="width:100%" ng-app>

<span style="vertical-align:top; background-color:pink; display:inline-block; width:23%; ">
<?php
	// Show the category list
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "name ASC";
	$categories = Category::model()->findAll($criteria);
	if ($categories)
	{
		foreach ($categories as $category)
		{
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/play/?cat=" . $category->id . "'>" . $category->name . "</a><br>";
		}
	}
?>
</span>

<span style="background-color:yellow; display:inline-block; width:75%">
<?php
	// Show the most recent article
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	//$criteria->order = "date DESC";
	//if (($catId != "") && ($catId != "0"))
	//$criteria->addCondition("blog_category_id=" . $catId);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		foreach ($articles as $article)
		{
			echo "<img src='userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' width='50%'>";
			echo "<br>" . $article->intro . "<br>";
			$mainArticleId = $article->id;
			break;
		}
	}
?>
</span>

<br/><br/>

<span style="background-color:yellow; display:inline-block; width:75%">
<?php
	// Show all the other articles
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	//$criteria->order = "date DESC";
	//if (($catId != "") && ($catId != "0"))
	//$criteria->addCondition("blog_category_id=" . $catId);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		foreach ($articles as $article)
		{
			if ($article->id == $mainArticleId)
				continue;
			echo "<span style='width:30%; margin-right:3px; overflow:hidden; float:left'>";
			echo "<img src='userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' width='100%'>";
			echo $article->intro . "<br/>";
			echo "</span>";
		}
	}
?>
</span>

<?php //var_dump($_GET); ?>

</div>

