<?php
/* @var $this PluginController */
/* @var $model Plugin */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'plugin-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'container_code'); ?>
		<?php echo $form->textArea($model,'container_code',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'container_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'container_width'); ?>
		<?php echo $form->textField($model,'container_width'); ?>
		<?php echo $form->error($model,'container_width'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'container_height'); ?>
		<?php echo $form->textField($model,'container_height'); ?>
		<?php echo $form->error($model,'container_height'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->