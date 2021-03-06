<?php
$this->breadcrumbs=array(
	'Vendors'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Vendor','url'=>array('index')),
	array('label'=>'Create Vendor','url'=>array('create')),
	array('label'=>'Update Vendor','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Vendor','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vendor','url'=>array('admin')),
);
?>

<h4>View Vendor #<?php echo $model->id; ?></h4>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'name',
		'address',
		'post_code',
		'email',
		'telephone',
		'vat_number',
		'bank_account_name',
		'bank_account_number',
		'bank_sort_code',
	),
)); ?>
