<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'coupon-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'code',array('class'=>'span2','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'type',array('class'=>'span1')); ?>

	<?php
    echo $form->dropDownListRow($model, 'type', $model::getTypes(), array('style'=>'width:150px'));
    ?>

	<?php echo $form->textFieldRow($model,'value',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

