<?php
$this->breadcrumbs=array(
	'Ws'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ws','url'=>array('index')),
	array('label'=>'Create Ws','url'=>array('create')),
	array('label'=>'View Ws','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Ws','url'=>array('admin')),
);
?>

<h4>Update Ws <?php echo $model->id; ?></h4>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
