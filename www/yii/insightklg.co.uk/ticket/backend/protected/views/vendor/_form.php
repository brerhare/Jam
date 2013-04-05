<?php
/* @var $this VendorController */
/* @var $model Vendor */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vendor-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
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
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vat_number'); ?>
		<?php echo $form->textField($model,'vat_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'vat_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_name'); ?>
		<?php echo $form->textField($model,'bank_name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'bank_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_sort_code'); ?>
		<?php echo $form->textField($model,'bank_sort_code',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'bank_sort_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_account_number'); ?>
		<?php echo $form->textField($model,'bank_account_number'); ?>
		<?php echo $form->error($model,'bank_account_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'venue_address'); ?>
		<?php echo $form->textArea($model,'venue_address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'venue_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'venue_post_code'); ?>
		<?php echo $form->textField($model,'venue_post_code',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'venue_post_code'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->