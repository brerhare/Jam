	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<!-- @@TODO: Reinstate this. For now menu items are in random order -->
	<?php // echo $form->textFieldRow($model,'sequence',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

    <?php
    // @@EG Dropdowns
    $possibleParents = array('0'=>'None');
    $criteria = new CDbCriteria;
    if (!$model->isNewRecord)
        $criteria->addCondition("id != " . $model->id);
    $contentBlocks = ContentBlock::model()->findAll($criteria);
    foreach ($contentBlocks as $contentBlock):
        $possibleParents[$contentBlock->id] = $contentBlock->title;
    endforeach;
    echo $form->dropDownListRow($model, 'parent_id', $possibleParents);
    ?>

<?php //echo $form->dropDownListRow($model, 'user2gradeGroups',CHtml::listData(User::model()->getUsers(),'id', 'profile.fullname'), array('multiple'=>true, 'size' => 10));?>

    <?php $urlEmbed = "";
    if (!($model->isNewRecord))
        //$urlEmbed = "<i>http://www.beirc.co.uk/?layout=index&page=" . $model->url . "</i>";
        $urlEmbed = "<i>" . Yii::app()->getBaseUrl(true) . "/?layout=index&page=" . $model->url . "</i>"; ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span1','maxlength'=>55)); ?>
	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>255, 'hint'=>$urlEmbed)); ?>


	<?php if (!$model->isNewRecord) {
    	echo "<div class='control-group '><label class='control-label'>Full Url </label>";
        echo     "<div class='controls' style='margin-top:5px'>";
		echo         "<i>" . Yii::app()->getBaseUrl(true) . "/?layout=index&page=" . $model->url . "</i>";
        echo     "</div>";
    	echo "</div>";
		}
	?>

	<?php //echo $form->textFieldRow($model,'home',array('class'=>'span1','maxlength'=>1)); ?>
	<?php echo $form->toggleButtonRow($model, 'home'); ?>

	<?php //echo $form->textFieldRow($model,'active',array('class'=>'span1','maxlength'=>1)); ?>
	<?php echo $form->toggleButtonRow($model, 'active'); ?>

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
		on: {
			instanceReady: function() {
				this.editable().setStyle( 'background-color', '#ffffff');
			}
		},
		allowedContent : true,	// Allow potentially harmful tags: iframes, javascript etc
        width: <?php echo Yii::app()->params['editorpagewidth'];?>,
        height: <?php echo Yii::app()->params['editorpageheight'];?>,
        filebrowserUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=flash'
    });

function embedWirefly() {
var editor = CKEDITOR.instances['editor1'];
if (editor) { editor.destroy(true); }

//CKEDITOR.instances.editor.destroy();

    CKEDITOR.replace( 'editor1', {
		on: {
			instanceReady: function() {
				this.editable().setStyle( 'background-color', '#625d5d');
			}
		},
		allowedContent : true,	// Allow potentially harmful tags: iframes, javascript etc
        width: <?php echo Yii::app()->params['editorpagewidth'];?>,
        height: <?php echo Yii::app()->params['editorpageheight'];?>,
        filebrowserUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=flash'
    });
}

</script>

		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type'=>'info',
			'label'=>'View with dark background',
			'htmlOptions'=>array(
				'onclick'=>'js:embedWirefly()',
			)
		)); ?>

<!-- CKEditor ends -->


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

