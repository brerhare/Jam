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
			array('label'=>'Room Selection', 'content' => $this->renderPartial('_form_choose_rooms', array('form' => $form, 'model' => $model), true), 'active'=>true),
			//array('label'=>'Room Options', 'content' => $this->renderPartial('_form_room_options', array('form' => $form, 'model' => $model),  true)),
			//array('label'=>'Your Details', 'content' => $this->renderPartial('_form_customer_details', array('form' => $form, 'model' => $model), true)),
			//array('label'=>'Confirm Booking', 'content' => $this->renderPartial('_form_confirm_booking', array('form' => $form, 'model' => $model), true)),

		),
	));
	?>

	<?php $this->endWidget(); ?>
</div><!-- form -->



