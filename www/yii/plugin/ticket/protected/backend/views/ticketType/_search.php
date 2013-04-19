<?php
/* @var $this TicketTypeController */
/* @var $model TicketType */
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
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'places_per_ticket'); ?>
		<?php echo $form->textField($model,'places_per_ticket'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_tickets_per_order'); ?>
		<?php echo $form->textField($model,'max_tickets_per_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ticket_area_id'); ?>
		<?php echo $form->textField($model,'ticket_area_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->