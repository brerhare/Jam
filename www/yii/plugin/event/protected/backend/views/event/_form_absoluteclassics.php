
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model2); ?>

    <?php echo $form->dropDownListRow($model2,'type', array('1'=>'Festival', '2'=>'Series'),  array('empty'=>'Choose') ); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model2->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

