<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ticker')); ?>:</b>
	<?php echo CHtml::encode($data->ticker); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('heading')); ?>:</b>
	<?php echo CHtml::encode($data->heading); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CHtml::encode($data->text); ?>
	<br />


</div>