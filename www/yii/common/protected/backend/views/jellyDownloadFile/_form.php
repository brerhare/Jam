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
            $urlEmbed .= "<b>Example code to provide a direct link to this file</b><br/>";
            $urlEmbed .= "<i>" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/download/" . $model->filename . "</i>";
			$urlEmbed .= "<br>";
            $urlEmbed .= "<b>Example code to embed this downloadable file in your pages</b><br/>";
            $urlEmbed .= "<i>{{download file " . $model->description . "}}</i>";
            echo $urlEmbed;
        }
        ?>
    </div>


<?php $this->endWidget(); ?>
