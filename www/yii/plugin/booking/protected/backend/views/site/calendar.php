<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

if (!isset(Yii::app()->session['uid']))
{
	echo "<h1>Cannot continue without a valid sid</h1>";
}
else
{
	$this->widget('ext.flowing-calendar.FlowingCalendarWidget');
}
?>

