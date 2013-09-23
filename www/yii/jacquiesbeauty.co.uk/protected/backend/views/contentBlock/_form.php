<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'content-block-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

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
        $urlEmbed = "<i>http://jacquies.wireflydesign.com/?layout=frontpage&page=" . $model->url . "</i>"; ?>

	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>255, 'hint'=>$urlEmbed)); ?>

	<?php //echo $form->textFieldRow($model,'active',array('class'=>'span1','maxlength'=>1)); ?>
	<?php echo $form->toggleButtonRow($model, 'active'); ?>

<div style="width:908px">
<?php 
$this->widget('bootstrap.widgets.TbRedactorJs',
    array(
      'model'=>$model,
      'attribute'=>'content',
      'editorOptions'=>array(
          'imageUpload' => $this->createUrl('contentBlock/imageUpload'),
          'imageGetJson' => $this->createUrl('contentBlock/imageList'),
          'width'=>'100%',
          'height'=>'400px'
       )
    ));
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
