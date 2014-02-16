<?php
$this->breadcrumbs=array(
	'Food Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List FoodType','url'=>array('index')),
	array('label'=>'Create FoodType','url'=>array('create')),
	array('label'=>'Update FoodType','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete FoodType','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FoodType','url'=>array('admin')),
);
?>

<h1>View FoodType #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
