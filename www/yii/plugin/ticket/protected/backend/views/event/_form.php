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
		<?php echo $form->labelEx($model,'uid'); ?>
		<?php echo $form->textField($model,'uid'); ?>
		<?php echo $form->error($model,'uid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'post_code'); ?>
		<?php echo $form->textField($model,'post_code',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'post_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_logo_path'); ?>
		<?php echo $form->textField($model,'ticket_logo_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ticket_logo_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_text'); ?>
		<?php echo $form->textArea($model,'ticket_text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'ticket_text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_terms'); ?>
		<?php echo $form->textArea($model,'ticket_terms',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'ticket_terms'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->textField($model,'active'); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active_start_date_time'); ?>
		<?php echo $form->textField($model,'active_start_date_time'); ?>
		<?php echo $form->error($model,'active_start_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active_end_date_time'); ?>
		<?php echo $form->textField($model,'active_end_date_time'); ?>
		<?php echo $form->error($model,'active_end_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_vendor_id'); ?>
		<?php echo $form->textField($model,'ticket_vendor_id'); ?>
		<?php echo $form->error($model,'ticket_vendor_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->