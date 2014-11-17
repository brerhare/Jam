<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>

<style type="text/css" media="screen">
* {
font-family: Helvetica Neue, Helvetica, Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
.red { font-color:#661010; }
}
.item {
/*float:left;*/
display: inline-block; vertical-align: top;
	width: 28%;
margin:6px;
	overflow:hidden;
	font-size:14px;
	background-color:white;
margin:0px 13px 13px 0px;
}
.item:hover {
opacity:0.9;
}

.itemleadin {
	display:block;
	font-size: 0.7em;
	text-decoration:none;
	padding:4px;
	height:10px;
	color: #989898;
}

.itemintro {
	text-align:left;
	display:inline-block;
	text-decoration:none;
	margin: 0px;
	padding: 0px 5px 5px 5px;
}

.wtf-did-this-hr-take-to-DO {
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
<?php require('_sidebar.php'); ?>
</span>

<span class="mainitem" style="display:inline-block; width:70%">
<?php
if ($showArt == '')
{
	// Show the most recent article
	// ----------------------------
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "date DESC, id DESC";
	if ($showCat != "0")
		$criteria->addCondition("blog_category_id=" . $showCat);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		foreach ($articles as $article)
		{
			echo "<a style='color:black; text-decoration:none' href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['http_referer'] . "/?art=" . $article->id . '&page=' . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", $article->title)    . '"' . ")'>";

//print_r(Yii::app()->session['http_referer']);
//die;

				echo "<center>";
					echo "<span class='mainitem' style='width:95%'>";
						echo "<img style='width:90%; height:300px; width:auto' src='" . Yii::app()->baseUrl  . "/userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' >";

						// Get the category name
						$catDesc = "Unknown";
						$criteria = new CDbCriteria;
						$criteria->addCondition("uid=" . Yii::app()->session['uid']);
						$criteria->addCondition("id=" . $article->blog_category_id);
						$category = Category::model()->find($criteria);
						if ($category)
							$catDesc = $category->name;
						echo "<span class='itemleadin'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</span>";

					echo "</span>";
echo "<br/>";
					echo "<span class='mainitem' style='width:95%; vertical-align:top; margin:0px;' >";

						echo "<p class='item' style='width:90%; padding:10px; padding-top: 2px; padding-bottom:0px; margin-bottom:0px; font-weight:bold; color:#424242'>" . $article->title . "</p>";
						echo "<p class='item' style='width:90%; padding:10px; padding-top:5px'>" . $article->intro . "</p>";
					echo "</span>";
				echo "</center>";
			echo "</a>";
			$mainArticleId = $article->id;
			break;
		}
	}
}
?>

<?php
if ($showArt == '')
{
	// Show all the other articles
	// ---------------------------
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "date DESC, id DESC";
	if ($showCat != "0")
		$criteria->addCondition("blog_category_id=" . $showCat);
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		echo "<div style='width:100%; padding:12px'; >";
		foreach ($articles as $article)
		{
			if ($article->id == $mainArticleId)
				continue;
			echo "<a href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['http_referer'] . "/?art=" . $article->id . '&page=' . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", $article->title)    . '"' . ")'>";
				echo "<span class='item' style='text-align:center;' >";
					// This is centered, shrink-to-fit
					echo "<img style='max-width:100%; height:140px; overflow:hidden;' src='" . Yii::app()->baseUrl . "/userdata/" . Yii::app()->session['uid'] . "/" . $article->thumbnail_path .  "' alt='No Image' Xwidth='100%'>";

					// Get the category name
					$catDesc = "Unknown";
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid=" . Yii::app()->session['uid']);
					$criteria->addCondition("id=" . $article->blog_category_id);
					$category = Category::model()->find($criteria);
					if ($category)
						$catDesc = $category->name;

					echo "<span class='itemleadin'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</span>";

					echo "<p class='itemintro' style='padding-top:2px; font-weight:bold; color:#424242'>" . $article->title . "</p>";
					echo "<span class='itemintro' style='padding-top:2px; color:#000000'>" . $article->intro . "</span>";
				echo "</span>";
			echo "</a>";
		}
		echo '</div>';
	}
}
if ($showArt != '')
{
	echo $showContent;
}
?>
</span>
</div>


</div> <!-- test container -->

<script type="text/javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
<script>

$(document).ready(function() {
});

// @@NB START POSTMESSAGE
function pM(type, param)
{
	parent.postMessage(type + "^" + param, "*");
}
// @@NB END POSTMESSAGE

</script>
