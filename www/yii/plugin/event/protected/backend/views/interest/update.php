<?php
$this->breadcrumbs=array(
	'Interests'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Interest','url'=>array('index')),
	array('label'=>'Create Interest','url'=>array('create')),
	array('label'=>'View Interest','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Interest','url'=>array('admin')),
);
?>

<h1>Update Interest <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>