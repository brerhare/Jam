<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'carousel-block-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'sequence',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

<?php 
$this->widget('bootstrap.widgets.TbRedactorJs',
    array(
      'model'=>$model,
      'attribute'=>'content',
      'editorOptions'=>array(
          'imageUpload' => $this->createUrl('carouselBlock/imageUpload'),
          'imageGetJson' => $this->createUrl('carouselBlock/imageList'),
          'width'=>'100%',
          'height'=>'400px'
       )
    ));
?>

	<?php echo $form->textFieldRow($model,'active',array('class'=>'span1','maxlength'=>1)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
