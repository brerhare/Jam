<?php
$this->breadcrumbs=array(
	'Price Bands'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List PriceBand','url'=>array('index')),
	array('label'=>'Create PriceBand','url'=>array('create')),
	array('label'=>'Update PriceBand','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete PriceBand','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PriceBand','url'=>array('admin')),
);
?>

<h1>View PriceBand #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'icon_path',
	),
)); ?>
