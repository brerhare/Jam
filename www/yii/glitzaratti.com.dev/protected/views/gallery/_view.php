<?php
/* @var $this GalleryController */
/* @var $data Gallery */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('carousel')); ?>:</b>
	<?php echo CHtml::encode($data->carousel); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filter')); ?>:</b>
	<?php echo CHtml::encode($data->filter); ?>
	<br />


</div>