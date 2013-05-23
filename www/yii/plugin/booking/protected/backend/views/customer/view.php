<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Customer','url'=>array('index')),
	array('label'=>'Create Customer','url'=>array('create')),
	array('label'=>'Update Customer','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Customer','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Customer','url'=>array('admin')),
);
?>

<h1>View Customer #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'ref',
		'address_1',
		'address_2',
		'town',
		'county',
		'post_code',
		'telephone',
		'email',
		'card_name',
		'card_type',
		'card_number',
		'card_expiry_mm',
		'card_expiry_yy',
		'card_cvv',
	),
)); ?>
