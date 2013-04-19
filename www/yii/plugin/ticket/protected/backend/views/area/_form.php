<?php
/* @var $this AreaController */
/* @var $model Area */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'area-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'uid'); ?>
		<?php echo $form->textField($model,'uid'); ?>
		<?php echo $form->error($model,'uid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_places'); ?>
		<?php echo $form->textField($model,'max_places',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'max_places'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_event_id'); ?>
		<?php echo $form->textField($model,'ticket_event_id'); ?>
		<?php echo $form->error($model,'ticket_event_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->