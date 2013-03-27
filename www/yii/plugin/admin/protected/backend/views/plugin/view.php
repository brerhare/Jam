<?php
$this->breadcrumbs=array(
	'Plugins'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Plugin','url'=>array('index')),
	array('label'=>'Create Plugin','url'=>array('create')),
	array('label'=>'Update Plugin','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Plugin','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Plugin','url'=>array('admin')),
);
?>

<h1>View Plugin #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'container_url',
		'container_width',
		'container_height',
	),
)); ?>
