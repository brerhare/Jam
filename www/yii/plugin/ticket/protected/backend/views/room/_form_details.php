

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'qty',array('class'=>'span1')); ?>

	<div class='well span4'>

		<?php echo $form->textFieldRow($model,'max_adult',array('class'=>'span1')); ?>

		<?php echo $form->textFieldRow($model,'max_child',array('class'=>'span1')); ?>

		<?php echo $form->textFieldRow($model,'max_total',array('class'=>'span1')); ?>

	</div>