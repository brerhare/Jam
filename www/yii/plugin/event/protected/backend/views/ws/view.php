<?php
$this->breadcrumbs=array(
	'Ws'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Ws','url'=>array('index')),
	array('label'=>'Create Ws','url'=>array('create')),
	array('label'=>'Update Ws','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Ws','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ws','url'=>array('admin')),
);
?>

<h1>View Ws #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'event_id',
		'os_grid_ref',
		'grade',
		'booking_essential',
		'min_age',
		'max_age',
		'child_ages_restrictions',
		'additional_venue_info',
		'full_price_notes',
		'short_description',
		'wheelchair_accessible',
	),
)); ?>
