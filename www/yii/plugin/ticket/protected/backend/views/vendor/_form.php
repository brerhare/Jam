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
		<?php echo $form->labelEx($model,'uid'); ?>
		<?php echo $form->textField($model,'uid'); ?>
		<?php echo $form->error($model,'uid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
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
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vat_number'); ?>
		<?php echo $form->textField($model,'vat_number',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'vat_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_account_name'); ?>
		<?php echo $form->textField($model,'bank_account_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bank_account_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_account_number'); ?>
		<?php echo $form->textField($model,'bank_account_number',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'bank_account_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bank_sort_code'); ?>
		<?php echo $form->textField($model,'bank_sort_code',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'bank_sort_code'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->