<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

	<?php $this->widget('bootstrap.widgets.TbTabs',array(
		'type'=>'tabs',
		'tabs' => array(
			array('label'=>'Choose Tickets', 'content' => $this->renderPartial('_form_choose_tickets', array('form' => $form, 'model' => $model), true), 'active'=>true),
			array('label'=>'Payment', 'content' => $this->renderPartial('_form_make_payment', array('form' => $form, 'model' => $model),  true)),
			array('label'=>'Confirmation', 'content' => $this->renderPartial('_form_make_payment', array('form' => $form, 'model' => $model),  true)),
		),
	));
	?>

	<?php $this->endWidget(); ?>
</div><!-- form -->
