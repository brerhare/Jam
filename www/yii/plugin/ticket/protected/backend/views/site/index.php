<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h4>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h4>

<p>
<?php echo "Session id: ".Yii::app()->session->sessionID;?>
</p>