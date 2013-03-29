

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'max_adult',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'max_child',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'max_total',array('class'=>'span5')); ?>

