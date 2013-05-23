<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('uid')); ?>:</b>
	<?php echo CHtml::encode($data->uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ref')); ?>:</b>
	<?php echo CHtml::encode($data->ref); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_1')); ?>:</b>
	<?php echo CHtml::encode($data->address_1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_2')); ?>:</b>
	<?php echo CHtml::encode($data->address_2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('town')); ?>:</b>
	<?php echo CHtml::encode($data->town); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('county')); ?>:</b>
	<?php echo CHtml::encode($data->county); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('post_code')); ?>:</b>
	<?php echo CHtml::encode($data->post_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone')); ?>:</b>
	<?php echo CHtml::encode($data->telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('card_name')); ?>:</b>
	<?php echo CHtml::encode($data->card_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('card_type')); ?>:</b>
	<?php echo CHtml::encode($data->card_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('card_number')); ?>:</b>
	<?php echo CHtml::encode($data->card_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('card_expiry_mm')); ?>:</b>
	<?php echo CHtml::encode($data->card_expiry_mm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('card_expiry_yy')); ?>:</b>
	<?php echo CHtml::encode($data->card_expiry_yy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('card_cvv')); ?>:</b>
	<?php echo CHtml::encode($data->card_cvv); ?>
	<br />

	*/ ?>

</div>