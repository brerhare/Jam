<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h4>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h4>

<?php
//require Yii::app()->baseUrl . "/protected/backend/views/event/report.php" ;
//http://plugin.wireflydesign.com/ticket/backend.php/event/showReport/297

/*
if ((isset(Yii::app()->session['uid'])) && (Yii::app()->session['uid'] != 0))
{
	$this->render('../event/report',array(
		'model'=>null,
		));
}
*/
?>

