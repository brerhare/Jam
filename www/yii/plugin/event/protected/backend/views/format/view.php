<?php
$this->breadcrumbs=array(
	'Formats'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Format','url'=>array('index')),
	array('label'=>'Create Format','url'=>array('create')),
	array('label'=>'Update Format','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Format','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Format','url'=>array('admin')),
);
?>

<h4>View Format #<?php echo $model->id; ?></h4>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'icon_path',
	),
)); ?>
