<?php
$this->breadcrumbs=array(
	'Areas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Area','url'=>array('index')),
	array('label'=>'Create Area','url'=>array('create')),
	array('label'=>'Update Area','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Area','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Area','url'=>array('admin')),
);
?>

<h4>View Area #<?php echo $model->id; ?></h4>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'description',
		'max_places',
		'ticket_event_id',
	),
)); ?>
