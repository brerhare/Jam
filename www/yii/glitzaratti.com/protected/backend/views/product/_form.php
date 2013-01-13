<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weight_kg'); ?>
		<?php echo $form->textField($model,'weight_kg',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'weight_kg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pack_height_mm'); ?>
		<?php echo $form->textField($model,'pack_height_mm'); ?>
		<?php echo $form->error($model,'pack_height_mm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pack_width_mm'); ?>
		<?php echo $form->textField($model,'pack_width_mm'); ?>
		<?php echo $form->error($model,'pack_width_mm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pack_depth_mm'); ?>
		<?php echo $form->textField($model,'pack_depth_mm'); ?>
		<?php echo $form->error($model,'pack_depth_mm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList(
			$model,
			'category_id',
			CHtml::listData(Category::model()->findAll(),'id','name')
		); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
