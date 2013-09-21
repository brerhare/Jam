<?php
$this->breadcrumbs=array(
	'Interests'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Interest','url'=>array('index')),
	array('label'=>'Create Interest','url'=>array('create')),
	array('label'=>'Update Interest','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Interest','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Interest','url'=>array('admin')),
);
?>

<h1>View Interest #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'icon_path',
	),
)); ?>
