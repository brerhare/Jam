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

<span class="mainitem" style="display:inline-block; width:95%">

<?php
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->sess->get('uid'));
	$criteria->order = "name ASC";
	$departments = Department::model()->findAll($criteria);
	echo "<div style='width:100%; padding:12px'; >";
	foreach ($departments as $department)
	{
		if (!($department->active))
			continue;
		//echo "<a href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->sess->get('parenturl') . "/?department=" . $department->id . '&page=' . Yii::app()->sess->get('page') . '"' . ")'>";
		echo "<a href='#' onClick='pM(" . '"redirect",' . '"' .     Yii::app()->sess->get('http_referer') . "/?department=" . $department->id . '&page=' . Yii::app()->sess->get('page') . '"' . ")'>";
			echo "<span class='item' style='text-align:center;' >";

				// @@EG: vertically align img in div
				echo "<div style='height:140px; width:154px; text-align: center; margin:1em 0; margin-bottom:0px'>";
					echo "<span style='display: inline-block; height: 100%; vertical-align: middle;'></span>";
					echo "<img style='max-width:154px; max-height:140px; vertical-align:bottom; overflow:hidden;' src='" . Yii::app()->baseUrl . "/userdata/thumb/" . Yii::app()->sess->get('uid') . "-" . $department->thumb_path .  "' alt='No Image'>";
				echo "</div>";

//				echo "<span class='itemleadin'>" . $catDesc . "&nbsp&nbsp" . $article->date . "</span>";

				echo "<p class='itemintro' style='padding-top:2px; font-weight:bold; color:#424242'>" . mb_convert_encoding($department->name, "HTML-ENTITIES", "UTF-8") . "</p>";
				//echo "<span class='itemintro' style='padding-top:2px; color:#000000'>" . mb_convert_encoding($article->intro, "HTML-ENTITIES", "UTF-8") . "</span>";
			echo "</span>";
		echo "</a>";
	}
	echo '</div>';
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

