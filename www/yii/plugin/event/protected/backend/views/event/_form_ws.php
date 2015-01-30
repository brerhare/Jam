
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model2); ?>

	<?php echo $form->textFieldRow($model2,'os_grid_ref',array('class'=>'span2','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model2,'grade',array('class'=>'span2','maxlength'=>255)); ?>

	<?php if ($model2->isNewRecord)
		$model2->grade = 'Easy';
	?>
    <?php echo $form->dropDownListRow($model2,'grade', array('Easy'=>'Easy', 'Medium'=>'Medium', 'Family'=>'Family'),  array('empty'=>'Choose') ); ?>


	<?php // @@EG: Change on/off labels on togglebutton ?>

	<?php //// @@NB: the 'options' buggers Yii although docs say its right: ..  echo $form->toggleButtonRow($model2, 'booking_essential' , array('options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No'))); ?>
	<?php echo $form->toggleButtonRow($model2, 'booking_essential'); ?>

	<?php echo $form->textFieldRow($model2,'min_age',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model2,'max_age',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

<div class="well">
	<?php echo $form->textFieldRow($model2,'child_ages_restrictions',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'additional_venue_info',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'full_price_notes',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'short_description',array('class'=>'span3','maxlength'=>255)); ?>
</div>

	<div class="form-actions">
	<?php
	if ($updateMode == "update")
	{
		 $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model2->isNewRecord ? 'Create' : 'Save',
		));
	}
	?>
	</div>

