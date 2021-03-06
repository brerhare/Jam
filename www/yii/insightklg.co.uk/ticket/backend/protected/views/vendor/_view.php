<?php
/* @var $this VendorController */
/* @var $data Vendor */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('post_code')); ?>:</b>
	<?php echo CHtml::encode($data->post_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone')); ?>:</b>
	<?php echo CHtml::encode($data->telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vat_number')); ?>:</b>
	<?php echo CHtml::encode($data->vat_number); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_name')); ?>:</b>
	<?php echo CHtml::encode($data->bank_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_sort_code')); ?>:</b>
	<?php echo CHtml::encode($data->bank_sort_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bank_account_number')); ?>:</b>
	<?php echo CHtml::encode($data->bank_account_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('venue_address')); ?>:</b>
	<?php echo CHtml::encode($data->venue_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('venue_post_code')); ?>:</b>
	<?php echo CHtml::encode($data->venue_post_code); ?>
	<br />

	*/ ?>

</div>