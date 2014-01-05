	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textAreaRow($model,'meta_title',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'meta_description',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'meta_keywords',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<p><b>title:</b> This is used by most search engines. It should have between 5 and 8 words and these should include your key words and phrases, ideally at the beginning. It should be set up differently, with different keywords, for each page on your site.</p>

<p><b>description:</b> This is still used by some search engines. It should have between 15 and 30 words and include more important keywords. It should read well also and give a reason for coming to your site as most search engines will display it.</p>

<p><b>keywords:</b> Should have about 20 to 25 words that are important to your site. You should avoid repetition of any one word if possible. You should vary the number of words in here on different pages.</p>



