<style>
.control-group{ margin-bottom:3px !important;}
</style>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'download-file-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<br>

	<?php //echo $form->textFieldRow($model,'filename',array('class'=>'span2','maxlength'=>255)); ?>
	<?php echo $form->fileFieldRow($model,'filename',array('size'=>60,'maxlength'=>255)); ?>


	<?php echo $form->textFieldRow($model,'description',array('class'=>'span6','maxlength'=>255)); ?>

    <?php echo $form->dropDownListRow($model,'jelly_download_collection_id', CHtml::listData(JellyDownloadCollection::model()->findAll(), 'id', 'name'), array('empty'=>'Choose')); ?>

	<?php //echo $form->textFieldRow($model,'jelly_download_collection_id',array('class'=>'span5')); ?>

	<?php if (!$model->isNewRecord) {
		echo "<div class='control-group '><label class='control-label'>Full Url </label>";
		echo     "<div class='controls' style='margin-top:5px'>";
		echo         "<i>" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/download/" . $model->filename . "</i>";
		echo     "</div>";
		echo "</div>";
	} ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
