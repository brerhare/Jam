<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-gallery-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->toggleButtonRow($model, 'active'); ?>
<br>

	<?php echo $form->textAreaRow($model,'text',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>



	<div class="row">
<br>
    <?php $urlEmbed = "";
    if (!($model->isNewRecord))
        echo "To embed this in pages use <b>{{gallery " . $model->id . " " . $model->title . "}}</b>";
	?>
	</div>
<br>




	<?php //echo $form->textFieldRow($model,'image',array('class'=>'span5','maxlength'=>255)); ?>

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

<?php $this->endWidget(); ?>
