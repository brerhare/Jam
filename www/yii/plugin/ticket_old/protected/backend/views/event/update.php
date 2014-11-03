<?php
/*$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);*/

$this->menu=array(
	array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h4>Update Event <?php echo $model->title; ?></h4>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
