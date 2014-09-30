<?php
$this->breadcrumbs=array(
	'Jelly Tickers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JellyTicker','url'=>array('index')),
	array('label'=>'Create JellyTicker','url'=>array('create')),
	array('label'=>'Update JellyTicker','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete JellyTicker','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellyTicker','url'=>array('admin')),
);
?>

<h1>View JellyTicker #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ticker',
		'heading',
		'text',
	),
)); ?>
