<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'filter-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'text',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'filter_string',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>


<?php if ($model->isNewRecord) 
	$src="https://plugin.wireflydesign.com/product/?sid=" . Yii::app()->session['sid'] . "&showurl=true";
else
	$src=$model->filter_string . "&showurl=true";
?>

<iframe height="670" width="850" style="border:medium double rgb(255,255,255)" style="overflow-x:hidden; overflow-y:auto;" src=<?php echo $src; ?>></iframe>

<?php $this->endWidget(); ?>
