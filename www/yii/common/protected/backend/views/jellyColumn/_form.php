<div style="width:920px">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'content-block-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php  echo $form->textFieldRow($model,'column_name',array('class'=>'span3')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span2','maxlength'=>55)); ?>

<!-- CKEditor starts -->

    <script src="<?php echo Yii::app()->baseUrl.'/scripts/editors/ck/ckeditor/ckeditor.js'; ?>"></script>
	<?php
        $_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
        $_SESSION['KCFINDER']['uploadURL'] = Yii::app()->baseUrl."/userdata/image/"; // URL for the uploads folder
        $_SESSION['KCFINDER']['uploadDir'] = Yii::app()->basePath."/../userdata/image/"; // path to the uploads folder
	?>
    <!-- <div class="row"> -->
    <?php echo $form->labelEx($model,'content'); ?>
    <?php echo $form->textArea($model, 'content', array('id'=>'editor1')); ?>
    <?php echo $form->error($model,'content'); ?>
    <!-- </div> -->

    <script type="text/javascript">
    CKEDITOR.replace( 'editor1', {
		allowedContent : true,	// Allow potentially harmful tags: iframes, javascript etc
        width: <?php echo Yii::app()->params['editorpagewidth'];?>,
        height: <?php echo Yii::app()->params['editorpageheight'];?>,
        filebrowserUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=flash'
    });
	</script>

<!-- CKEditor ends -->


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

</div>
