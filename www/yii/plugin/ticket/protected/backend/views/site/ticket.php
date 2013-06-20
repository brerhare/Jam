<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<style>
#page {
	border:0;
}
</style>

<h1>Manual Tickets</h1>

<p>
<?php //echo "Session id: " . Yii::app()->session->sessionID . "<br>";?>
<?php //echo "uid: " . Yii::app()->session['uid'] . "<br>";?>
</p>

<iframe src="<?php echo Yii::app()->getHomeUrl();?>/ticket?sid=<?php echo Yii::app()->session['sid'];?>&mode=manual" width="1220" height="800"></iframe>

<!--
<iframe src="http://localhost/ticket?sid=<?php echo Yii::app()->session['sid'];?>&mode=manual" width="1220" height="800"></iframe>
-->

<!--
<iframe src="https://plugin.wireflydesign.com/ticket?sid=<?php echo Yii::app()->session['sid'];?>&mode=manual" width="1220" height="800"></iframe>
-->