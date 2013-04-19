<?php
/* @var $this TicketTypeController */
/* @var $model TicketType */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticket-type-form',
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
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'places_per_ticket'); ?>
		<?php echo $form->textField($model,'places_per_ticket'); ?>
		<?php echo $form->error($model,'places_per_ticket'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_tickets_per_order'); ?>
		<?php echo $form->textField($model,'max_tickets_per_order'); ?>
		<?php echo $form->error($model,'max_tickets_per_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ticket_area_id'); ?>
		<?php echo $form->textField($model,'ticket_area_id'); ?>
		<?php echo $form->error($model,'ticket_area_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->