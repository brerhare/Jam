<?php
if (Yii::app()->session['news_type'] == 'traditional')
	require('index_traditional.php');
else if (Yii::app()->session['news_type'] == 'pinterest')
	require('index_pinterest.php');
else
	die('Invalid or missing newstype');
?>

