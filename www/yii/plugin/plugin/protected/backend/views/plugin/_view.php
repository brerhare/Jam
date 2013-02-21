<?php
/* @var $this PluginController */
/* @var $data Plugin */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('container_code')); ?>:</b>
	<?php echo CHtml::encode($data->container_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('container_width')); ?>:</b>
	<?php echo CHtml::encode($data->container_width); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('container_height')); ?>:</b>
	<?php echo CHtml::encode($data->container_height); ?>
	<br />


</div>