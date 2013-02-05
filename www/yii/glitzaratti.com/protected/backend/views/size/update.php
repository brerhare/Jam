<?php
/* @var $this SizeController */
/* @var $model Size */

/* $this->breadcrumbs=array(
	'Sizes'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
); */

$this->menu=array(
/*	array('label'=>'List Size', 'url'=>array('index')),
	array('label'=>'Create Size', 'url'=>array('create')),
	array('label'=>'View Size', 'url'=>array('view', 'id'=>$model->id)), */
	array('label'=>'Manage Sizes', 'url'=>array('admin')),
);
?>

<h1>Update Size <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>