<?php

$this->menu=array(
	array('label'=>'Manage Mailing Lists','url'=>array('admin')),
);
?>

<h2>Import Email addresses into <?php echo $name; ?></h2>

<?php //echo $this->renderPartial('_form', array('model'=>$model)); ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'image-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php
$this->widget('CMultiFileUpload',array(
    'name'=>'files',
    'accept'=>'jpg|csv',
    'max'=>1,
    'remove'=>Yii::t('ui','Remove'),
    //'denied'=>'', message that is displayed when a file type is not allowed
    //'duplicate'=>'', message that is displayed when a file appears twice
    'htmlOptions'=>array('size'=>25),
));
?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'Import',
	));
	?>
</div>


<?php $this->endWidget(); ?>

