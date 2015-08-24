<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-gallery-form',
	'type' => 'horizontal',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->toggleButtonRow($model, 'active'); ?>

	<?php echo $form->textAreaRow($model,'text',array('rows'=>6, 'cols'=>50, 'class'=>'span6')); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'image'); ?>
        <?php echo CHtml::activeFileField($model,'image',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'image'); ?>
    </div>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>


	<div class="row" class="span8">
    	<?php $urlEmbed = "";
    	if (!($model->isNewRecord))
		{
			$urlEmbed .= "<b>Example code to include photo albums in your pages</b><br/>";
			$urlEmbed .= "<i>{{gallery}}</i> will show the entire list of active albums in your Gallery. Clicking on an album will screenshow it's images<br/>";
			$urlEmbed .= "<i>{{gallery " . $model->id . " " . $model->title . "}}</i> ... to list just this album (whether active or not)</br>";
			$urlEmbed .= "<i>{{gallery " . $model->id .  " thumbnails " . $model->title . "}}</i> ... displays all images as thumbnails for this album. Clicking on any thumbnail will start the screenshow from that point<br/>";
			$urlEmbed .= "<br/>";
			$urlEmbed .= "<b>Example code to include galleries in your blog-news articles</b><br/>";
			$urlEmbed .= "<i>{{gallery-lightbox " . $model->id .  " " . $model->title ."}}</i> ... displays all images as thumbnails for this album. Hover over any thumbnail to see the enlarged image<br/>";
			echo $urlEmbed;
		}
		?>
	</div>


<?php $this->endWidget(); ?>
