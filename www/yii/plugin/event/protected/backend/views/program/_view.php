<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('thumb_path')); ?>:</b>
	<?php echo CHtml::encode($data->thumb_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('icon_path')); ?>:</b>
	<?php echo CHtml::encode($data->icon_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_program_fields_id')); ?>:</b>
	<?php echo CHtml::encode($data->event_program_fields_id); ?>
	<br />


</div>