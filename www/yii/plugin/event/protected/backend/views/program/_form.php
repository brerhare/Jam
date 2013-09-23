<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'program-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'thumb_path',array('class'=>'span5','maxlength'=>255)); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model,'thumb_path'); ?>
        <?php echo CHtml::activeFileField($model,'thumb_path',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'thumb_path'); ?>
    </div>

	<?php //echo $form->textFieldRow($model,'icon_path',array('class'=>'span5','maxlength'=>255)); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model,'icon_path'); ?>
        <?php echo CHtml::activeFileField($model,'icon_path',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'icon_path'); ?>
    </div>

	<?php //echo $form->textFieldRow($model,'event_program_fields_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

