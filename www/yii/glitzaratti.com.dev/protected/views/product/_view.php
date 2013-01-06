<?php
/* @var $this ProductController */
/* @var $data Product */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('weight_kg')); ?>:</b>
	<?php echo CHtml::encode($data->weight_kg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pack_height_mm')); ?>:</b>
	<?php echo CHtml::encode($data->pack_height_mm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pack_width_mm')); ?>:</b>
	<?php echo CHtml::encode($data->pack_width_mm); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('pack_depth_mm')); ?>:</b>
	<?php echo CHtml::encode($data->pack_depth_mm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_id')); ?>:</b>
	<?php echo CHtml::encode($data->category_id); ?>
	<br />

	*/ ?>

</div>