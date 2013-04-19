<?php
/* @var $this EventController */
/* @var $data Event */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_code')); ?>:</b>
	<?php echo CHtml::encode($data->post_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_logo_path')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_logo_path); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_text')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_terms')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_terms); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_start_date_time')); ?>:</b>
	<?php echo CHtml::encode($data->active_start_date_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active_end_date_time')); ?>:</b>
	<?php echo CHtml::encode($data->active_end_date_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_vendor_id')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_vendor_id); ?>
	<br />

	*/ ?>

</div>