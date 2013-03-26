<?php
$this->breadcrumbs=array(
	'Occupancy Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OccupancyType','url'=>array('index')),
	array('label'=>'Manage OccupancyType','url'=>array('admin')),
);
?>

<h1>Create OccupancyType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>