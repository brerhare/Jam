<?php
$this->breadcrumbs=array(
	'Carousel Content',
);

$this->menu=array(
	array('label'=>'Create Carousel Content','url'=>array('create')),
	array('label'=>'Manage Carousel Content','url'=>array('admin')),
);
?>

<h1>Carousel Blocks</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
