<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'member-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',

)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span2','maxlength'=>255, 'autocomplete'=>"off")); ?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span2','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span3','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'member_type_id',array('class'=>'span5')); ?>
	<?php
		$criteria = new CDbCriteria;
		echo $form->dropDownListRow($model,'member_type_id', CHtml::listData(MemberType::model()->findAll($criteria), 'id', 'description'), array('empty'=>'Choose'));
	?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
