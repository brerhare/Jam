<?php
$this->breadcrumbs=array(
	'Content Blocks'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ContentBlock','url'=>array('index')),
	array('label'=>'Create ContentBlock','url'=>array('create')),
	array('label'=>'View ContentBlock','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage ContentBlock','url'=>array('admin')),
);
?>

<h1>Update ContentBlock <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>