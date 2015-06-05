<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-setting-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	 'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>255)); ?>

    <?php /// @@EG How to line up custom content ?>
    <div class="control-group "><label class="control-label" for="image">Favicon </label>
        <div class="controls">
			<input type="file" name="favicon" accept="image/*">
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
