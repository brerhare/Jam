<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_places')); ?>:</b>
	<?php echo CHtml::encode($data->max_places); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticket_event_id')); ?>:</b>
	<?php echo CHtml::encode($data->ticket_event_id); ?>
	<br />


</div>