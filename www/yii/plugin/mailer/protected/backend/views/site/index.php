<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h2>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h2>

<?php if (Yii::app()->user->isGuest): ?>
	<h5>Please login to continue</h5>
<?php else: ?>
	<h5>You are logged in</h5>
<?php endif ?>
