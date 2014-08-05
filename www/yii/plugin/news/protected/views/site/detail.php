<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
<!-- <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script> -->

<style type="text/css" media="screen">
* {
font-family: Helvetica Neue, Helvetica, Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
.red { font-color:#661010; }
}
</style>

<div style="width:100%; border:0px solid black">	<!-- test container -->

	<div style="/*padding-top:10px;*/ width:100%" ng-app>

<?php
	// Show the most recent article
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	if ($showArt != "0")
		$criteria->addCondition("id=" . $showArt);
	$article = Article::model()->find($criteria);
	if ($article)
	{
		echo $article->content;
	}
?>


	</div>
</div>

<script>
$(document).ready(function() {
var Ifr = parent.document.getElementById("Content");
            Ifr.onload = function () {
                parent.scrollTo(0, 0);

});
</script>

