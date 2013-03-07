<?php
$this->breadcrumbs=array(
	'Images'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Image','url'=>array('index')),
	array('label'=>'Create Image','url'=>array('create')),
	array('label'=>'Update Image','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Image','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Image','url'=>array('admin')),
);
?>

<h1>View Image #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'filename',
		'plugin_id',
	),
)); ?>
