<?php
$this->breadcrumbs=array(
	'Tab Blocks'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List TabBlock','url'=>array('index')),
	array('label'=>'Create TabBlock','url'=>array('create')),
	array('label'=>'Update TabBlock','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete TabBlock','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TabBlock','url'=>array('admin')),
);
?>

<h1>View TabBlock #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sequence',
		'title',
		'content',
		'image',
	),
)); ?>
