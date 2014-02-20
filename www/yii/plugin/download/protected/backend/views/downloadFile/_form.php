<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'download-file-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<br>
	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'filename',array('class'=>'span2','maxlength'=>255)); ?>
	<?php echo $form->fileFieldRow($model,'filename',array('size'=>60,'maxlength'=>255)); ?>


	<?php echo $form->textFieldRow($model,'description',array('class'=>'span6','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'download_collection_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
