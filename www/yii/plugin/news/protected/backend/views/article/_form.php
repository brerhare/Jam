<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'article-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php
		//echo $form->textFieldRow($model,'blog_category_id',array('class'=>'span5'));
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		echo $form->dropDownListRow($model,'blog_category_id', CHtml::listData(Category::model()->findAll($criteria), 'id', 'name'), array('empty'=>'Choose')); ?>

	<?php //echo $form->textFieldRow($model,'date',array('class'=>'span5')); ?>


<div id = "row">
Date *
</div>
<div id = "row">

	<?php
// @@EG CJuiDatePicker. See also the model for the before/after function I added to support this

		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'publishDate',
			'model' => $model,
			'attribute' => 'date',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat' => 'dd-mm-yy', // save to db format
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;'
			),
		));
	?>
</div>


	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'thumbnail_path',array('class'=>'span5','maxlength'=>255)); ?>
	<?php echo $form->fileFieldRow($model, 'thumbnail_path'); ?>

	<?php echo $form->textAreaRow($model,'intro',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php //echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div id="row">


	<style>
	.Xui-tooltip{display:none}
	</style>

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
        width: <?php echo Yii::app()->params['editorpagewidth'];?>,
        height: <?php echo Yii::app()->params['editorpageheight'];?>,
        filebrowserUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=flash'
    });
    </script>

<!-- CKEditor ends -->

<!-- http://www.dgnews-sport.co.uk/?art=90 -->

	<?php // Show deep link
	if (!($model->isNewRecord))
		echo "<br/>To link directly to this article visit: <b> " . Yii::app()->session['http_referer'] .  "/?art=" . $model->id . "</b>";
	?>

	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
