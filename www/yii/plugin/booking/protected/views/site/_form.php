<!--
<style>
	body {width:520px;overflow: hidden;}
	div.form .form-horizontal {width:520px;}
	#content {width:520px;}
</style>
-->

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

	<?php $this->widget('bootstrap.widgets.TbTabs',array(
		'type'=>'tabs',
		'tabs' => array(
			array('id'=>'tab_1', 'label'=>'Choose Room', 'content' => $this->renderPartial('_form_choose_rooms', array('form' => $form, 'model' => $model), true), 'itemOptions'=>array('class'=>'disabled'), 'active'=>true),
			array('id'=>'tab_2', 'label'=>'Choose Options', 'content' => $this->renderPartial('_form_room_options', array('form' => $form, 'model' => $model),  true), 'itemOptions'=>array('class'=>'disabled')),
			array('id'=>'tab_3', 'label'=>'Your Details', 'content' => $this->renderPartial('_form_customer_details', array('form' => $form, 'model' => $model), true), 'itemOptions'=>array('class'=>'disabled')),
			array('id'=>'tab_4', 'label'=>'Confirm Booking', 'content' => $this->renderPartial('_form_confirm_booking', array('form' => $form, 'model' => $model), true), 'itemOptions'=>array('class'=>'disabled')),
		),
	));
	?>

	<?php $this->endWidget(); ?>
</div><!-- form -->



