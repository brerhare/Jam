<?php
$this->breadcrumbs=array(
	'Content Blocks'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List CarouselBlock','url'=>array('index')),
	array('label'=>'Create CarouselBlock','url'=>array('create')),
	array('label'=>'Update CarouselBlock','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete CarouselBlock','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CarouselBlock','url'=>array('admin')),
);
?>

<h1>View CarouselBlock #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sequence',
		'title',
		'content',
	),
)); ?>
