<?php
/* @var $this MemberController */
/* @var $model Member */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'member-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'approved'); ?>
		<?php echo $form->textField($model,'approved'); ?>
		<?php echo $form->error($model,'approved'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'business_name'); ?>
		<?php echo $form->textField($model,'business_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'business_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address1'); ?>
		<?php echo $form->textField($model,'address1',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address2'); ?>
		<?php echo $form->textField($model,'address2',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address3'); ?>
		<?php echo $form->textField($model,'address3',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address4'); ?>
		<?php echo $form->textField($model,'address4',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postcode'); ?>
		<?php echo $form->textField($model,'postcode',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'postcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contact'); ?>
		<?php echo $form->textField($model,'contact',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'contact'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'web'); ?>
		<?php echo $form->textField($model,'web',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'web'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'opening_hours'); ?>
		<?php echo $form->textField($model,'opening_hours',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'opening_hours'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'html_content'); ?>
		<?php echo $form->textArea($model,'html_content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'html_content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'logo_path'); ?>
		<?php echo $form->textField($model,'logo_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'logo_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slider_image_path'); ?>
		<?php echo $form->textField($model,'slider_image_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'slider_image_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'public'); ?>
		<?php echo $form->textField($model,'public'); ?>
		<?php echo $form->error($model,'public'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->textField($model,'category_id'); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'food_type_id'); ?>
		<?php echo $form->textField($model,'food_type_id'); ?>
		<?php echo $form->error($model,'food_type_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->