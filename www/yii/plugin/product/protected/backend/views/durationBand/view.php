<?php
$this->breadcrumbs=array(
	'Duration Bands'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DurationBand','url'=>array('index')),
	array('label'=>'Create DurationBand','url'=>array('create')),
	array('label'=>'Update DurationBand','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete DurationBand','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DurationBand','url'=>array('admin')),
);
?>

<h1>View DurationBand #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'max',
	),
)); ?>
