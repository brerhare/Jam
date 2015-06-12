<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
<link href="/news/css/oldernewsbutton.css" rel="stylesheet"/>

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
	margin-top: 7px;
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

<?php
// The sidebar is initially invisible
require('_sidebar.php');
if (!isset(Yii::app()->session['sidebar']))
    Yii::app()->session['sidebar'] = "left";
?>

<?php
if (Yii::app()->session['sidebar'] == 'left')
{
	echo "<span id='sideleft' style='vertical-align:top; padding:0px 10px 10px; margin:0px 10px; display:inline-block; width:20%; '>";
	echo "</span>";
	echo "<script> document.getElementById('sideleft').innerHTML = document.getElementById('sidebar').innerHTML; </script>";
}
?>

<span class="mainitem" style="display:inline-block; width:70%">

<?php
if (!(isset($article)))
{
	die;
}
$nextPageDate = "";
$nextPageItem = "";
$mainArticleId = $article->id;
if ( ($showArt == '') && (!isset($_GET['archive'])) )
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
			// Ignore future dates
			if (date("Y-m-d", strtotime($article->date)) > date("Y-m-d"))
				continue;

			echo "<a style='color:black; text-decoration:none' href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['parenturl'] . "/?art=" . $article->id . '&page=' . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", urlencode($article->title))    . '"' . ")'>";

//print_r(Yii::app()->session['parenturl']);
//die;

				echo "<center>";
					echo "<span class='mainitem' style='width:95%'>";
						echo "<img style='width:90%; height:300px; width:auto' src='" . Yii::app()->baseUrl  . "/userdata/" . Yii::app()->session['uid'] . "/thumb_" . $article->thumbnail_path .  "' alt='No Image' >";

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

						echo "<p class='item' style='width:90%; padding:10px; padding-top: 2px; padding-bottom:0px; margin-bottom:0px; font-weight:bold; color:#424242'>" . mb_convert_encoding($article->title, "HTML-ENTITIES", "UTF-8") . "</p>";
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
	if (isset($_GET['archive']))
	{
		$nextArr = explode("|", $_GET['archive']);
		$id = $nextArr[0];
		$date = date('Y-m-d', strtotime($nextArr[1]));
		$criteria->addCondition("id <="  . $id);
		$criteria->addCondition("date <='" . $date . "'");
	}
	$displayCount = 1;
	$maxDisplay = $maxDisplayItems;
	if (isset($_GET['archive']))
		$maxDisplay = $maxDisplayArchiveItems;
	$articles = Article::model()->findAll($criteria);
	if ($articles)
	{
		echo "<div style='width:100%; padding:12px'; >";
		foreach ($articles as $article)
		{
			if ($article->id == $mainArticleId)
				continue;

			// Ignore future dates
			if (date("Y-m-d", strtotime($article->date)) > date("Y-m-d"))
				continue;

			if (++$displayCount > $maxDisplay)
			{
				$nextPageItem = strval($article->id);
				$nextPageDate = $article->date;
				break;
			}

			// Get the category name
			$catDesc = "No category";
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid=" . Yii::app()->session['uid']);
			$criteria->addCondition("id=" . $article->blog_category_id);
			$category = Category::model()->find($criteria);
			if ($category)
				$catDesc = $category->name;

			if (!isset($_GET['archive']))
			{
				// Main page large grid format
				// ---------------------------
				echo "<a href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['parenturl'] . "/?art=" . $article->id . '&page=' . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", urlencode($article->title))    . '"' . ")'>";
					echo "<span class='item' style='text-align:center;' >";

						// @@EG: vertically align img in div
						echo "<div style='height:140px; width:154px; text-align: center; margin:1em 0; margin-bottom:0px'>";
							echo "<span style='display: inline-block; height: 100%; vertical-align: middle;'></span>";
							echo "<img style='max-width:154px; max-height:140px; vertical-align:bottom; overflow:hidden;' src='" . Yii::app()->baseUrl . "/userdata/" . Yii::app()->session['uid'] . "/thumb_" . $article->thumbnail_path .  "' alt='No Image'>";
						echo "</div>";

						echo "<span class='itemleadin'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</span>";

						echo "<p class='itemintro' style='padding-top:2px; font-weight:bold; color:#424242'>" . mb_convert_encoding($article->title, "HTML-ENTITIES", "UTF-8") . "</p>";
						echo "<span class='itemintro' style='padding-top:2px; color:#000000'>" . mb_convert_encoding($article->intro, "HTML-ENTITIES", "UTF-8") . "</span>";
					echo "</span>";
				echo "</a>";
			}
			else
			{
				// Archive page smaller grid format
				// --------------------------------
				echo "<a href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['parenturl'] . "/?art=" . $article->id . '&page=' . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", urlencode($article->title))    . '"' . ")'>";
					echo "<table><tr>";
						echo "<td width='80px' align=right>";
							echo "<img style='max-width:65px; max-height:50px; vertical-align:bottom; overflow:hidden;' src='" . Yii::app()->baseUrl . "/userdata/" . Yii::app()->session['uid'] . "/thumb_" . $article->thumbnail_path .  "' alt='No Image'>";
						echo "</td><td style='padding-left:10px'>";
							echo "<span style='padding-top:15px; font-size:14; color:#000000'>" . mb_convert_encoding($article->title, "HTML-ENTITIES", "UTF-8") . "</span>";
							echo "<span style='padding-left:0px;' class='itemleadin'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</span>";
						echo "</td>";
					echo "</tr></table>";
				echo "</a>";
			}
		}
		echo '</div>';

		// Show 'older' button if any more
		if ((!isset($_GET['archive'])) && ($nextPageItem != ""))
			echo "<center><a href='http://plugin.wireflydesign.com/news/index.php/site/play/?cat=" . $showCat . "&art=" . $showArt . "&archive=" . $nextPageItem . "|" . $nextPageDate . "' class='oldernewsbutton'>Older news</a></center>";
	}
}
if ($showArt != '')
{
	echo $showContent;
}
?>
</span>

<?php
if (Yii::app()->session['sidebar'] == 'right')
{
	echo "<span id='sideright' style='vertical-align:top; padding:0px 10px 10px; margin:0px 10px; display:inline-block; width:20%; '>";
	echo "</span>";
	echo "<script> document.getElementById('sideright').innerHTML = document.getElementById('sidebar').innerHTML; </script>";
}
?>

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

