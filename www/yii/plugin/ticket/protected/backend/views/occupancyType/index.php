<?php
$this->breadcrumbs=array(
	'Occupancy Types',
);

$this->menu=array(
	array('label'=>'Create OccupancyType','url'=>array('create')),
	array('label'=>'Manage OccupancyType','url'=>array('admin')),
);
?>

<h1>Occupancy Types</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
