<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('event_id')); ?>:</b>
	<?php echo CHtml::encode($data->event_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('os_grid_ref')); ?>:</b>
	<?php echo CHtml::encode($data->os_grid_ref); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grade')); ?>:</b>
	<?php echo CHtml::encode($data->grade); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('booking_essential')); ?>:</b>
	<?php echo CHtml::encode($data->booking_essential); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('min_age')); ?>:</b>
	<?php echo CHtml::encode($data->min_age); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('max_ageI')); ?>:</b>
	<?php echo CHtml::encode($data->max_ageI); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('child_ages_restrictions')); ?>:</b>
	<?php echo CHtml::encode($data->child_ages_restrictions); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('additional_venue_info')); ?>:</b>
	<?php echo CHtml::encode($data->additional_venue_info); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('full_price_notes')); ?>:</b>
	<?php echo CHtml::encode($data->full_price_notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('short_description')); ?>:</b>
	<?php echo CHtml::encode($data->short_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wheelchair_accessible')); ?>:</b>
	<?php echo CHtml::encode($data->wheelchair_accessible); ?>
	<br />

	*/ ?>

</div>