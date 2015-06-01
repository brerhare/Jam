<?php

$this->menu=array(
	array('label'=>'Manage Mail Content','url'=>array('admin')),
);
?>

<h2>Ready to Publish <?php echo $model->title; ?></h2>

<?php //echo $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'image-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php if ($model->sent == 1) echo "<h4 style='color:red'>Warning! This has already been published</h4>"; ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'Publish',
	));
	?>
</div>


<?php $this->endWidget(); ?>

