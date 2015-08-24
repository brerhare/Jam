<style>
.control-group{ margin-bottom:3px !important;}
</style>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-slider-image-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'slider',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model, 'text', array('class'=>'span5', 'rows'=>5)); ?>

    <?php /// @@EG How to line up custom content ?>
    <div class="control-group "><label class="control-label" for="image">Image <span class="required">*</span></label>
        <div class="controls">
        <?php echo CHtml::activeFileField($model,'image',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'image`'); ?>
        </div>
    </div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
