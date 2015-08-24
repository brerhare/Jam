<style>
.control-group{ margin-bottom:3px !important;}
</style>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'department-form',
	'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php $jellyEmbed = "";
	if (!($model->isNewRecord))
		$jellyEmbed = "To embed this in pages use <b><i>{{department " . $model->id . " " . $model->name . "}}</b></i>"; ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255, 'hint'=>$jellyEmbed)); ?>

<?php echo $form->toggleButtonRow($model, 'active'); ?>

    <div class="control-group">
        <label class="control-label" for="thumb_path">Thumb (140w x 140h)</label>
            <div class="controls">
            <?php echo CHtml::activeFileField($model,'thumb_path',array('size'=>60,'maxlength'=>255)); ?>
            <?php echo $form->error($model,'thumb_path'); ?>
        </div>
    </div>

	<?php echo $jellyEmbed . "<br/><br/>";?>

	<?php if (!($model->isNewRecord)): ?>
		<a class="btn btn-small" title="Options" rel="tooltip" href="/product/backend.php/option/session?department_id=<?php echo $model->id; ?>">Options</a>
		<a class="btn btn-small" title="Features" rel="tooltip" href="/product/backend.php/feature/session?department_id=<?php echo $model->id; ?>">Features</a>
		<a class="btn btn-small" title="Products" rel="tooltip" href="/product/backend.php/product/session?department_id=<?php echo $model->id; ?>">Products</a>
	<?php endif;?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
