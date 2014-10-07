<?php
$this->breadcrumbs=array(
	'Jelly Settings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JellySetting','url'=>array('index')),
	array('label'=>'Create JellySetting','url'=>array('create')),
	array('label'=>'Update JellySetting','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete JellySetting','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellySetting','url'=>array('admin')),
);
?>

<h1>View JellySetting #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'email',
	),
)); ?>
