<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'event_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'os_grid_ref',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'grade',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'booking_essential',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'min_age',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'max_ageI',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'child_ages_restrictions',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'additional_venue_info',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'full_price_notes',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'short_description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'wheelchair_accessible',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
