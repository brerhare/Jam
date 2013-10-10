
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model2); ?>

	<?php echo $form->textFieldRow($model2,'os_grid_ref',array('class'=>'span2','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'grade',array('class'=>'span2','maxlength'=>255)); ?>

	<?php echo $form->toggleButtonRow($model2, 'booking_essential'); ?>

	<?php echo $form->textFieldRow($model2,'min_age',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model2,'max_age',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model2,'child_ages_restrictions',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'additional_venue_info',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'full_price_notes',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model2,'short_description',array('class'=>'span3','maxlength'=>255)); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model2->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

