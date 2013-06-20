<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<style>
#page {
	border:0;
}
</style>

<?php
$url = str_replace("backend.php", "index.php", Yii::app()->getHomeUrl());
?>

<iframe src="<?php echo $url;?>/ticket?sid=<?php echo Yii::app()->session['sid'];?>&mode=manual" width="1220" height="700"></iframe>
