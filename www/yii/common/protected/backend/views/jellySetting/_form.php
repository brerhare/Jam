<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-setting-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	 'htmlOptions'=>array('enctype'=>'multipart/form-data'),

)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span3','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'analyticsUA',array('class'=>'span2','maxlength'=>255)); ?>

    <?php /// @@EG How to line up custom content ?>
    <?php /// @@EG Display thumbnails of existing images ?>
    <div class="control-group ">
        <label class="control-label" for="image">
            Favicon<br> 
            <?php if (!$model->isNewRecord) echo "<img src='/favicon.ico?lastmod=12345678' style='max-width:100px;max-height:100px;'/>"; ?>
        </label>
        <div class="controls">
			<input type="file" name="favicon" accept="image/*">
        </div>
    </div>

    <div class="control-group ">
        <label class="control-label">New password</label>
        <div class="controls">
			<input style="width:100px" type="password" name="pass1" accept="image/*">
			&nbsp&nbsp Type it again &nbsp&nbsp&nbsp
			<input style="width:100px" type="password" name="pass2" accept="image/*">
        </div>
    </div>

<!--
    <div class="control-group ">
        <label class="control-label">Input it again</label>
        <div class="controls">
			<input type="password" name="pass2" accept="image/*">
        </div>
    </div>
-->

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
