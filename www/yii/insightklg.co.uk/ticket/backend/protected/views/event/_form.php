<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_date'); ?>
		<?php echo $form->textField($model,'start_date',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'start_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'post_code'); ?>
		<?php echo $form->textField($model,'post_code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'post_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'web_link'); ?>
		<?php echo $form->textField($model,'web_link',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'web_link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_tickets'); ?>
		<?php echo $form->textField($model,'max_tickets'); ?>
		<?php echo $form->error($model,'max_tickets'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_text'); ?>
		<?php echo $form->textArea($model,'ticket_text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'ticket_text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_logo_path'); ?>
		<?php echo CHtml::activeFileField($model,'ticket_logo_path',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'ticket_logo_path'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
