<div class="row">
    <div class="span4 well">

	<?php echo $form->textFieldRow($model,'weight',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model,'height',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model,'width',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model,'depth',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model,'volume',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php echo $form->textFieldRow($model,'duration',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	</div>
</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

