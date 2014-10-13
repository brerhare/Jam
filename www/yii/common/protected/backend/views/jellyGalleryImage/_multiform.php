<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'jelly-gallery-image-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<br/>

<?php
$this->widget('CMultiFileUpload',array(
    'name'=>'files',
    'accept'=>'jpg|jpeg|gif|png',
    'max'=>50,
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
			'label'=>'Upload',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
