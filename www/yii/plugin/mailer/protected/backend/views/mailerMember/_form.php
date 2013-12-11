<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'mailer-member-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'email_address',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'first_name',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'last_name',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'telephone',array('class'=>'span2','maxlength'=>45)); ?>

	<?php echo $form->textAreaRow($model,'address',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php // echo $form->textFieldRow($model,'active',array('class'=>'span1')); ?>
	<?php // @@EG: Change on/off labels on togglebutton ?>
	<?php echo $form->toggleButtonRow($model, 'active' , array('options'=>array('enabledLabel'=>'Yes', 'disabledLabel'=>'No'))); ?>

	<?php //echo $form->textFieldRow($model,'mailer_list_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
