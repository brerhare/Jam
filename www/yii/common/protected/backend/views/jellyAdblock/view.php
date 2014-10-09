<?php
$this->breadcrumbs=array(
	'Jelly Adblocks'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JellyAdblock','url'=>array('index')),
	array('label'=>'Create JellyAdblock','url'=>array('create')),
	array('label'=>'Update JellyAdblock','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete JellyAdblock','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellyAdblock','url'=>array('admin')),
);
?>

<h1>View JellyAdblock #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'image',
		'url',
	),
)); ?>
