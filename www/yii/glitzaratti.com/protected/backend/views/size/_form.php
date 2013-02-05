<?php
/* @var $this SizeController */
/* @var $model Size */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'size-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textField($model,'text',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<!-- hide the is-a-default -->
	<div class="row">
		<?php /*echo $form->labelEx($model,'is_a_default');*/ ?>
		<?php /*echo $form->textField($model,'is_a_default');*/ ?>
		<?php echo $form->hiddenField($model,'is_a_default'); ?>
		<?php echo $form->error($model,'is_a_default'); ?>
	</div>

    <!-- hide the category_id although its included -->
	<div class="row">
		<?php echo $form->hiddenField($model,'category_id'); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->