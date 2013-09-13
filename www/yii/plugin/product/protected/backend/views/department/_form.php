<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'department-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php $jellyEmbed = "";
	if (!($model->isNewRecord))
		$jellyEmbed = "To embed this in pages use <b><i>{{department " . $model->id . " " . $model->name . "}}</b></i>"; ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255, 'hint'=>$jellyEmbed)); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
