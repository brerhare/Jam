<style>
.control-group{ margin-bottom:3px !important;}
</style>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'ws-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'os_grid_ref',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'grade',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'booking_essential',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'min_age',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'max_age',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'child_ages_restrictions',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'additional_venue_info',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'full_price_notes',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'short_description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'wheelchair_accessible',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
