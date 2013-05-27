<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cc_email_address')); ?>:</b>
	<?php echo CHtml::encode($data->cc_email_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deposit_percent')); ?>:</b>
	<?php echo CHtml::encode($data->deposit_percent); ?>
	<br />


</div>