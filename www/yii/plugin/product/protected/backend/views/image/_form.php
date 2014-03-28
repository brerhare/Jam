<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'image-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'filename',array('class'=>'span5','maxlength'=>255)); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'filename'); ?>
        <?php echo CHtml::activeFileField($model,'filename',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'filename'); ?>
    </div>

	<?php //echo $form->textFieldRow($model,'product_product_id',array('class'=>'span5')); ?>
    <!-- hide the product_id although its included -->
    <div class="row">
        <?php echo $form->hiddenField($model,'product_product_id'); ?>
        <?php echo $form->error($model,'product_product_id'); ?>
    </div>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
