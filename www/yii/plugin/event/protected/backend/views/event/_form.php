<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'start',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'end',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'address',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'post_code',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'web',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'contact',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'thumb_path',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'approved',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'member_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'program_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
