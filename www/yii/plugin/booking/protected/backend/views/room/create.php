<?php

$this->menu=array(
	array('label'=>'Manage Rooms','url'=>array('admin')),
);
?>

<h1>Create Room</h1>

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'room-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php
	$this->widget('zii.widgets.jui.CJuiTabs', array(
		'tabs' => array(
			'Details' => $this->renderPartial('_form_basic', array('form' => $form, 'model' => $model), true),
			'Pricing' => $this->renderPartial('_form_pricing', array('form' => $form, 'model' => $model), true),
		),
		'options' => array(
			//'collapsible' => true,
		),
	));
	?>

    <div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Create' : 'Save',
	)); ?>
    </div>


	<?php $this->endWidget(); ?>
</div><!-- form -->




<?php //echo $this->renderPartial('_form', array('model'=>$model)); ?>