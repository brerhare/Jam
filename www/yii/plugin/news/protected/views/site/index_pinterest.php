<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>

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
.item:hover {
opacity:0.9;
}

.itemleadin {
	display:block;
	font-size: 0.7em;
	padding:4px;
	height:10px;
	color: #989898;
}

.itemintro {
	display:inline-block;
	padding: 5px;
}

.wtf-did-this-hr-take-to-DO {
	display: block; height: 1px;
	border: 0; border-top: 1px solid #ccc;
	margin: 0; padding: 0;
}

.mainitem {
	display:inline-block;
	overflow:hidden;
	padding:0px 12px;
}
/*.mainitem:hover {
opacity:0.85;
}*/
</style>

<div style="width:100%; border:0px solid black">	<!-- test container -->

<div style="padding-top:10px; width:100%" ng-app>

<span style="vertical-align:top; padding:0px 10px 10px; margin:0px 10px; display:inline-block; width:20%; ">

<?php
    // Show the 3 most recent articles
    echo "<div style='font-size:12px'>";
    echo "<span style='padding:5px; background-color:#d3d3d3'>Recent&nbsp&nbsp</span><br/><br/>";
    $criteria = new CDbCriteria;
    $criteria->addCondition("uid=" . Yii::app()->session['uid']);
    $criteria->order = "date DESC";
    $articles = Article::model()->findAll($criteria);
    $cnt = 0;
    if ($articles)
    {
        foreach ($articles as $article)
        {
            echo "<a style='text-decoration:none;color:black' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art=" . $article->id . "'>";
            echo $article->title . "<br/>";
            echo "</a>";
            if ($cnt++ > 3)
                break;
        }
    }
    echo "</div>";
    echo "<br/>";
?>

<?php
	// Show the category list
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "name ASC";
	$categories = Category::model()->findAll($criteria);
	if ($categories)
	{
		echo "<hr>";
		foreach ($categories as $category)
		{
			if ($category->id == $showCat)
				continue;
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=" . $category->id . "&art='>" . $category->name . "</a><br>";
			echo "<hr>";
		}
		if (($showCat != "0") || ((isset($_GET['art'])) && ($_GET['art'] != '')))
		{
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art='>" . 'All' . "</a><br>";
			echo "<hr>";
		}
	}
	// Show the signup form (@@EG calling an addon directly, not via the jelly)
	require(Yii::app()->basePath . "/../scripts/jelly/addon/mailer/signup/signup.php");
	$addon = new signup;
	$optArr = array();
	$optArr['buttoncolor'] = 'white';
	$optArr['buttontextcolor'] = '#a70055';
	$optArr['buttontext'] = 'Sign up';
	$optArr['inputspacing'] = '5px';
	$optArr['successtextcolor'] = 'white';
	$optArr['failuretextcolor'] = 'red';
	$ret = $addon->init($optArr, '/news/scripts/jelly/addon/mailer/signup');
	echo "<br/>";
	echo "<div style='font-size:13px; padding:1px; background-color:lightgrey'>";
	echo "Keep me informed<br/>";
	echo $ret[0];
	echo "</div>";
	echo "<script>" . $ret[1] . "</script>";
//echo"<script>SID='" . $_GET['sid'] . "';</script>";



?>
</span>

<span class="mainitem" style="display:inline-block; width:70%">
<?php
if ((!isset($_GET['art'])) || ($_GET['art'] == ''))
{
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
			echo "<a style='color:black; text-decoration:none' href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art=" . $article->id . "'>";
				echo "<span class='mainitem' style='width:45%'>";
					echo "<img style='width:95%; height:auto' src='" . Yii::app()->baseUrl  . "/userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' >";
				echo "</span>";
				echo "<span class='mainitem' style='width:45%; vertical-align:top; margin:0px;' >";
					echo "<p class='item' style='width:95%; padding:10px'>" . $article->intro . "</p>";
				echo "</span>";
			echo "</a>";
			$mainArticleId = $article->id;
			break;
		}
	}
}
?>

<br/><br/>

<?php
if ((!isset($_GET['art'])) || ($_GET['art'] == ''))
{
	// Show all the other articles
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "date DESC";
	if ($showCat != "0")
		$criteria->addCondition("blog_category_id=" . $showCat);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		echo "<div id='masonry-container' style='display:none; width:100%; padding:12px'; >";
		foreach ($articles as $article)
		{
			if ($article->id == $mainArticleId)
				continue;
			echo "<span class='item' style='margin-bottom:13px;' >";
			echo "<a href='https://plugin.wireflydesign.com/news/index.php/site/play/?cat=0&art=" . $article->id . "'>";
			echo "<img src='" . Yii::app()->baseUrl . "/userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' width='100%'>";
			echo "</a>";

			// Get the category name
			$showCat = "Unknown";
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid=" . Yii::app()->session['uid']);
			$criteria->addCondition("id=" . $article->blog_category_id);
			$category = Category::model()->find($criteria);
			if ($category)
				$showCat = $category->name;

			echo "<span class='itemleadin'>" . $showCat . "&nbsp&nbsp" . $article->date . "</span><hr class='wtf-did-this-hr-take-to-DO'/>";

			echo "<span class='itemintro'>" . $article->intro . "</span><br/>";
			echo "</span>";
		}
		echo '</div>';
	}
}
if ((isset($_GET['art'])) && ($_GET['art'] != ''))
{
    // Show the selected article's detail
    $criteria = new CDbCriteria;
    $criteria->addCondition("uid=" . Yii::app()->session['uid']);
    $criteria->addCondition("id=" . $_GET['art']);
    $article = Article::model()->find($criteria);
    if ($article)
    {
        echo $article->content;
    }

}
?>
</span>
</div>


</div> <!-- test container -->

<script type="text/javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
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
