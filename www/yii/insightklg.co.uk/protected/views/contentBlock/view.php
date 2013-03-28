<?php
$this->breadcrumbs=array(
	'Content Blocks'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List ContentBlock','url'=>array('index')),
	array('label'=>'Create ContentBlock','url'=>array('create')),
	array('label'=>'Update ContentBlock','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete ContentBlock','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ContentBlock','url'=>array('admin')),
);
?>

<h1>View ContentBlock #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sequence',
		'title',
		'url',
		'content',
	),
)); ?>
