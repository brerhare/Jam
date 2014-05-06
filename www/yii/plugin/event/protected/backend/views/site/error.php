<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
?>

<h4>Error <?php echo $code; ?></h4>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>
