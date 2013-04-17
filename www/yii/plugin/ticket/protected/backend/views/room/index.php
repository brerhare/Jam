<?php
$this->breadcrumbs=array(
	'Rooms',
);

$this->menu=array(
	array('label'=>'Create Room','url'=>array('create')),
	array('label'=>'Manage Room','url'=>array('admin')),
);
?>

<h1>Rooms</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
