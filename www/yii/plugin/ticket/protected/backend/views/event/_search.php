<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'uid'); ?>
		<?php echo $form->textField($model,'uid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'post_code'); ?>
		<?php echo $form->textField($model,'post_code',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_logo_path'); ?>
		<?php echo $form->textField($model,'ticket_logo_path',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_text'); ?>
		<?php echo $form->textArea($model,'ticket_text',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_terms'); ?>
		<?php echo $form->textArea($model,'ticket_terms',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active'); ?>
		<?php echo $form->textField($model,'active'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active_start_date_time'); ?>
		<?php echo $form->textField($model,'active_start_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active_end_date_time'); ?>
		<?php echo $form->textField($model,'active_end_date_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_vendor_id'); ?>
		<?php echo $form->textField($model,'ticket_vendor_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->