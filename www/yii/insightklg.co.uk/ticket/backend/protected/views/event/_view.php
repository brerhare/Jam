<?php
/* @var $this EventController */
/* @var $data Event */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_code')); ?>:</b>
	<?php echo CHtml::encode($data->post_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('web_link')); ?>:</b>
	<?php echo CHtml::encode($data->web_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_tickets')); ?>:</b>
	<?php echo CHtml::encode($data->max_tickets); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_text')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_logo_path')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_logo_path); ?>
	<br />

	*/ ?>

</div>