<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'member-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
	if ($model->isNewRecord)
		echo $form->textFieldRow($model,'user_name',array('class'=>'span3','maxlength'=>255));
	?>

	<?php echo $form->passwordFieldRow($model,'password',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'first_name',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'last_name',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'telephone',array('class'=>'span3','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'email_address',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'organisation',array('class'=>'span3','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'join_date',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'last_login_date',array('class'=>'span5')); ?>


    <?php if(CCaptcha::checkRequirements()): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'captcha'); ?>
        <div>
        <?php $this->widget('CCaptcha'); ?>
        <?php echo $form->textField($model,'captcha'); ?>
        </div>
        <div class="hint">Please enter the letters as they are shown in the image above.
        <br/>Letters are not case-sensitive.</div>
        <?php echo $form->error($model,'captcha'); ?>
    </div>
    <?php endif; ?>



	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
