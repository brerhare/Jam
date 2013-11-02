<?php
$this->breadcrumbs=array(
	'Shipping Options'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ShippingOption','url'=>array('index')),
	array('label'=>'Create ShippingOption','url'=>array('create')),
	array('label'=>'Update ShippingOption','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete ShippingOption','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShippingOption','url'=>array('admin')),
);
?>

<h1>View ShippingOption #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'description',
		'price',
	),
)); ?>
