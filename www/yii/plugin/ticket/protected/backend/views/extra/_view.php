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

	<b><?php echo CHtml::encode($data->getAttributeLabel('daily_rate')); ?>:</b>
	<?php echo CHtml::encode($data->daily_rate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('once_rate')); ?>:</b>
	<?php echo CHtml::encode($data->once_rate); ?>
	<br />


</div>