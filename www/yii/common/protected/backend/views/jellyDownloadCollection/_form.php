<style>
.control-group{ margin-bottom:3px !important;}
</style>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'download-collection-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

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
            $urlEmbed .= "<b>Example code to embed this download collection in your pages</b><br/>";
            $urlEmbed .= "<i>{{download collection " . $model->name . "}}</i>";
            echo $urlEmbed;
        }
        ?>
    </div>

<?php $this->endWidget(); ?>
