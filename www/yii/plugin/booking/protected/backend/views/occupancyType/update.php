<?php
$this->breadcrumbs=array(
	'Occupancy Types'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OccupancyType','url'=>array('index')),
	array('label'=>'Create OccupancyType','url'=>array('create')),
	array('label'=>'View OccupancyType','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage OccupancyType','url'=>array('admin')),
);
?>

<h1>Update OccupancyType <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>