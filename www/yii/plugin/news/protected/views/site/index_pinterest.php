<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
<link href="/news/css/oldernewsbutton.css" rel="stylesheet"/>

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
	display:inline-block;
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
<?php require('_sidebar.php'); ?>
</span>

<span class="mainitem" style="display:inline-block; width:70%">

<?php
$mainArticleId = -1;
?>

<br/><br/>

<?php
$nextPageDate = "";
$nextPageItem = "";
if ($showArt == '')
{
	// Show all the articles
	// ---------------------
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
		echo "<div id='masonry-container' style='display:none; width:100%; padding:12px'; >";
		foreach ($articles as $article)
		{
			if ($article->id == $mainArticleId)
				continue;

			// Ignore future dates
			if (date("Y-m-d", strtotime($article->date)) > date("Y-m-d"))
				continue;

			if (++$displayCount > $maxDisplay)
			{
				$nextPageItem = $article->id;
				$nextPageDate = $article->date;
				break;
			}

			// Get the category name
			$showCat = "No category";
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid=" . Yii::app()->session['uid']);
			$criteria->addCondition("id=" . $article->blog_category_id);
			$category = Category::model()->find($criteria);
			if ($category)
				$showCat = $category->name;

			if (!isset($_GET['archive']))
			{
				// Main page large grid format
				// ---------------------------
				echo "<a href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->session['parenturl'] . "/?art=" . $article->id . '&page=' . Yii::app()->session['page'] . "&title=" . str_replace(" ", "-", urlencode($article->title))    . '"' . ")'>";
					echo "<span class='item' style='margin-bottom:13px;' >";
						echo "<img src='" . Yii::app()->baseUrl . "/userdata/" . Yii::app()->session['uid'] . "/thumb_" . $article->thumbnail_path .  "' alt='No Image' width='100%'>";
						echo "<span class='itemleadin'>" . $showCat . "&nbsp&nbsp" . $article->date . "</span><hr class='wtf-did-this-hr-take-to-DO'/>";
						echo "<span class='itemintro' style='font-weight:bold; color:#424242'>" . $article->title . "</span><br/>";
						echo "<span class='itemintro' style='padding-top:0px; color:#000000'>" . $article->intro . "</span><br/>";
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
							echo "<span style='padding-top:15px; font-size:14; color:#000000'>" . $article->title . "</span>";
							echo "<span style='padding-left:0px;' class='itemleadin'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</span>";
						echo "</td>";
					echo "</tr></table>";
				echo "</a>";
			}
		}
		echo '</div>';

		// Show 'older' button if any more
		if ((!isset($_GET['archive'])) && ($nextPageItem != ""))
			echo "<center><a href='http://plugin.wireflydesign.com/news/index.php/site/play/?cat=" . $showCat . "&art=" . $showArt . "&archive=" . $nextPageItem . "|" . $nextPageDate . "' class='oldernewsbutton'>Older news</a><center>";
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

// @@NB START POSTMESSAGE
function pM(type, param)
{
	parent.postMessage(type + "^" + param, "*");
}
// @@NB END POSTMESSAGE

</script>
<script src="/js/masonry.pkgd.min.js"></script>
<script src="/js/imagesloaded.pkgd.min.js"></script>
