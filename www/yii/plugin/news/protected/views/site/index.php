 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<!-- <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script> -->

<style type="text/css" media="screen">
* {
font-family: Helvetica Neue, Helvetica, Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
.red { font-color:#661010; }
}
.item {
	width: 28%;
	border: 1px solid #d7d7d7;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	overflow:hidden;
	font-size:14px;
	background-color:white;
	-moz-box-shadow:    1px 1px 0px 0px #c8c8c8;
	-webkit-box-shadow: 1px 1px 0px 0px #c8c8c8;
	box-shadow:         1px 1px 0px 0px #c8c8c8;
}
.itemintro {
	display:inline-block;
	padding:10px 5px;
}
.mainitem {
	display:inline-block;
	overflow:hidden;
	padding:0px 12px;
}
</style>

<div style="padding-top:10px; background-color:#e9e9e9; width:750px" ng-app>

<span style="vertical-align:top; padding:0px 10px 10px; margin:0px 10px; display:inline-block; width:20%; ">
<?php
	// Show the category list
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "name ASC";
	$categories = Category::model()->findAll($criteria);
	if ($categories)
	{
		echo "<h3>Category</h3>";
		foreach ($categories as $category)
		{
			if ($category->id == $showCat)
				continue;
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=" . $category->id . "'>" . $category->name . "</a><br>";
		}
		if ($showCat != "0")
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0'>" . 'All' . "</a><br>";
	}
?>
</span>

<span class="mainitem" style="width:70%">
<?php
	// Show the most recent article
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "date DESC";
	if ($showCat != "0")
		$criteria->addCondition("blog_category_id=" . $showCat);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		foreach ($articles as $article)
		{
			echo "<a href='#'>";
			echo "<img style='max:width:330px; /*max-height:220px*/' src='" . Yii::app()->baseUrl  . "/userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' width='50%'>";
			echo "</a>";
			echo "<br>" . $article->intro . "<br>";
			$mainArticleId = $article->id;
			break;
		}
	}
?>

<br/><br/>

<?php
	// Show all the other articles
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "date DESC";
	if ($showCat != "0")
		$criteria->addCondition("blog_category_id=" . $showCat);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		//echo "<div id='container' style='padding:12px'; class='js-masonry' data-masonry-options='{ \"gutter\": 13,  \"itemSelector\": \".item\" }'>";
		echo "<div id='masonry-container' style='display:none; padding:12px'; >";
		foreach ($articles as $article)
		{
			if ($article->id == $mainArticleId)
				continue;
			//echo "<span style='font-size:15; display:inline-block; width:30%; vertical-align:bottom; margin-bottom:20px; margin-right:12px; overflow:hidden; '>";
			echo "<span class='item' style='margin-bottom:13px;' >";
			echo "<a href='#'>";
			echo "<img src='" . Yii::app()->baseUrl . "/userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' width='100%'>";
			echo "</a>";
			echo "<span class='itemintro'>" . $article->intro . "</span><br/>";
			echo "</span>";
		}
		echo '</div>';
	}
?>
</span>
<div style="float:clear"></div>

<?php //var_dump($_GET); ?>

</div>

<!-- @@NB iframe resizer hardcode here -->
<script type="text/javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
<!-- Iframe resizer -->
<script>

$(document).ready(function() {

	// initialize Masonry after all images have loaded
	var container = document.querySelector('#masonry-container');
	var msnry;
	imagesLoaded( container, function() {
		document.getElementById('masonry-container').style.display = 'block';
  		msnry = new Masonry( container, {
			// Options
			itemSelector: '.item',
			gutter: 13
		});
	});

});

</script>
<script src="/js/masonry.pkgd.min.js"></script>
<script src="/js/imagesloaded.pkgd.min.js"></script>
