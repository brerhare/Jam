<?php
$this->breadcrumbs=array(
	'Content Blocks'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Slider','url'=>array('index')),
	array('label'=>'Create Slider','url'=>array('create')),
	array('label'=>'Update Slider','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Slider','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sliders','url'=>array('admin')),
);
?>

<h1>View Slider #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'slider',
		'sequence',
		'title',
		'content',
	),
)); ?>
