	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>


<!-- CKEditor starts -->

    <script src="<?php echo Yii::app()->baseUrl.'/scripts/editors/ck/ckeditor/ckeditor.js'; ?>"></script>
	<?php
        $_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
        $_SESSION['KCFINDER']['uploadURL'] = Yii::app()->baseUrl."/userdata/image/"; // URL for the uploads folder
        $_SESSION['KCFINDER']['uploadDir'] = Yii::app()->basePath."/../userdata/image/"; // path to the uploads folder
	?>
    <!-- <div class="row"> -->
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textArea($model, 'description', array('id'=>'editor1')); ?>
    <?php echo $form->error($model,'description'); ?>
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

<br/>

	<?php //echo $form->textFieldRow($model,'product_department_id',array('class'=>'span5')); ?>

<!-- @@EG: Dropdowns -->

	<?php
	$criteria = new CDbCriteria;
 	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$vats = Vat::model()->findAll($criteria);
	if ($vats)
	{
		$defaultVat = "";
		if ($model->isNewRecord)
		{
			// Find default
			$criteria = new CDbCriteria;
 			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("is_default = 1");
			$vats = Vat::model()->find($criteria);
			if ($vats)
				$defaultVat = $vats->id;
		}
		else
			$defaultVat = $model->product_vat_id;
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		echo $form->dropDownListRow($model,'product_vat_id', CHtml::listData(Vat::model()->findAll($criteria), 'id', 'description'), array( 'options' => array($defaultVat=>array('selected'=>true))   ));
	}
	else
		$model->product_vat_id = 0;
	?>

	<?php echo $form->textFieldRow($model,'display_priority',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

