<?php
$this->breadcrumbs=array(
	'Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Type','url'=>array('index')),
	array('label'=>'Create Type','url'=>array('create')),
	array('label'=>'Update Type','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Type','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Type','url'=>array('admin')),
);
?>

<h1>View Type #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
