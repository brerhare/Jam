<?php
$this->breadcrumbs=array(
	'Occupancy Types'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OccupancyType','url'=>array('index')),
	array('label'=>'Create OccupancyType','url'=>array('create')),
	array('label'=>'Update OccupancyType','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete OccupancyType','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OccupancyType','url'=>array('admin')),
);
?>

<h1>View OccupancyType #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'description',
	),
)); ?>
