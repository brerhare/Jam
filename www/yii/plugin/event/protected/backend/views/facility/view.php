<?php
$this->breadcrumbs=array(
	'Facilities'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Facility','url'=>array('index')),
	array('label'=>'Create Facility','url'=>array('create')),
	array('label'=>'Update Facility','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Facility','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Facility','url'=>array('admin')),
);
?>

<h4>View Facility #<?php echo $model->id; ?></h4>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'icon_path',
	),
)); ?>
