<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
?>

<style>
.form-horizontal .control-group {
margin-bottom: 0px;
}


</style>

<div class="row">
	<div class="span1"></div>
	<div class='well span6'>
	<h3>Thank you for your booking.</h3>
	<h4>A confirmation email has been sent to you.<br>
	Should you not receive it within a few minutes please check your junk folder</h4>
	<br/><br/>

	</div> <!-- well -->
</div> <!-- row -->


<?php $this->endWidget(); ?>
</div><!-- form -->
